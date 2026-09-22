<x-layouts.app title="Kelola Bank Soal - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">

            {{-- TOP ACTION BAR --}}
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <a href="{{ route('admin.disc.banks.index') }}"
                   class="inline-flex items-center gap-1.5 rounded-full border border-[#322d2a]/15 bg-white px-4 py-1.5 text-xs font-medium text-[#322d2a] shadow-sm transition hover:bg-[#f0efe9]">
                    ← Kembali ke Bank Soal
                </a>

                <div class="flex flex-wrap items-center gap-2">
                    <form method="POST" action="{{ route('admin.disc.banks.import-standard', $bank) }}" onsubmit="return confirm('Salin seluruh paket soal standar (25 Deret, 50 Analogi, 24 Kepribadian) ke bank soal ini?')">
                        @csrf
                        <button type="submit" class="rounded-full border border-blue-200 bg-blue-50 px-4 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100 shadow-xs">
                            📥 Salin Paket Standar (99 Soal)
                        </button>
                    </form>

                    <a href="{{ route('admin.disc.banks.scoring-keys', $bank) }}"
                       class="rounded-full border border-amber-200 bg-amber-50 px-4 py-1.5 text-xs font-semibold text-amber-800 hover:bg-amber-100">
                        ⚡ Kunci Scoring Kepribadian
                    </a>

                    @if ($bank->status !== 'PUBLISHED')
                        <form method="POST" action="{{ route('admin.disc.banks.publish', $bank) }}">
                            @csrf
                            <button type="submit"
                                    class="rounded-full bg-emerald-600 px-5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-emerald-700">
                                ✓ Publikasikan Bank Soal
                            </button>
                        </form>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3.5 py-1 text-xs font-semibold text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> PUBLISHED
                        </span>
                    @endif

                    <button type="button"
                            onclick="openDeleteModal()"
                            class="rounded-full border border-rose-200 bg-rose-50 px-3.5 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-600 hover:text-white transition shadow-xs">
                        ✕ Hapus Bank Soal
                    </button>
                </div>
            </div>

            {{-- METADATA CARD --}}
            <div class="rounded-[28px] sm:rounded-[32px] border border-white bg-white p-5 sm:p-6 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="rounded-md bg-[#f0efe9] px-2.5 py-0.5 font-mono text-xs font-bold text-[#322d2a]">{{ $bank->code }}</span>
                            <span class="text-xs text-[#6f6b67]">Versi {{ $bank->version }}</span>
                        </div>
                        <h1 class="mt-1.5 text-xl sm:text-2xl font-light text-[#322d2a]">
                            {{ $bank->name }}
                        </h1>
                        <p class="mt-1 text-xs text-[#6f6b67]">
                            {{ $bank->description ?? 'Paket 3 Sub-Tes Psikotes Standar PT Indodata Solusi Persada.' }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 rounded-[20px] bg-[#f0efe9] p-3 text-xs">
                        <div class="text-center px-3 py-1 bg-white rounded-[14px]">
                            <p class="text-[10px] text-[#6f6b67]">1. Deret</p>
                            <p class="font-bold text-blue-700">{{ $bank->deretQuestions->count() }} / 25</p>
                        </div>
                        <div class="text-center px-3 py-1 bg-white rounded-[14px]">
                            <p class="text-[10px] text-[#6f6b67]">2. Analogi</p>
                            <p class="font-bold text-emerald-700">{{ $bank->analogiQuestions->count() }} / 50</p>
                        </div>
                        <div class="text-center px-3 py-1 bg-white rounded-[14px]">
                            <p class="text-[10px] text-[#6f6b67]">3. Kepribadian</p>
                            <p class="font-bold text-purple-700">{{ $bank->questions->count() }} / 24</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3 SUBTEST TABS NAVIGATION --}}
            <div class="mt-6">
                <div class="flex flex-wrap gap-2 border-b border-[#322d2a]/10 pb-3" x-data="{ tab: 'deret' }">

                    {{-- TAB 1: DERET HITUNG --}}
                    <div class="w-full space-y-5">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">1</span>
                                <h2 class="text-lg font-medium text-[#322d2a]">Sub-Tes 1: Deret Hitung ({{ $bank->deretQuestions->count() }} Soal)</h2>
                            </div>
                            <span class="rounded-full bg-blue-50 border border-blue-200 px-3 py-1 text-xs font-semibold text-blue-800">
                                ⏱ Durasi Default: 10 Menit (Target: 25 Soal)
                            </span>
                        </div>

                        {{-- FORM TAMBAH SOAL DERET --}}
                        <details class="group rounded-[20px] border border-blue-200 bg-white p-4 shadow-xs">
                            <summary class="flex cursor-pointer items-center justify-between font-semibold text-xs text-blue-700 hover:text-blue-900">
                                <span>➕ Tambah Butir Soal Deret Hitung Baru (Manual)</span>
                                <span class="transition group-open:rotate-180">▼</span>
                            </summary>
                            <form method="POST" action="{{ route('admin.disc.banks.stage-questions.store', $bank) }}" class="mt-4 space-y-3 pt-3 border-t border-blue-100 text-xs">
                                @csrf
                                <input type="hidden" name="stage" value="STAGE_1_DERET">
                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block font-medium text-[#322d2a] mb-1">Nomor Soal *</label>
                                        <input type="number" name="question_number" min="1" value="{{ $bank->deretQuestions->count() + 1 }}" required
                                               class="w-full rounded-xl border border-[#322d2a]/20 px-3 py-2 text-xs focus:border-blue-500 focus:outline-hidden">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label class="block font-medium text-[#322d2a] mb-1">Pola Barisan / Deret Angka * (Contoh: 3, 6, 9, 12, ...)</label>
                                        <input type="text" name="question_text" placeholder="Contoh: 6  9  12  15  18  ..." required
                                               class="w-full rounded-xl border border-[#322d2a]/20 px-3 py-2 text-xs font-mono focus:border-blue-500 focus:outline-hidden">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block font-medium text-[#322d2a] mb-1">Kunci Angka 1 (Wajib) *</label>
                                        <input type="text" name="correct_answer_1" placeholder="Contoh: 21" required
                                               class="w-full rounded-xl border border-[#322d2a]/20 px-3 py-2 text-xs font-mono focus:border-blue-500 focus:outline-hidden">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-[#322d2a] mb-1">Kunci Angka 2 (Opsional jika 2 angka)</label>
                                        <input type="text" name="correct_answer_2" placeholder="Contoh: 24"
                                               class="w-full rounded-xl border border-[#322d2a]/20 px-3 py-2 text-xs font-mono focus:border-blue-500 focus:outline-hidden">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-[#322d2a] mb-1">Penjelasan Pola (Opsional)</label>
                                        <input type="text" name="explanation" placeholder="Pola: +3 tiap langkah"
                                               class="w-full rounded-xl border border-[#322d2a]/20 px-3 py-2 text-xs focus:border-blue-500 focus:outline-hidden">
                                    </div>
                                </div>
                                <div class="flex justify-end pt-1">
                                    <button type="submit" class="rounded-full bg-blue-600 px-5 py-1.5 font-bold text-white shadow-xs hover:bg-blue-700">
                                        Simpan Soal Deret
                                    </button>
                                </div>
                            </form>
                        </details>

                        {{-- DAFTAR SOAL DERET HITUNG --}}
                        <div class="overflow-hidden rounded-[24px] border border-white bg-white shadow-sm">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-[#322d2a]/10 text-xs">
                                    <thead class="bg-[#322d2a] text-left text-[11px] font-normal uppercase tracking-wider text-white">
                                        <tr>
                                            <th class="px-4 py-3">No</th>
                                            <th class="px-4 py-3">Pola Deret Angka</th>
                                            <th class="px-4 py-3">Kunci Angka 1</th>
                                            <th class="px-4 py-3">Kunci Angka 2</th>
                                            <th class="px-4 py-3">Penjelasan Pola</th>
                                            <th class="px-4 py-3 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#322d2a]/10 bg-white">
                                        @forelse($bank->deretQuestions as $dq)
                                            <tr class="transition hover:bg-blue-50/40">
                                                <td class="px-4 py-3 font-bold text-[#322d2a]">{{ $dq->question_number }}</td>
                                                <td class="px-4 py-3 font-mono font-bold text-[#322d2a]">{{ $dq->question_text }}</td>
                                                <td class="px-4 py-3"><span class="rounded bg-blue-100 px-2 py-0.5 font-mono font-bold text-blue-900">{{ $dq->correct_answer_1 }}</span></td>
                                                <td class="px-4 py-3"><span class="rounded bg-blue-100 px-2 py-0.5 font-mono font-bold text-blue-900">{{ $dq->correct_answer_2 ?? '-' }}</span></td>
                                                <td class="px-4 py-3 text-[#6f6b67] text-[11px]">{{ $dq->explanation ?? '-' }}</td>
                                                <td class="px-4 py-3 text-right">
                                                    <form method="POST" action="{{ route('admin.disc.banks.stage-questions.destroy', [$bank, $dq]) }}" onsubmit="return confirm('Hapus butir soal deret ini?')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-rose-600 hover:underline">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-4 py-6 text-center text-xs text-[#6f6b67]">Belum ada soal deret hitung. Klik 'Salin Paket Standar' di atas atau tambah secara manual.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- TAB 2: ANALOGI VERBAL --}}
                        <div class="mt-8 flex flex-wrap items-center justify-between gap-3 pt-6 border-t border-[#322d2a]/10">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-600 text-xs font-bold text-white">2</span>
                                <h2 class="text-lg font-medium text-[#322d2a]">Sub-Tes 2: Analogi Verbal ({{ $bank->analogiQuestions->count() }} Soal)</h2>
                            </div>
                            <span class="rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-xs font-semibold text-emerald-800">
                                ⏱ Durasi Default: 10 Menit (Target: 50 Soal)
                            </span>
                        </div>

                        {{-- FORM TAMBAH SOAL ANALOGI --}}
                        <details class="group rounded-[20px] border border-emerald-200 bg-white p-4 shadow-xs">
                            <summary class="flex cursor-pointer items-center justify-between font-semibold text-xs text-emerald-700 hover:text-emerald-900">
                                <span>➕ Tambah Butir Soal Analogi Verbal Baru (Manual)</span>
                                <span class="transition group-open:rotate-180">▼</span>
                            </summary>
                            <form method="POST" action="{{ route('admin.disc.banks.stage-questions.store', $bank) }}" class="mt-4 space-y-3 pt-3 border-t border-emerald-100 text-xs">
                                @csrf
                                <input type="hidden" name="stage" value="STAGE_2_ANALOGI">
                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block font-medium text-[#322d2a] mb-1">Nomor Soal *</label>
                                        <input type="number" name="question_number" min="1" value="{{ $bank->analogiQuestions->count() + 1 }}" required
                                               class="w-full rounded-xl border border-[#322d2a]/20 px-3 py-2 text-xs focus:border-emerald-500 focus:outline-hidden">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label class="block font-medium text-[#322d2a] mb-1">Pertanyaan Analogi * (Contoh: Menghubungkan kata / analogi)</label>
                                        <input type="text" name="question_text" placeholder="Contoh: Gelap : Terang = Dingin : ..." required
                                               class="w-full rounded-xl border border-[#322d2a]/20 px-3 py-2 text-xs focus:border-emerald-500 focus:outline-hidden">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block font-medium text-[#322d2a] mb-1">Opsi A *</label>
                                        <input type="text" name="options[A]" placeholder="Pilihan A" required
                                               class="w-full rounded-xl border border-[#322d2a]/20 px-3 py-2 text-xs focus:border-emerald-500 focus:outline-hidden">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-[#322d2a] mb-1">Opsi B *</label>
                                        <input type="text" name="options[B]" placeholder="Pilihan B" required
                                               class="w-full rounded-xl border border-[#322d2a]/20 px-3 py-2 text-xs focus:border-emerald-500 focus:outline-hidden">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-[#322d2a] mb-1">Opsi C *</label>
                                        <input type="text" name="options[C]" placeholder="Pilihan C" required
                                               class="w-full rounded-xl border border-[#322d2a]/20 px-3 py-2 text-xs focus:border-emerald-500 focus:outline-hidden">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-[#322d2a] mb-1">Opsi D *</label>
                                        <input type="text" name="options[D]" placeholder="Pilihan D" required
                                               class="w-full rounded-xl border border-[#322d2a]/20 px-3 py-2 text-xs focus:border-emerald-500 focus:outline-hidden">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-medium text-[#322d2a] mb-1">Kunci Jawaban Benar *</label>
                                        <select name="correct_answer_1" required
                                                class="w-full rounded-xl border border-[#322d2a]/20 px-3 py-2 text-xs focus:border-emerald-500 focus:outline-hidden bg-white">
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-[#322d2a] mb-1">Penjelasan (Opsional)</label>
                                        <input type="text" name="explanation" placeholder="Hubungan antonim/lawan kata"
                                               class="w-full rounded-xl border border-[#322d2a]/20 px-3 py-2 text-xs focus:border-emerald-500 focus:outline-hidden">
                                    </div>
                                </div>
                                <div class="flex justify-end pt-1">
                                    <button type="submit" class="rounded-full bg-emerald-600 px-5 py-1.5 font-bold text-white shadow-xs hover:bg-emerald-700">
                                        Simpan Soal Analogi
                                    </button>
                                </div>
                            </form>
                        </details>

                        {{-- DAFTAR SOAL ANALOGI VERBAL --}}
                        <div class="overflow-hidden rounded-[24px] border border-white bg-white shadow-sm">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-[#322d2a]/10 text-xs">
                                    <thead class="bg-[#322d2a] text-left text-[11px] font-normal uppercase tracking-wider text-white">
                                        <tr>
                                            <th class="px-4 py-3">No</th>
                                            <th class="px-4 py-3">Pertanyaan Analogi</th>
                                            <th class="px-4 py-3">Pilihan Opsi (A, B, C, D)</th>
                                            <th class="px-4 py-3">Kunci Benar</th>
                                            <th class="px-4 py-3 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#322d2a]/10 bg-white">
                                        @forelse($bank->analogiQuestions as $aq)
                                            <tr class="transition hover:bg-emerald-50/40">
                                                <td class="px-4 py-3 font-bold text-[#322d2a]">{{ $aq->question_number }}</td>
                                                <td class="px-4 py-3 font-medium text-[#322d2a]">{{ $aq->question_text }}</td>
                                                <td class="px-4 py-3">
                                                    <div class="grid grid-cols-2 gap-1 text-[11px]">
                                                        @foreach($aq->options_json ?? [] as $k => $v)
                                                            <span class="{{ $aq->correct_answer_1 === $k ? 'font-bold text-emerald-700 bg-emerald-100 rounded px-1' : 'text-[#6f6b67]' }}">
                                                                <strong>{{ $k }}.</strong> {{ $v }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="rounded-full bg-emerald-600 px-2.5 py-0.5 font-bold text-white text-[11px]">{{ $aq->correct_answer_1 }}</span>
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    <form method="POST" action="{{ route('admin.disc.banks.stage-questions.destroy', [$bank, $aq]) }}" onsubmit="return confirm('Hapus butir soal analogi ini?')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-rose-600 hover:underline">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-6 text-center text-xs text-[#6f6b67]">Belum ada soal analogi verbal. Klik 'Salin Paket Standar' di atas atau tambah secara manual.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- TAB 3: TES KEPRIBADIAN (24 SOAL) --}}
                        <div class="mt-8 flex flex-wrap items-center justify-between gap-3 pt-6 border-t border-[#322d2a]/10">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-purple-600 text-xs font-bold text-white">3</span>
                                <h2 class="text-lg font-medium text-[#322d2a]">Sub-Tes 3: Tes Kepribadian ({{ $bank->questions->count() }} Soal Most/Least)</h2>
                            </div>
                            <span class="rounded-full bg-purple-50 border border-purple-200 px-3 py-1 text-xs font-semibold text-purple-800">
                                ✨ Tanpa Batas Waktu (Target: 24 Soal)
                            </span>
                        </div>

                        {{-- FORM TAMBAH SOAL KEPRIBADIAN --}}
                        <details class="group rounded-[20px] border border-purple-200 bg-white p-4 shadow-xs">
                            <summary class="flex cursor-pointer items-center justify-between font-semibold text-xs text-purple-700 hover:text-purple-900">
                                <span>➕ Tambah Butir Soal Kepribadian Baru (Manual)</span>
                                <span class="transition group-open:rotate-180">▼</span>
                            </summary>
                            <form method="POST" action="{{ route('admin.disc.banks.questions.store', $bank) }}" class="mt-4 space-y-3 pt-3 border-t border-purple-100 text-xs">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block font-medium text-[#322d2a] mb-1">Nomor Butir Soal * (1 - 50)</label>
                                        <input type="number" name="question_number" min="1" max="50" value="{{ $bank->questions->count() + 1 }}" required
                                               class="w-full rounded-xl border border-[#322d2a]/20 px-3 py-2 text-xs focus:border-purple-500 focus:outline-hidden">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block font-medium text-[#322d2a] mb-1">Instruksi Khusus (Opsional)</label>
                                        <input type="text" name="instruction" placeholder="Pilih 1 pernyataan yang Paling menggambarkan dan 1 yang Kurang menggambarkan"
                                               class="w-full rounded-xl border border-[#322d2a]/20 px-3 py-2 text-xs focus:border-purple-500 focus:outline-hidden">
                                    </div>
                                </div>

                                <div class="space-y-2.5">
                                    <p class="font-semibold text-[#322d2a]">4 Pilihan Pernyataan & Pemetaan DISC:</p>
                                    @for($i = 0; $i < 4; $i++)
                                        @php $labels = ['A', 'B', 'C', 'D']; @endphp
                                        <div class="grid grid-cols-12 gap-2 rounded-xl bg-purple-50/50 p-2.5 border border-purple-100 items-center">
                                            <div class="col-span-1 text-center font-bold text-purple-900">
                                                {{ $labels[$i] }}
                                                <input type="hidden" name="options[{{ $i }}][label]" value="{{ $labels[$i] }}">
                                            </div>
                                            <div class="col-span-7">
                                                <input type="text" name="options[{{ $i }}][statement]" placeholder="Pernyataan {{ $labels[$i] }}..." required
                                                       class="w-full rounded-lg border border-[#322d2a]/20 px-3 py-1.5 text-xs bg-white focus:border-purple-500 focus:outline-hidden">
                                            </div>
                                            <div class="col-span-2">
                                                <select name="options[{{ $i }}][most_mapping]" required class="w-full rounded-lg border border-[#322d2a]/20 px-2 py-1.5 text-xs bg-white">
                                                    <option value="" disabled selected>Most (M)</option>
                                                    <option value="D">D</option>
                                                    <option value="I">I</option>
                                                    <option value="S">S</option>
                                                    <option value="C">C</option>
                                                    <option value="*">*</option>
                                                </select>
                                            </div>
                                            <div class="col-span-2">
                                                <select name="options[{{ $i }}][least_mapping]" required class="w-full rounded-lg border border-[#322d2a]/20 px-2 py-1.5 text-xs bg-white">
                                                    <option value="" disabled selected>Least (L)</option>
                                                    <option value="D">D</option>
                                                    <option value="I">I</option>
                                                    <option value="S">S</option>
                                                    <option value="C">C</option>
                                                    <option value="*">*</option>
                                                </select>
                                            </div>
                                        </div>
                                    @endfor
                                </div>

                                <div class="flex justify-end pt-1">
                                    <button type="submit" class="rounded-full bg-purple-600 px-5 py-1.5 font-bold text-white shadow-xs hover:bg-purple-700">
                                        Simpan Soal Kepribadian
                                    </button>
                                </div>
                            </form>
                        </details>

                        {{-- DAFTAR SOAL KEPRIBADIAN --}}
                        <div class="grid gap-3 sm:grid-cols-2">
                            @forelse($bank->questions as $q)
                                <div class="rounded-[20px] border border-white bg-white p-4 shadow-sm">
                                    <div class="flex items-center justify-between border-b border-[#322d2a]/10 pb-2">
                                        <span class="font-bold text-[#322d2a]">Nomor {{ $q->question_number }}</span>
                                        <form method="POST" action="{{ route('admin.disc.banks.questions.destroy', [$bank, $q]) }}" onsubmit="return confirm('Hapus butir soal kepribadian ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-[11px] text-rose-600 hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                    <div class="mt-2.5 space-y-1.5 text-[11px]">
                                        @foreach($q->options as $opt)
                                            <div class="flex items-center justify-between rounded-lg bg-[#f0efe9] p-2">
                                                <span class="text-[#322d2a]">{{ $opt->statement }}</span>
                                                <div class="flex items-center gap-2 text-[10px] font-mono">
                                                    <span class="text-blue-700">M: {{ $opt->most_mapping }}</span>
                                                    <span class="text-rose-700">L: {{ $opt->least_mapping }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 rounded-[20px] border border-white bg-white p-6 text-center text-xs text-[#6f6b67]">
                                    Belum ada butir soal kepribadian. Klik 'Salin Paket Standar' di atas atau tambah secara manual.
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- MODAL KONFIRMASI HAPUS --}}
        <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-delete-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative w-full max-w-md transform overflow-hidden rounded-[28px] border border-white bg-white p-6 text-left shadow-2xl transition-all">
                    <div class="flex items-center gap-3.5">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 ring-1 ring-rose-200">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-[#322d2a]" id="modal-delete-title">Hapus Bank Soal</h3>
                            <p class="text-xs text-[#6f6b67]">Konfirmasi penghapusan data bank soal</p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl bg-rose-50/70 p-3.5 border border-rose-100 text-xs text-rose-900 leading-relaxed">
                        Apakah Anda yakin ingin menghapus bank soal <strong>"{{ $bank->name }}"</strong>?
                        <p class="mt-1 text-[11px] text-rose-700">Bank soal yang terhubung dengan jadwal asesmen tidak dapat dihapus demi keamanan data.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.disc.banks.destroy', $bank) }}" class="mt-5 flex items-center justify-end gap-2.5">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="closeDeleteModal()" class="rounded-full bg-[#f0efe9] px-4 py-2 text-xs font-medium text-[#6f6b67] hover:bg-[#e4e2dc] hover:text-[#322d2a] transition">
                            Batal
                        </button>
                        <button type="submit" class="rounded-full bg-rose-600 px-5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-rose-700 transition">
                            Ya, Hapus Bank Soal
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openDeleteModal() {
                document.getElementById('deleteModal').classList.remove('hidden');
            }
            function closeDeleteModal() {
                document.getElementById('deleteModal').classList.add('hidden');
            }
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeDeleteModal();
                }
            });
        </script>
    </section>
</x-layouts.app>
