<?php

namespace App\Http\Controllers;

use App\Enums\CareerEnums;
use App\Models\Application;
use App\Models\CandidateTest;
use App\Models\HrNote;
use App\Models\Interview;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class AdminApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilters = $this->statusFilters();
        $selectedStatus = $request->string('status')->toString();

        $applications = Application::query()
            ->with(['job.department', 'job.division', 'candidateProfile.documents.documentType', 'candidateProfile.educations', 'candidateProfile.experiences'])
            ->when($selectedStatus && isset($statusFilters[$selectedStatus]), fn ($query) => $query->whereIn('status', $statusFilters[$selectedStatus]['statuses']))
            ->when($request->date('date_from'), fn ($query, $dateFrom) => $query->whereDate('created_at', '>=', $dateFrom))
            ->when($request->date('date_to'), fn ($query, $dateTo) => $query->whereDate('created_at', '<=', $dateTo))
            ->when($request->string('keyword')->toString(), fn ($query, $keyword) => $query->where(function ($q) use ($keyword) {
                $q->where('application_number', 'like', "%{$keyword}%")
                    ->orWhereHas('candidateProfile', fn ($candidate) => $candidate->where('full_name', 'like', "%{$keyword}%")->orWhere('email', 'like', "%{$keyword}%"))
                    ->orWhereHas('job', fn ($job) => $job->where('title', 'like', "%{$keyword}%"));
            }))
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('admin.applications.index', ['applications' => $applications, 'statuses' => $statusFilters]);
    }

    public function show(Application $application): View
    {
        $application->load([
            'job.department',
            'job.division',
            'job.position',
            'candidateProfile.educations',
            'candidateProfile.experiences',
            'candidateProfile.documents.documentType',
            'candidateProfile.user',
            'interviews.interviewer',
            'offer',
            'tests',
            'hrNotes.creator',
            'statusHistories.changedBy',
        ]);

        $interviewers = User::query()
            ->whereHas('roles', fn ($q) => $q->whereIn('name', ['SUPERADMIN', 'ADMIN']))
            ->orderBy('name')
            ->get();

        $statusOptions = [
            'DRAFT' => 'Draft',
            'SUBMITTED' => 'Lamaran Dikirim (Submitted)',
            'DOCUMENT_REVIEW' => 'Review Berkas (Document Review)',
            'ADMINISTRATIVE_REVIEW' => 'Lolos Administrasi',
            'TECHNICAL_TEST' => 'Psikotest / Technical Test',
            'HR_INTERVIEW' => 'Interview HRD',
            'USER_INTERVIEW' => 'Interview User / Technical',
            'FINAL_INTERVIEW' => 'Final Interview Management',
            'MEDICAL_CHECK_UP' => 'Medical Check Up (MCU)',
            'OFFERING' => 'Offering (Penawaran Kerja)',
            'HIRED' => 'Hired (Diterima Bekerja)',
            'REJECTED' => 'Rejected (Ditolak)',
            'WITHDRAWN' => 'Withdrawn (Mengundurkan Diri)',
            'CANCELLED' => 'Cancelled (Dibatalkan)',
        ];

        return view('admin.applications.show', [
            'application' => $application,
            'interviewers' => $interviewers,
            'statusOptions' => $statusOptions,
        ]);
    }

    public function updateStatus(Request $request, Application $application): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', Rule::in(CareerEnums::APPLICATION_STATUSES)],
            'note' => ['nullable', 'string'],
        ]);

        if ($application->canTransitionTo($data['status'])) {
            $application->transitionTo($data['status'], auth()->user(), 'HRD_UPDATE_STATUS', $data['note'] ?? null);
        } else {
            $fromStatus = $application->status;
            $application->forceFill(['status' => $data['status']])->save();
            $application->statusHistories()->create([
                'from_status' => $fromStatus,
                'to_status' => $data['status'],
                'changed_by' => auth()->id(),
                'reason' => 'HRD_UPDATE_STATUS',
                'note' => $data['note'] ?? 'Status diperbarui langsung dari dashboard HRD.',
                'changed_at' => now(),
            ]);
        }

        // When application enters Psikotest / Test (TECHNICAL_TEST), auto-assign the 10-minute DISC assessment
        if ($data['status'] === 'TECHNICAL_TEST') {
            $discAssessment = \App\Models\DiscAssessment::query()->where('status', 'PUBLISHED')->latest()->first();
            if (!$discAssessment) {
                $bank = \App\Models\QuestionBank::query()->where('status', 'PUBLISHED')->first();
                if ($bank) {
                    $discAssessment = \App\Models\DiscAssessment::create([
                        'title' => 'Psikotest DISC Rekrutmen ISP',
                        'code' => 'DISC-RECRUIT-ISP',
                        'question_bank_id' => $bank->id,
                        'question_bank_version' => $bank->version,
                        'duration_minutes' => 10,
                        'result_visibility' => 'HRD_ONLY',
                        'status' => 'PUBLISHED',
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            if ($discAssessment) {
                $userId = $application->candidateProfile?->user_id;
                if ($userId) {
                    \App\Models\DiscAssessmentParticipant::firstOrCreate(
                        ['disc_assessment_id' => $discAssessment->id, 'user_id' => $userId],
                        ['application_id' => $application->id, 'status' => 'ASSIGNED', 'invited_at' => now(), 'max_attempts' => 1]
                    );

                    \App\Models\CandidateTest::firstOrCreate(
                        ['application_id' => $application->id, 'test_type' => 'DISC_ASSESSMENT'],
                        [
                            'title' => 'Psikotest DISC Kepribadian (10 Menit)',
                            'instruction' => 'Silakan kerjakan 24 butir soal DISC dalam durasi waktu 10 menit.',
                            'test_url' => route('disc.assessment.instruction', $discAssessment),
                            'scheduled_at' => now(),
                            'deadline_at' => now()->addDays(3),
                            'maximum_score' => 100,
                            'result' => 'PENDING',
                        ]
                    );
                }
            }
        }

        return back()->with('success', 'Status lamaran berhasil diperbarui.');
    }

    public function storeNote(Request $request, Application $application): RedirectResponse
    {
        $data = $request->validate(['type' => ['required', 'string', 'max:50'], 'note' => ['required', 'string']]);
        HrNote::query()->create($data + ['application_id' => $application->id, 'created_by' => auth()->id()]);

        return back()->with('success', 'Catatan HR berhasil ditambahkan.');
    }

    public function storeInterview(Request $request, Application $application): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'string', 'max:80'],
            'interviewer_id' => ['nullable', 'exists:users,id'],
            'scheduled_date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
            'method' => ['required', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
            'meeting_url' => ['nullable', 'url', 'max:255'],
            'instruction' => ['nullable', 'string'],
        ]);

        Interview::query()->create($data + ['application_id' => $application->id, 'status' => 'SCHEDULED']);

        // Auto update application status to matching interview stage if it's currently at earlier stage
        $targetStatus = match ($data['type']) {
            'USER_INTERVIEW', 'Interview User', 'Technical Interview' => 'USER_INTERVIEW',
            'FINAL_INTERVIEW', 'Final Interview', 'Interview Direksi' => 'FINAL_INTERVIEW',
            default => 'HR_INTERVIEW',
        };

        if (in_array($application->status, ['DRAFT', 'SUBMITTED', 'DOCUMENT_REVIEW', 'ADMINISTRATIVE_REVIEW', 'TECHNICAL_TEST'])) {
            $fromStatus = $application->status;
            $application->forceFill(['status' => $targetStatus])->save();
            $application->statusHistories()->create([
                'from_status' => $fromStatus,
                'to_status' => $targetStatus,
                'changed_by' => auth()->id(),
                'reason' => 'SCHEDULED_INTERVIEW',
                'note' => "Jadwal interview ({$data['type']}) telah dibuat untuk tanggal {$data['scheduled_date']}.",
                'changed_at' => now(),
            ]);
        }

        return back()->with('success', 'Jadwal interview berhasil dibuat dan akan langsung tampil pada dashboard kandidat.');
    }

    public function updateInterview(Request $request, Application $application, Interview $interview): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'string', 'max:80'],
            'interviewer_id' => ['nullable', 'exists:users,id'],
            'scheduled_date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
            'method' => ['required', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
            'meeting_url' => ['nullable', 'url', 'max:255'],
            'instruction' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in(['SCHEDULED', 'CONFIRMED', 'COMPLETED', 'CANCELLED', 'RESCHEDULED'])],
        ]);

        $interview->update($data);

        $application->statusHistories()->create([
            'from_status' => $application->status,
            'to_status' => $application->status,
            'changed_by' => auth()->id(),
            'reason' => 'RESCHEDULE_INTERVIEW',
            'note' => "Jadwal interview ({$data['type']}) telah diubah/dijadwalkan ulang ke tanggal {$data['scheduled_date']}.",
            'changed_at' => now(),
        ]);

        return back()->with('success', 'Jadwal interview berhasil diperbarui / di-reschedule.');
    }

    public function destroyInterview(Application $application, Interview $interview): RedirectResponse
    {
        $interviewType = $interview->type;
        $interview->delete();

        $application->statusHistories()->create([
            'from_status' => $application->status,
            'to_status' => $application->status,
            'changed_by' => auth()->id(),
            'reason' => 'DELETE_INTERVIEW',
            'note' => "Jadwal interview ({$interviewType}) telah dihapus dari sistem.",
            'changed_at' => now(),
        ]);

        return back()->with('success', 'Jadwal interview berhasil dihapus.');
    }

    public function destroyOffer(Application $application, Offer $offer): RedirectResponse
    {
        $offer->delete();

        return back()->with('success', 'Offering berhasil dihapus.');
    }

    public function storeTest(Request $request, Application $application): RedirectResponse
    {
        $data = $request->validate([
            'test_type' => ['required', 'string', 'max:80'],
            'title' => ['required', 'string', 'max:255'],
            'instruction' => ['nullable', 'string'],
            'test_url' => ['nullable', 'url', 'max:255'],
            'scheduled_at' => ['nullable', 'date'],
            'deadline_at' => ['nullable', 'date'],
            'passing_score' => ['nullable', 'numeric'],
        ]);

        CandidateTest::query()->create($data + ['application_id' => $application->id, 'maximum_score' => 100, 'result' => 'PENDING']);

        return back()->with('success', 'Test kandidat berhasil dibuat.');
    }

    public function storeOffer(Request $request, Application $application): RedirectResponse
    {
        $data = $request->validate([
            'position' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'string', 'max:80'],
            'salary' => ['nullable', 'numeric'],
            'start_date' => ['nullable', 'date'],
            'expired_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
            'status' => ['nullable', 'string', Rule::in(['DRAFT', 'PREPARED', 'SENT', 'ACCEPTED', 'REJECTED'])],
        ]);

        $offerStatus = $data['status'] ?? 'SENT';

        Offer::query()->updateOrCreate(
            ['application_id' => $application->id],
            [
                'position' => $data['position'],
                'employment_type' => $data['employment_type'],
                'salary' => $data['salary'] ?? null,
                'start_date' => $data['start_date'] ?? null,
                'expired_at' => $data['expired_at'] ?? null,
                'note' => $data['note'] ?? null,
                'status' => $offerStatus,
                'sent_at' => now(),
            ]
        );

        if (!in_array($application->status, ['HIRED', 'REJECTED', 'WITHDRAWN', 'CANCELLED'])) {
            $fromStatus = $application->status;
            $application->forceFill(['status' => 'OFFERING'])->save();
            $application->statusHistories()->create([
                'from_status' => $fromStatus,
                'to_status' => 'OFFERING',
                'changed_by' => auth()->id(),
                'reason' => 'OFFER_ISSUED',
                'note' => 'Penawaran kerja (Offering) telah disiapkan dan diterbitkan untuk kandidat.',
                'changed_at' => now(),
            ]);
        }

        return back()->with('success', 'Offering berhasil disiapkan dan akan langsung tampil pada dashboard kandidat.');
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $statusFilters = $this->statusFilters();
        $selectedStatus = $request->string('status')->toString();

        $applications = Application::query()
            ->with(['job.department', 'job.division', 'job.position', 'candidateProfile.educations', 'candidateProfile.experiences'])
            ->when($selectedStatus && isset($statusFilters[$selectedStatus]), fn ($query) => $query->whereIn('status', $statusFilters[$selectedStatus]['statuses']))
            ->when($request->date('date_from'), fn ($query, $dateFrom) => $query->whereDate('created_at', '>=', $dateFrom))
            ->when($request->date('date_to'), fn ($query, $dateTo) => $query->whereDate('created_at', '<=', $dateTo))
            ->when($request->string('keyword')->toString(), fn ($query, $keyword) => $query->where(function ($q) use ($keyword) {
                $q->where('application_number', 'like', "%{$keyword}%")
                    ->orWhereHas('candidateProfile', fn ($candidate) => $candidate->where('full_name', 'like', "%{$keyword}%")->orWhere('email', 'like', "%{$keyword}%"))
                    ->orWhereHas('job', fn ($job) => $job->where('title', 'like', "%{$keyword}%"));
            }))
            ->oldest('created_at')
            ->get();

        $template = base_path('202608_Report Kandidat Karyawan ISP.xlsx');
        abort_unless(is_file($template), 404, 'Template Excel tidak ditemukan.');

        $filename = now()->format('Ym').'_Report Kandidat Karyawan ISP.xlsx';
        $temporaryPath = tempnam(sys_get_temp_dir(), 'kandidat_isp_').'.xlsx';
        copy($template, $temporaryPath);

        $zip = new ZipArchive();
        abort_unless($zip->open($temporaryPath) === true, 500, 'Template Excel gagal dibuka.');

        $sheetXml = $zip->getFromName('xl/worksheets/sheet2.xml');
        abort_unless($sheetXml !== false, 500, 'Sheet kandidat pada template tidak ditemukan.');

        $lastRow = max(250, $applications->count() + 4);
        $sheetXml = preg_replace('/<dimension ref="[^"]+"\/>/', '<dimension ref="A1:AN'.$lastRow.'"/>', $sheetXml, 1);
        $sheetXml = $this->replaceCandidateSheetRows($sheetXml, $applications, $lastRow);

        $zip->addFromString('xl/worksheets/sheet2.xml', $sheetXml);
        $zip->deleteName('xl/calcChain.xml');
        $zip->close();

        return response()->download($temporaryPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private function statusFilters(): array
    {
        return [
            'review' => [
                'label' => 'Review',
                'statuses' => ['DRAFT', 'SUBMITTED', 'DOCUMENT_REVIEW', 'ADMINISTRATIVE_REVIEW', 'HR_INTERVIEW', 'USER_INTERVIEW', 'FINAL_INTERVIEW', 'MEDICAL_CHECK_UP'],
            ],
            'test' => [
                'label' => 'Psikotes / Test',
                'statuses' => ['TECHNICAL_TEST'],
            ],
            'offering' => [
                'label' => 'Offering',
                'statuses' => ['OFFERING'],
            ],
            'hired' => [
                'label' => 'Hired',
                'statuses' => ['HIRED'],
            ],
            'rejected' => [
                'label' => 'Rejected',
                'statuses' => ['REJECTED', 'WITHDRAWN', 'CANCELLED'],
            ],
        ];
    }

    private function replaceCandidateSheetRows(string $sheetXml, $applications, int $lastRow): string
    {
        $rows = [];
        foreach ($applications as $index => $application) {
            $rows[] = $this->candidateRowXml($application, $index + 1, $index + 5);
        }

        for ($number = $applications->count() + 1; $number <= $lastRow - 4; $number++) {
            $rows[] = $this->emptyCandidateRowXml($number, $number + 4);
        }

        return preg_replace('/<row r="5"[\s\S]*?<\/sheetData>/', implode('', $rows).'</sheetData>', $sheetXml, 1);
    }

    private function candidateRowXml(Application $application, int $number, int $row): string
    {
        $profile = $application->candidateProfile;
        $education = $profile?->educations?->first();
        $experience = $profile?->experiences?->first();
        $birthDate = $profile?->birth_date;
        $applyDate = $application->submitted_at ?? $application->created_at;
        $formattedBirthDate = $birthDate?->format('d-M-y');
        $age = $birthDate ? round($birthDate->diffInDays(now()) / 365, 2) : null;

        $statusLabel = str($application->status)->headline()->toString();
        $phoneWa = trim(($profile?->phone ?? '').' / '.($profile?->whatsapp ?? ''), ' /');

        $values = [
            'A' => ['v' => ($profile?->gender ?? '').($profile?->birth_place ?? '').($birthDate ? $this->excelDate($birthDate) : ''), 's' => 53],
            'B' => ['v' => $number, 's' => 56, 'type' => 'n'],
            'C' => ['v' => $this->excelDate($applyDate), 's' => 96, 'type' => 'n'],
            'D' => ['v' => $application->reference ?? '', 's' => 98],
            'E' => ['v' => $application->job?->title, 's' => 99],
            'F' => ['v' => $profile?->full_name, 's' => 91],
            'G' => ['v' => $profile?->gender, 's' => 13],
            'H' => ['v' => $profile?->nik, 's' => 15],
            'I' => ['v' => $profile?->birth_place, 's' => 14],
            'J' => ['v' => $formattedBirthDate, 's' => 116],
            'K' => ['v' => $age, 's' => 58, 'type' => 'n'],
            'L' => ['v' => $profile?->address, 's' => 59],
            'M' => ['v' => $profile?->rt_rw, 's' => 118],
            'N' => ['v' => $profile?->village, 's' => 59],
            'O' => ['v' => $profile?->district, 's' => 59],
            'P' => ['v' => $profile?->city, 's' => 59],
            'Q' => ['v' => $profile?->province, 's' => 59],
            'R' => ['v' => $phoneWa, 's' => 60],
            'S' => ['v' => $profile?->email, 's' => 69],
            'T' => ['v' => $education?->education_level, 's' => 62],
            'U' => ['v' => $education?->institution, 's' => 14],
            'V' => ['v' => $education?->major, 's' => 14],
            'W' => ['v' => $education?->ipk, 's' => 64],
            'X' => ['v' => $education?->certification, 's' => 63],
            'Y' => ['v' => $profile?->driving_license, 's' => 65],
            'Z' => ['v' => $experience?->company_name, 's' => 57],
            'AA' => ['v' => $experience?->position, 's' => 14],
            'AB' => ['v' => $experience?->work_years, 's' => 66],
            'AC' => ['v' => '', 's' => 55],
            'AD' => ['v' => $statusLabel, 's' => 70],
            'AE' => ['v' => '', 's' => 55],
            'AF' => ['v' => $statusLabel, 's' => 70],
            'AG' => ['v' => '', 's' => 55],
            'AH' => ['v' => $statusLabel, 's' => 70],
            'AI' => ['v' => '', 's' => 71],
            'AJ' => ['v' => $statusLabel, 's' => 70],
            'AK' => ['v' => '', 's' => 67],
            'AL' => ['v' => $application->job?->title, 's' => 17],
            'AM' => ['v' => $application->application_number.' - '.$statusLabel, 's' => 72],
            'AN' => ['v' => '', 's' => 37],
        ];

        return $this->rowXml($row, $values);
    }

    private function emptyCandidateRowXml(int $number, int $row): string
    {
        return $this->rowXml($row, [
            'A' => ['v' => '', 's' => 53],
            'B' => ['v' => $number, 's' => 56, 'type' => 'n'],
            'C' => ['v' => '', 's' => 96],
            'D' => ['v' => '', 's' => 98],
            'E' => ['v' => '', 's' => 99],
            'F' => ['v' => '', 's' => 91],
            'G' => ['v' => '', 's' => 13],
            'H' => ['v' => '', 's' => 15],
            'I' => ['v' => '', 's' => 14],
            'J' => ['v' => '', 's' => 116],
            'K' => ['v' => '', 's' => 58],
            'L' => ['v' => '', 's' => 59],
            'M' => ['v' => '', 's' => 118],
            'N' => ['v' => '', 's' => 59],
            'O' => ['v' => '', 's' => 59],
            'P' => ['v' => '', 's' => 59],
            'Q' => ['v' => '', 's' => 59],
            'R' => ['v' => '', 's' => 60],
            'S' => ['v' => '', 's' => 69],
            'T' => ['v' => '', 's' => 62],
            'U' => ['v' => '', 's' => 14],
            'V' => ['v' => '', 's' => 14],
            'W' => ['v' => '', 's' => 64],
            'X' => ['v' => '', 's' => 63],
            'Y' => ['v' => '', 's' => 65],
            'Z' => ['v' => '', 's' => 57],
            'AA' => ['v' => '', 's' => 14],
            'AB' => ['v' => '', 's' => 66],
            'AC' => ['v' => '', 's' => 55],
            'AD' => ['v' => '', 's' => 70],
            'AE' => ['v' => '', 's' => 55],
            'AF' => ['v' => '', 's' => 70],
            'AG' => ['v' => '', 's' => 55],
            'AH' => ['v' => '', 's' => 70],
            'AI' => ['v' => '', 's' => 71],
            'AJ' => ['v' => '', 's' => 70],
            'AK' => ['v' => '', 's' => 67],
            'AL' => ['v' => '', 's' => 17],
            'AM' => ['v' => '', 's' => 68],
            'AN' => ['v' => '', 's' => 37],
        ]);
    }

    private function rowXml(int $row, array $cells): string
    {
        $xml = '<row r="'.$row.'" spans="1:40" ht="96" customHeight="1">';

        foreach ($cells as $column => $cell) {
            $xml .= $this->cellXml($column.$row, $cell['v'] ?? '', (int) $cell['s'], $cell['type'] ?? 's');
        }

        return $xml.'</row>';
    }

    private function cellXml(string $reference, mixed $value, int $style, string $type = 's'): string
    {
        if ($value === null || $value === '') {
            return '<c r="'.$reference.'" s="'.$style.'"/>';
        }

        if ($type === 'n' && is_numeric($value)) {
            return '<c r="'.$reference.'" s="'.$style.'"><v>'.$value.'</v></c>';
        }

        return '<c r="'.$reference.'" s="'.$style.'" t="inlineStr"><is><t>'.$this->xml($value).'</t></is></c>';
    }

    private function excelDate($date): ?int
    {
        if (! $date) {
            return null;
        }

        return (int) floor($date->copy()->startOfDay()->diffInDays(\Carbon\Carbon::create(1899, 12, 30), false) * -1);
    }

    private function xml(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }
}
