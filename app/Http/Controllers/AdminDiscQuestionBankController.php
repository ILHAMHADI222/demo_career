<?php

namespace App\Http\Controllers;

use App\Models\DiscQuestion;
use App\Models\DiscQuestionOption;
use App\Models\QuestionBank;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDiscQuestionBankController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $keyword = $request->string('keyword')->toString();

        $banks = QuestionBank::query()
            ->withCount(['questions', 'assessments'])
            ->with(['creator'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($keyword, fn ($q) => $q->where(fn ($sub) => $sub->where('name', 'like', "%{$keyword}%")->orWhere('code', 'like', "%{$keyword}%")))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => QuestionBank::count(),
            'published' => QuestionBank::where('status', 'PUBLISHED')->count(),
            'draft' => QuestionBank::where('status', 'DRAFT')->count(),
            'archived' => QuestionBank::where('status', 'ARCHIVED')->count(),
        ];

        return view('admin.disc.banks.index', compact('banks', 'stats', 'status', 'keyword'));
    }

    public function create(): View
    {
        return view('admin.disc.banks.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:question_banks,code'],
            'description' => ['nullable', 'string'],
            'version' => ['required', 'string', 'max:20'],
        ]);

        $bank = QuestionBank::create($data + [
            'assessment_type' => 'DISC',
            'status' => 'DRAFT',
            'created_by' => auth()->id(),
        ]);

        AuditLogger::log('CREATE_QUESTION_BANK', 'DISC', $bank);

        return redirect()->route('admin.disc.banks.edit', $bank)->with('success', 'Bank Soal DISC berhasil dibuat. Silakan kelola butir pertanyaan.');
    }

    public function show(QuestionBank $bank): View
    {
        $bank->load(['questions.options']);

        return view('admin.disc.banks.show', compact('bank'));
    }

    public function edit(QuestionBank $bank): View
    {
        $bank->load(['questions.options']);

        return view('admin.disc.banks.edit', compact('bank'));
    }

    public function update(Request $request, QuestionBank $bank): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:question_banks,code,' . $bank->id],
            'description' => ['nullable', 'string'],
            'version' => ['required', 'string', 'max:20'],
        ]);

        $bank->update($data + ['updated_by' => auth()->id()]);
        AuditLogger::log('UPDATE_QUESTION_BANK', 'DISC', $bank);

        return redirect()->route('admin.disc.banks.edit', $bank)->with('success', 'Informasi Bank Soal berhasil diperbarui.');
    }

    public function scoringKeys(QuestionBank $bank): View
    {
        $bank->load(['questions.options']);

        return view('admin.disc.banks.scoring_keys', compact('bank'));
    }

    public function updateScoringKeys(Request $request, QuestionBank $bank): RedirectResponse
    {
        $data = $request->validate([
            'options' => ['required', 'array'],
            'options.*.most_mapping' => ['required', 'string', 'in:D,I,S,C,*'],
            'options.*.least_mapping' => ['required', 'string', 'in:D,I,S,C,*'],
            'options.*.statement' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['options'] as $optionId => $vals) {
                $opt = DiscQuestionOption::find($optionId);
                if ($opt) {
                    $update = [
                        'most_mapping' => strtoupper($vals['most_mapping']),
                        'least_mapping' => strtoupper($vals['least_mapping']),
                    ];
                    if (!empty($vals['statement'])) {
                        $update['statement'] = $vals['statement'];
                    }
                    $opt->update($update);
                }
            }
        });

        AuditLogger::log('UPDATE_SCORING_KEYS', 'DISC', $bank);

        return back()->with('success', 'Kunci jawaban scoring dan pemetaan Most/Least berhasil disimpan.');
    }

    public function publish(QuestionBank $bank): RedirectResponse
    {
        $questionCount = $bank->questions()->count();
        if ($questionCount < 24) {
            return back()->withErrors(['error' => "Bank Soal harus memiliki minimal 24 butir soal lengkap sebelum dipublikasikan. (Saat ini: {$questionCount} soal)"]);
        }

        $bank->update(['status' => 'PUBLISHED', 'updated_by' => auth()->id()]);
        AuditLogger::log('PUBLISH_QUESTION_BANK', 'DISC', $bank);

        return back()->with('success', 'Bank Soal DISC berhasil dipublikasikan dan siap digunakan untuk Asesmen.');
    }

    public function duplicate(QuestionBank $bank): RedirectResponse
    {
        $bank->load(['questions.options']);

        $newBank = DB::transaction(function () use ($bank) {
            $cloned = QuestionBank::create([
                'name' => $bank->name . ' (Copy)',
                'code' => $bank->code . '-COPY-' . strtoupper(bin2hex(random_bytes(2))),
                'description' => $bank->description,
                'assessment_type' => 'DISC',
                'version' => $bank->version . '.1',
                'status' => 'DRAFT',
                'created_by' => auth()->id(),
            ]);

            foreach ($bank->questions as $q) {
                $newQ = DiscQuestion::create([
                    'question_bank_id' => $cloned->id,
                    'question_number' => $q->question_number,
                    'instruction' => $q->instruction,
                    'active' => $q->active,
                    'created_by' => auth()->id(),
                ]);

                foreach ($q->options as $opt) {
                    DiscQuestionOption::create([
                        'disc_question_id' => $newQ->id,
                        'option_number' => $opt->option_number,
                        'option_label' => $opt->option_label,
                        'statement' => $opt->statement,
                        'most_mapping' => $opt->most_mapping,
                        'least_mapping' => $opt->least_mapping,
                        'display_order' => $opt->display_order,
                        'active' => $opt->active,
                    ]);
                }
            }

            return $cloned;
        });

        AuditLogger::log('DUPLICATE_QUESTION_BANK', 'DISC', $newBank);

        return redirect()->route('admin.disc.banks.edit', $newBank)->with('success', 'Bank Soal berhasil diduplikasi menjadi versi Draft baru.');
    }

    public function destroy(QuestionBank $bank): RedirectResponse
    {
        if ($bank->assessments()->exists()) {
            return back()->withErrors(['error' => 'Bank Soal tidak dapat dihapus karena telah digunakan dalam asesmen aktif.']);
        }

        $bank->delete();
        AuditLogger::log('DELETE_QUESTION_BANK', 'DISC', $bank);

        return redirect()->route('admin.disc.banks.index')->with('success', 'Bank Soal berhasil diarsipkan/dihapus.');
    }
}
