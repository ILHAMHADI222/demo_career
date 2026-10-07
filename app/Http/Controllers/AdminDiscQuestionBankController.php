<?php

namespace App\Http\Controllers;

use App\Models\DiscQuestion;
use App\Models\DiscQuestionOption;
use App\Models\PsikotesStageQuestion;
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
            ->withCount(['questions', 'assessments', 'stageQuestions'])
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
            'assessment_type' => 'PSIKOTES_3_STAGE',
            'status' => 'DRAFT',
            'created_by' => auth()->id(),
        ]);

        AuditLogger::log('CREATE_QUESTION_BANK', 'PSIKOTES', $bank);

        return redirect()->route('admin.disc.banks.edit', $bank)->with('success', 'Bank Soal Psikotes berhasil dibuat. Silakan kelola butir pertanyaan untuk 3 sub-tes.');
    }

    public function show(QuestionBank $bank): View
    {
        $bank->load(['questions.options', 'deretQuestions', 'analogiQuestions']);

        return view('admin.disc.banks.show', compact('bank'));
    }

    public function edit(QuestionBank $bank): View
    {
        $bank->load(['questions.options', 'deretQuestions', 'analogiQuestions']);

        return view('admin.disc.banks.edit', compact('bank'));
    }

    public function update(Request $request, QuestionBank $bank): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:question_banks,code,' . $bank->id],
            'description' => ['nullable', 'string'],
            'version' => ['required', 'string', 'max:20'],
            'status' => ['nullable', 'string', 'in:DRAFT,PUBLISHED,ARCHIVED'],
        ]);

        $bank->update($data + ['updated_by' => auth()->id()]);
        AuditLogger::log('UPDATE_QUESTION_BANK', 'PSIKOTES', $bank);

        return back()->with('success', "Informasi Bank Soal '{$bank->name}' berhasil diperbarui.");
    }

    // --- MANAJEMEN SOAL SUB-TES 1 (DERET) & SUB-TES 2 (ANALOGI) ---
    public function storeStageQuestion(Request $request, QuestionBank $bank): RedirectResponse
    {
        $data = $request->validate([
            'stage' => ['required', 'string', 'in:STAGE_1_DERET,STAGE_2_ANALOGI'],
            'question_number' => ['required', 'integer', 'min:1'],
            'question_text' => ['required', 'string'],
            'correct_answer_1' => ['required', 'string'],
            'correct_answer_2' => ['nullable', 'string'],
            'explanation' => ['nullable', 'string'],
            'options' => ['nullable', 'array'],
        ]);

        PsikotesStageQuestion::create([
            'question_bank_id' => $bank->id,
            'stage' => $data['stage'],
            'question_number' => $data['question_number'],
            'question_text' => $data['question_text'],
            'options_json' => $data['options'] ?? null,
            'correct_answer_1' => $data['correct_answer_1'],
            'correct_answer_2' => $data['correct_answer_2'] ?? null,
            'explanation' => $data['explanation'] ?? null,
            'active' => true,
        ]);

        AuditLogger::log('CREATE_STAGE_QUESTION', 'PSIKOTES', $bank, ['stage' => $data['stage'], 'question_number' => $data['question_number']]);

        return redirect()->route('admin.disc.banks.edit', $bank)->with('success', 'Butir soal psikotes berhasil ditambahkan.');
    }

    public function updateStageQuestion(Request $request, QuestionBank $bank, PsikotesStageQuestion $question): RedirectResponse
    {
        $data = $request->validate([
            'question_text' => ['required', 'string'],
            'correct_answer_1' => ['required', 'string'],
            'correct_answer_2' => ['nullable', 'string'],
            'explanation' => ['nullable', 'string'],
            'options' => ['nullable', 'array'],
        ]);

        $question->update([
            'question_text' => $data['question_text'],
            'options_json' => $data['options'] ?? $question->options_json,
            'correct_answer_1' => $data['correct_answer_1'],
            'correct_answer_2' => $data['correct_answer_2'] ?? null,
            'explanation' => $data['explanation'] ?? null,
        ]);

        AuditLogger::log('UPDATE_STAGE_QUESTION', 'PSIKOTES', $bank, ['id' => $question->id]);

        return redirect()->route('admin.disc.banks.edit', $bank)->with('success', "Soal No. {$question->question_number} berhasil diperbarui.");
    }

    public function deleteStageQuestion(QuestionBank $bank, PsikotesStageQuestion $question): RedirectResponse
    {
        $stage = $question->stage;
        $num = $question->question_number;
        $question->delete();

        AuditLogger::log('DELETE_STAGE_QUESTION', 'PSIKOTES', $bank, ['stage' => $stage, 'question_number' => $num]);

        return redirect()->route('admin.disc.banks.edit', $bank)->with('success', "Soal No. {$num} berhasil dihapus.");
    }

    // --- MANAJEMEN SOAL SUB-TES 3 (KEPRIBADIAN / MOST-LEAST) ---
    public function storeQuestion(Request $request, QuestionBank $bank): RedirectResponse
    {
        $data = $request->validate([
            'question_number' => ['required', 'integer', 'min:1', 'max:50'],
            'instruction' => ['nullable', 'string', 'max:255'],
            'options' => ['required', 'array', 'size:4'],
            'options.*.label' => ['required', 'string', 'max:5'],
            'options.*.statement' => ['required', 'string'],
            'options.*.most_mapping' => ['required', 'string', 'in:D,I,S,C,*'],
            'options.*.least_mapping' => ['required', 'string', 'in:D,I,S,C,*'],
        ]);

        DB::transaction(function () use ($data, $bank) {
            $question = DiscQuestion::updateOrCreate(
                [
                    'question_bank_id' => $bank->id,
                    'question_number' => $data['question_number'],
                ],
                [
                    'instruction' => $data['instruction'] ?? null,
                    'active' => true,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]
            );

            foreach ($data['options'] as $idx => $opt) {
                DiscQuestionOption::updateOrCreate(
                    [
                        'disc_question_id' => $question->id,
                        'option_number' => $idx + 1,
                    ],
                    [
                        'option_label' => $opt['label'],
                        'statement' => $opt['statement'],
                        'most_mapping' => strtoupper($opt['most_mapping']),
                        'least_mapping' => strtoupper($opt['least_mapping']),
                        'display_order' => $idx + 1,
                        'active' => true,
                    ]
                );
            }
        });

        AuditLogger::log('STORE_QUESTION_TO_BANK', 'PSIKOTES', $bank, ['question_number' => $data['question_number']]);

        return redirect()->route('admin.disc.banks.edit', $bank)->with('success', "Butir Soal Kepribadian No. {$data['question_number']} berhasil disimpan.");
    }

    public function deleteQuestion(QuestionBank $bank, DiscQuestion $question): RedirectResponse
    {
        $num = $question->question_number;
        $question->options()->delete();
        $question->delete();

        AuditLogger::log('DELETE_QUESTION_FROM_BANK', 'PSIKOTES', $bank, ['question_number' => $num]);

        return redirect()->route('admin.disc.banks.edit', $bank)->with('success', "Butir Soal No. {$num} berhasil dihapus.");
    }

    public function importStandardQuestions(QuestionBank $bank): RedirectResponse
    {
        $sourceBank = QuestionBank::where('status', 'PUBLISHED')->first();
        if (!$sourceBank) {
            return back()->withErrors(['error' => 'Master Bank Soal standar belum ditemukan di database.']);
        }

        DB::transaction(function () use ($bank, $sourceBank) {
            // Salin Soal Deret & Analogi
            foreach ($sourceBank->stageQuestions as $sq) {
                PsikotesStageQuestion::updateOrCreate(
                    [
                        'question_bank_id' => $bank->id,
                        'stage' => $sq->stage,
                        'question_number' => $sq->question_number,
                    ],
                    [
                        'question_text' => $sq->question_text,
                        'options_json' => $sq->options_json,
                        'correct_answer_1' => $sq->correct_answer_1,
                        'correct_answer_2' => $sq->correct_answer_2,
                        'explanation' => $sq->explanation,
                        'active' => true,
                    ]
                );
            }

            // Salin Soal Kepribadian
            foreach ($sourceBank->questions()->with('options')->get() as $q) {
                $newQ = DiscQuestion::updateOrCreate(
                    [
                        'question_bank_id' => $bank->id,
                        'question_number' => $q->question_number,
                    ],
                    [
                        'instruction' => $q->instruction,
                        'active' => true,
                        'created_by' => auth()->id(),
                    ]
                );

                foreach ($q->options as $opt) {
                    DiscQuestionOption::updateOrCreate(
                        [
                            'disc_question_id' => $newQ->id,
                            'option_number' => $opt->option_number,
                        ],
                        [
                            'option_label' => $opt->option_label,
                            'statement' => $opt->statement,
                            'most_mapping' => $opt->most_mapping,
                            'least_mapping' => $opt->least_mapping,
                            'display_order' => $opt->display_order,
                            'active' => true,
                        ]
                    );
                }
            }
        });

        AuditLogger::log('IMPORT_STANDARD_QUESTIONS', 'PSIKOTES', $bank);

        return redirect()->route('admin.disc.banks.edit', $bank)->with('success', 'Berhasil menyalin seluruh paket 3 sub-tes soal standar Psikotes ISP ke Bank Soal ini.');
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

        AuditLogger::log('UPDATE_SCORING_KEYS', 'PSIKOTES', $bank);

        return back()->with('success', 'Kunci jawaban scoring dan pemetaan Most/Least berhasil disimpan.');
    }

    public function publish(QuestionBank $bank): RedirectResponse
    {
        $deretCount = $bank->deretQuestions()->count();
        $analogiCount = $bank->analogiQuestions()->count();
        $discCount = $bank->questions()->count();

        if ($deretCount < 25 || $analogiCount < 50 || $discCount < 24) {
            return back()->withErrors(['error' => "Bank Soal harus memiliki paket lengkap: 25 Soal Deret Hitung, 50 Soal Analogi Verbal, dan 24 Soal Kepribadian sebelum dipublikasikan. (Saat ini: Deret: {$deretCount}, Analogi: {$analogiCount}, Kepribadian: {$discCount})"]);
        }

        $bank->update(['status' => 'PUBLISHED', 'updated_by' => auth()->id()]);
        AuditLogger::log('PUBLISH_QUESTION_BANK', 'PSIKOTES', $bank);

        return back()->with('success', 'Bank Soal Psikotes berhasil dipublikasikan dan siap digunakan untuk Asesmen.');
    }

    public function duplicate(QuestionBank $bank): RedirectResponse
    {
        $bank->load(['questions.options', 'stageQuestions']);

        $newBank = DB::transaction(function () use ($bank) {
            $cloned = QuestionBank::create([
                'name' => $bank->name . ' (Copy)',
                'code' => $bank->code . '-COPY-' . strtoupper(bin2hex(random_bytes(2))),
                'description' => $bank->description,
                'assessment_type' => 'PSIKOTES_3_STAGE',
                'version' => $bank->version . '.1',
                'status' => 'DRAFT',
                'created_by' => auth()->id(),
            ]);

            // Salin stage questions
            foreach ($bank->stageQuestions as $sq) {
                PsikotesStageQuestion::create([
                    'question_bank_id' => $cloned->id,
                    'stage' => $sq->stage,
                    'question_number' => $sq->question_number,
                    'question_text' => $sq->question_text,
                    'options_json' => $sq->options_json,
                    'correct_answer_1' => $sq->correct_answer_1,
                    'correct_answer_2' => $sq->correct_answer_2,
                    'explanation' => $sq->explanation,
                    'active' => $sq->active,
                ]);
            }

            // Salin kepribadian questions
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

        AuditLogger::log('DUPLICATE_QUESTION_BANK', 'PSIKOTES', $newBank);

        return redirect()->route('admin.disc.banks.edit', $newBank)->with('success', 'Bank Soal Psikotes berhasil diduplikasi menjadi versi Draft baru.');
    }

    public function destroy(QuestionBank $bank): RedirectResponse
    {
        $assessmentsCount = $bank->assessments()->count();
        if ($assessmentsCount > 0) {
            return back()->withErrors(['error' => "Bank Soal '{$bank->name}' tidak dapat dihapus karena sedang digunakan pada {$assessmentsCount} jadwal asesmen."]);
        }

        $bankName = $bank->name;
        $bank->stageQuestions()->delete();
        foreach ($bank->questions as $q) {
            $q->options()->delete();
            $q->delete();
        }
        $bank->delete();
        AuditLogger::log('DELETE_QUESTION_BANK', 'PSIKOTES', $bank);

        return redirect()->route('admin.disc.banks.index')->with('success', "Bank Soal '{$bankName}' berhasil dihapus.");
    }
}
