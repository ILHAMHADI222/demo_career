<x-layouts.app title="Hasil Lengkap Psikotes - {{ $session->participant->name ?? 'Kandidat' }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <section class="min-h-screen bg-[#f0efe9] px-4 pb-16 pt-6 sm:pt-8 sm:px-6 lg:px-8 text-[#322d2a]">
        <div class="mx-auto max-w-[1240px]">

            {{-- BACK & ACTION BAR --}}
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('admin.disc.assessments.show', $session->disc_assessment_id) }}"
                   class="inline-flex items-center gap-2 rounded-full border border-[#322d2a]/15 bg-white px-4 py-2 text-xs font-semibold text-[#322d2a] shadow-sm transition hover:bg-[#f0efe9]">
                    ← Kembali ke Detail Asesmen
                </a>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.disc.sessions.export-csv', $session) }}"
                       class="inline-flex items-center gap-2 rounded-full bg-[#322d2a] px-5 py-2 text-xs font-semibold text-white shadow-md transition hover:bg-black">
                        📥 Unduh Laporan CSV
                    </a>
                </div>
            </div>

            {{-- PARTICIPANT & SUMMARY HERO CARD --}}
            <div class="relative overflow-hidden rounded-[36px] bg-[#322d2a] p-6 text-white shadow-xl shadow-slate-900/10 sm:p-10">
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#f97316]/20 blur-3xl"></div>
                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/15 blur-3xl"></div>

                <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-white/10 px-3.5 py-1 text-xs uppercase tracking-wider text-white backdrop-blur">
                                Hasil Psikotes & Evaluasi Sub-Tes
                            </span>
                            @if ($session->status === 'COMPLETED')
                                <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-300">
                                    ✓ Selesai Dikerjakan (3/3 Tahap)
                                </span>
                            @else
                                <span class="rounded-full bg-blue-500/20 px-3 py-1 text-xs font-semibold text-blue-200">
                                    ⚡ Real-time: Sedang di Tahap {{ $session->current_stage ?? 1 }} ({{ ($session->current_stage ?? 1) == 1 ? 'Deret Hitung' : (($session->current_stage ?? 1) == 2 ? 'Analogi Verbal' : 'Kepribadian') }})
                                </span>
                            @endif
                        </div>

                        <h1 class="mt-4 text-2xl font-light tracking-tight sm:text-4xl text-white">
                            {{ $session->participant->name ?? '-' }}
                        </h1>
                        <p class="mt-1.5 text-xs sm:text-sm text-white/75">
                            {{ $session->participant->email ?? '-' }} • No. Kandidat: <span class="font-mono text-amber-200">{{ $session->participant->candidateProfile->candidate_number ?? '-' }}</span>
                        </p>
                        <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-white/60">
                            <span>Asesmen: <strong class="text-white/90">{{ $session->assessment->title }}</strong></span>
                            <span>•</span>
                            <span>Kode: <strong class="font-mono text-white/90">{{ $session->assessment->code }}</strong></span>
                            <span>•</span>
                            <span>Waktu Selesai: <strong class="text-white/90">{{ $session->completed_at ? $session->completed_at->format('d M Y, H:i') : 'Belum Selesai (Proses Berjalan)' }}</strong></span>
                        </div>
                    </div>

                    {{-- BIG PROFILE BADGE --}}
                    <div class="flex flex-col items-center justify-center rounded-[28px] border border-white/15 bg-white/10 p-5 text-center backdrop-blur sm:min-w-[240px]">
                        <span class="text-[11px] font-bold uppercase tracking-widest text-[#f97316]">Profil Kepribadian</span>
                        <span class="mt-1 font-mono text-4xl sm:text-5xl font-black text-white">{{ $session->disc_profile_code ?: '-' }}</span>
                        <span class="mt-2 text-xs font-semibold text-amber-200">{{ $session->disc_profile_name ?: ($session->isCompleted() ? 'Hasil Psikotes' : 'Menunggu Tahap 3') }}</span>
                    </div>
                </div>
            </div>

            {{-- 3 SUBTEST SCORES OVERVIEW --}}
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {{-- SUBTEST 1 --}}
                <div class="rounded-[28px] border border-blue-200 bg-white p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="rounded-full bg-blue-50 border border-blue-200 px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider text-blue-700">Sub-Tes 1</span>
                        <span class="text-xs text-[#6f6b67]">25 Soal Deret</span>
                    </div>
                    <h3 class="mt-2 text-sm font-semibold text-[#322d2a]">Deret Hitung</h3>
                    <div class="mt-3 flex items-baseline justify-between">
                        <div>
                            <span class="text-3xl font-bold text-blue-600">{{ number_format($session->stage_1_score ?? 0, 1) }}</span>
                            <span class="text-xs text-blue-600 font-bold">%</span>
                        </div>
                        <span class="text-xs font-medium text-[#6f6b67]">Benar: <strong class="text-blue-700">{{ $session->stage_1_correct_count ?? 0 }} / 25</strong></span>
                    </div>
                </div>

                {{-- SUBTEST 2 --}}
                <div class="rounded-[28px] border border-emerald-200 bg-white p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="rounded-full bg-emerald-50 border border-emerald-200 px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-700">Sub-Tes 2</span>
                        <span class="text-xs text-[#6f6b67]">50 Soal Analogi</span>
                    </div>
                    <h3 class="mt-2 text-sm font-semibold text-[#322d2a]">Analogi Verbal</h3>
                    <div class="mt-3 flex items-baseline justify-between">
                        <div>
                            <span class="text-3xl font-bold text-emerald-600">{{ number_format($session->stage_2_score ?? 0, 1) }}</span>
                            <span class="text-xs text-emerald-600 font-bold">%</span>
                        </div>
                        <span class="text-xs font-medium text-[#6f6b67]">Benar: <strong class="text-emerald-700">{{ $session->stage_2_correct_count ?? 0 }} / 50</strong></span>
                    </div>
                </div>

                {{-- SUBTEST 3 --}}
                <div class="rounded-[28px] border border-purple-200 bg-white p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="rounded-full bg-purple-50 border border-purple-200 px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider text-purple-700">Sub-Tes 3</span>
                        <span class="text-xs text-[#6f6b67]">24 Soal Most/Least</span>
                    </div>
                    <h3 class="mt-2 text-sm font-semibold text-[#322d2a]">Tes Kepribadian</h3>
                    <div class="mt-3 flex items-baseline justify-between">
                        <div>
                            <span class="font-mono text-3xl font-black text-purple-700">{{ $session->disc_profile_code ?: '-' }}</span>
                            @if($session->disc_profile_name)
                                <span class="text-xs font-semibold text-purple-900 ml-1">{{ Str::limit($session->disc_profile_name, 16) }}</span>
                            @endif
                        </div>
                        <span class="text-xs font-medium text-[#6f6b67]">
                            @if ($session->isCompleted())
                                <strong class="text-purple-700">Selesai (24/24)</strong>
                            @elseif ($session->answers->count() > 0)
                                <strong class="text-purple-700">{{ $session->answers->count() }} / 24 Soal</strong>
                            @else
                                <span class="text-slate-400">{{ ($session->current_stage ?? 1) == 3 ? 'Sedang Mengerjakan' : 'Menunggu Tahap 3' }}</span>
                            @endif
                        </span>
                    </div>
                </div>

                {{-- SECURITY / INTEGRITY --}}
                <div class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="rounded-full bg-slate-100 border border-slate-200 px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider text-slate-700">Integritas</span>
                        <span class="text-xs text-[#6f6b67]">{{ $session->securityEvents->count() }} Event</span>
                    </div>
                    <h3 class="mt-2 text-sm font-semibold text-[#322d2a]">Skor Keamanan</h3>
                    <div class="mt-3 flex items-baseline justify-between">
                        <div>
                            <span class="text-3xl font-bold {{ ($session->security_score ?? 100) >= 80 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $session->security_score ?? 100 }}</span>
                            <span class="text-xs font-bold text-slate-500">/ 100</span>
                        </div>
                        <span class="text-xs font-semibold {{ ($session->security_score ?? 100) >= 80 ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ ($session->security_score ?? 100) >= 80 ? '✓ Valid / Terpercaya' : '⚠️ Terindikasi Pelanggaran' }}
                        </span>
                    </div>
                </div>
            </div>

            @php
                $raw = $session->raw_scores_json ?? [];
                $norm = $session->normalized_scores_json ?? [];
                $interp = $session->interpretation_json ?? [];
                $stage1Ans = $session->stage_1_answers_json ?? [];
                $stage2Ans = $session->stage_2_answers_json ?? [];
            @endphp

            {{-- TAB NAVIGATION --}}
            <div class="mt-8" id="result-tabs-container">
                <div class="flex flex-wrap gap-2 border-b border-[#322d2a]/10 pb-3">
                    <button onclick="switchTab('disc-tab')" id="btn-disc-tab"
                            class="tab-btn rounded-full bg-[#322d2a] px-5 py-2 text-xs font-semibold text-white shadow-xs transition">
                        📊 1. Profil & Grafik DISC
                    </button>
                    <button onclick="switchTab('stage1-tab')" id="btn-stage1-tab"
                            class="tab-btn rounded-full bg-white border border-[#322d2a]/15 px-5 py-2 text-xs font-semibold text-[#322d2a] shadow-xs transition hover:bg-[#f0efe9]">
                        🔢 2. Sub-Tes 1: Deret Hitung ({{ $deretQuestions->count() }} Soal)
                    </button>
                    <button onclick="switchTab('stage2-tab')" id="btn-stage2-tab"
                            class="tab-btn rounded-full bg-white border border-[#322d2a]/15 px-5 py-2 text-xs font-semibold text-[#322d2a] shadow-xs transition hover:bg-[#f0efe9]">
                        📖 3. Sub-Tes 2: Analogi Verbal ({{ $analogiQuestions->count() }} Soal)
                    </button>
                    <button onclick="switchTab('stage3-tab')" id="btn-stage3-tab"
                            class="tab-btn rounded-full bg-white border border-[#322d2a]/15 px-5 py-2 text-xs font-semibold text-[#322d2a] shadow-xs transition hover:bg-[#f0efe9]">
                        🎭 4. Sub-Tes 3: Audit Jawaban Kepribadian ({{ $session->answers->count() }} Soal)
                    </button>
                    <button onclick="switchTab('security-tab')" id="btn-security-tab"
                            class="tab-btn rounded-full bg-white border border-[#322d2a]/15 px-5 py-2 text-xs font-semibold text-[#322d2a] shadow-xs transition hover:bg-[#f0efe9]">
                        🛡️ 5. Log Integritas & Anti-Cheating ({{ $session->securityEvents->count() }})
                    </button>
                </div>

                {{-- TAB CONTENT 1: DISC CHARTS & INTERPRETATION --}}
                <div id="disc-tab" class="tab-pane mt-6 space-y-8">

                    {{-- DISC SCORE SUMMARY TABLE --}}
                    <div class="overflow-hidden rounded-[32px] border border-white bg-white p-6 sm:p-8 shadow-sm">
                        <div class="border-b border-slate-100 pb-4">
                            <h2 class="text-xl font-light text-[#322d2a]">
                                Tabel Skor Mentah & Normalisasi DISC
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">
                                Graph 1 (Mask / Respon Lingkungan), Graph 2 (Core / Respon Tekanan), Graph 3 (Perceived Mirror: Most - Least).
                            </p>
                        </div>

                        <div class="mt-6 overflow-x-auto">
                            <table class="w-full text-center text-xs sm:text-sm text-slate-700">
                                <thead class="bg-slate-50 text-[11px] sm:text-xs font-semibold uppercase tracking-wider text-slate-600">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Faktor / Dimensi</th>
                                        <th class="px-4 py-3 text-center">Graph 1 Raw (Most)</th>
                                        <th class="px-4 py-3 text-center">Graph 2 Raw (Least)</th>
                                        <th class="px-4 py-3 text-center">Graph 3 Raw (Change)</th>
                                        <th class="px-4 py-3 text-center text-[#f97316]">Norm. G1 (Mask)</th>
                                        <th class="px-4 py-3 text-center text-blue-600">Norm. G2 (Core)</th>
                                        <th class="px-4 py-3 text-center text-purple-600 font-bold">Norm. G3 (Mirror)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-mono">
                                    @foreach (['D' => 'Dominance (D)', 'I' => 'Influence (I)', 'S' => 'Steadiness (S)', 'C' => 'Conscientiousness (C)'] as $dim => $label)
                                        <tr class="transition hover:bg-slate-50/50">
                                            <td class="px-4 py-3 text-left font-sans font-bold text-[#322d2a]">{{ $label }}</td>
                                            <td class="px-4 py-3">{{ $raw['graph1_most'][$dim] ?? 0 }}</td>
                                            <td class="px-4 py-3">{{ $raw['graph2_least'][$dim] ?? 0 }}</td>
                                            <td class="px-4 py-3 font-semibold {{ ($raw['graph3_change'][$dim] ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ ($raw['graph3_change'][$dim] ?? 0) > 0 ? '+' : '' }}{{ $raw['graph3_change'][$dim] ?? 0 }}
                                            </td>
                                            <td class="px-4 py-3 font-bold text-[#f97316]">{{ number_format($norm['graph1'][$dim] ?? 0, 1) }}</td>
                                            <td class="px-4 py-3 font-bold text-blue-600">{{ number_format($norm['graph2'][$dim] ?? 0, 1) }}</td>
                                            <td class="px-4 py-3 font-extrabold text-purple-700 bg-purple-50/50">{{ number_format($norm['graph3'][$dim] ?? 0, 1) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-slate-50/70 font-sans text-xs text-slate-500">
                                        <td class="px-4 py-2 text-left font-semibold">* (Star / Neutral)</td>
                                        <td class="px-4 py-2 font-mono">{{ $raw['graph1_most']['*'] ?? 0 }}</td>
                                        <td class="px-4 py-2 font-mono">{{ $raw['graph2_least']['*'] ?? 0 }}</td>
                                        <td class="px-4 py-2 font-mono">-</td>
                                        <td class="px-4 py-2 font-mono">-</td>
                                        <td class="px-4 py-2 font-mono">-</td>
                                        <td class="px-4 py-2 font-mono">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- DISC GRAPHS SECTION (Chart.js) --}}
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                        {{-- COMBINED DISC GRAPH --}}
                        <div class="rounded-[32px] border border-white bg-white p-6 sm:p-8 shadow-sm lg:col-span-2">
                            <div class="border-b border-slate-100 pb-4">
                                <h2 class="text-xl font-light text-[#322d2a]">
                                    Grafik Gabungan <span class="font-normal text-[#f97316]">Combined DISC Graph</span>
                                </h2>
                                <p class="text-xs text-slate-500 mt-1">
                                    Perbandingan Graph 1 (Mask), Graph 2 (Core), dan Graph 3 (Perceived) pada satu visualisasi linier dengan baseline 0.
                                </p>
                            </div>
                            <div class="mt-6 h-[340px] w-full">
                                <canvas id="combinedChart"></canvas>
                            </div>
                        </div>

                        {{-- GRAPH 1: MOST / MASK --}}
                        <div class="rounded-[32px] border border-white bg-white p-6 shadow-sm">
                            <div class="border-b border-slate-100 pb-3">
                                <h3 class="text-base font-semibold text-[#f97316]">Graph 1: Mask / Public Behavior (Most)</h3>
                                <p class="text-xs text-slate-500">Respon perilaku yang ditampilkan di lingkungan sosial/kerja.</p>
                            </div>
                            <div class="mt-4 h-[240px] w-full">
                                <canvas id="graph1Chart"></canvas>
                            </div>
                        </div>

                        {{-- GRAPH 2: LEAST / CORE --}}
                        <div class="rounded-[32px] border border-white bg-white p-6 shadow-sm">
                            <div class="border-b border-slate-100 pb-3">
                                <h3 class="text-base font-semibold text-blue-600">Graph 2: Core / Under Pressure Behavior (Least)</h3>
                                <p class="text-xs text-slate-500">Karakter dasar alami saat menghadapi tekanan berat.</p>
                            </div>
                            <div class="mt-4 h-[240px] w-full">
                                <canvas id="graph2Chart"></canvas>
                            </div>
                        </div>

                    </div>

                    {{-- DETAILED PROFILE INTERPRETATION --}}
                    <div class="rounded-[32px] border border-white bg-white p-6 sm:p-8 shadow-sm">
                        <div class="border-b border-slate-100 pb-4">
                            <span class="rounded-full bg-orange-50 px-3.5 py-1 text-xs font-semibold text-[#f97316]">
                                Psychometric Diagnostic
                            </span>
                            <h2 class="mt-2 text-2xl font-light text-[#322d2a]">
                                Interpretasi Komprehensif Profil: <span class="font-normal text-[#f97316]">{{ $session->disc_profile_name }}</span>
                            </h2>
                        </div>

                        <div class="mt-6 space-y-6">
                            <div class="rounded-3xl bg-slate-50 p-6">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Ringkasan & Karakteristik Umum</h3>
                                <p class="mt-2 text-sm leading-relaxed text-slate-700">
                                    {{ $interp['description'] ?? 'Belum ada deskripsi profil.' }}
                                </p>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="rounded-3xl border border-emerald-100 bg-emerald-50/40 p-6">
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800">🌟 Kekuatan Utama (Key Strengths)</h3>
                                    <p class="mt-2 text-xs leading-relaxed text-slate-700">
                                        {{ $interp['strengths'] ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-3xl border border-rose-100 bg-rose-50/40 p-6">
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-rose-800">⚠️ Potensi Keterbatasan (Potential Limitations)</h3>
                                    <p class="mt-2 text-xs leading-relaxed text-slate-700">
                                        {{ $interp['potential_limitations'] ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-3xl border border-blue-100 bg-blue-50/40 p-6">
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-blue-800">💬 Gaya Komunikasi (Communication Style)</h3>
                                    <p class="mt-2 text-xs leading-relaxed text-slate-700">
                                        {{ $interp['communication_style'] ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-3xl border border-purple-100 bg-purple-50/40 p-6">
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-purple-800">💼 Gaya Bekerja & Manajemen Tugas</h3>
                                    <p class="mt-2 text-xs leading-relaxed text-slate-700">
                                        {{ $interp['work_style'] ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="rounded-3xl border border-amber-100 bg-amber-50/50 p-6">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-amber-800">🎯 Rekomendasi Area Pengembangan (Development Suggestions)</h3>
                                <p class="mt-2 text-xs leading-relaxed text-slate-700">
                                    {{ $interp['development_suggestions'] ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- TAB CONTENT 2: SUB-TES 1 (DERET HITUNG) --}}
                <div id="stage1-tab" class="tab-pane mt-6 hidden space-y-6">
                    <div class="rounded-[32px] border border-blue-200 bg-white p-6 sm:p-8 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
                            <div>
                                <span class="rounded-full bg-blue-50 border border-blue-200 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-blue-700">
                                    Evaluasi Sub-Tes 1: Deret Hitung
                                </span>
                                <h2 class="mt-2 text-2xl font-light text-[#322d2a]">
                                    Detail Hasil & Analisis Jawaban Deret Angka
                                </h2>
                                <p class="text-xs text-slate-500 mt-1">
                                    Menampilkan 25 butir pola angka, kunci jawaban resmi, jawaban yang diisi kandidat, dan pembahasan pola.
                                </p>
                            </div>
                            <div class="flex items-center gap-3 bg-blue-50/70 border border-blue-200 rounded-2xl p-3">
                                <div class="text-center px-3">
                                    <span class="block text-[10px] text-slate-500">Skor Sub-Tes 1</span>
                                    <span class="text-xl font-bold text-blue-700">{{ number_format($session->stage_1_score ?? 0, 1) }}%</span>
                                </div>
                                <div class="h-8 w-px bg-blue-200"></div>
                                <div class="text-center px-3">
                                    <span class="block text-[10px] text-slate-500">Benar / Total</span>
                                    <span class="text-xl font-bold text-blue-700">{{ $session->stage_1_correct_count ?? 0 }} / {{ $deretQuestions->count() }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- TABEL EVALUASI JAWABAN DERET --}}
                        <div class="mt-6 overflow-hidden rounded-[24px] border border-slate-100">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100 text-xs">
                                    <thead class="bg-[#322d2a] text-left text-[11px] font-normal uppercase tracking-wider text-white">
                                        <tr>
                                            <th class="px-4 py-3.5">No</th>
                                            <th class="px-4 py-3.5">Pola Barisan Deret</th>
                                            <th class="px-4 py-3.5">Kunci Jawaban</th>
                                            <th class="px-4 py-3.5">Jawaban Kandidat</th>
                                            <th class="px-4 py-3.5 text-center">Status</th>
                                            <th class="px-4 py-3.5">Pembahasan Pola</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @forelse ($deretQuestions as $dq)
                                            @php
                                                $candAns = $stage1Ans[$dq->id] ?? null;
                                                $uAns1 = is_array($candAns) ? trim((string)($candAns['ans_1'] ?? '')) : '';
                                                $uAns2 = is_array($candAns) ? trim((string)($candAns['ans_2'] ?? '')) : '';
                                                $cAns1 = trim((string)($dq->correct_answer_1 ?? ''));
                                                $cAns2 = trim((string)($dq->correct_answer_2 ?? ''));

                                                $isCorrect = (strtolower($uAns1) === strtolower($cAns1)) && (strtolower($uAns2) === strtolower($cAns2));
                                                $isFilled = $uAns1 !== '' || $uAns2 !== '';
                                            @endphp
                                            <tr class="transition hover:bg-slate-50/60 {{ $isCorrect ? 'bg-emerald-50/20' : '' }}">
                                                <td class="px-4 py-3.5 font-bold text-[#322d2a]">{{ $dq->question_number }}</td>
                                                <td class="px-4 py-3.5 font-mono font-bold text-[#322d2a]">{{ $dq->question_text }}</td>
                                                <td class="px-4 py-3.5 font-mono">
                                                    <span class="rounded bg-slate-100 px-2 py-0.5 font-bold text-slate-800">{{ $dq->correct_answer_1 }}</span>
                                                    @if(!empty($dq->correct_answer_2))
                                                        <span class="rounded bg-slate-100 px-2 py-0.5 font-bold text-slate-800 ml-1">{{ $dq->correct_answer_2 }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3.5 font-mono">
                                                    @if($isFilled)
                                                        <span class="rounded px-2 py-0.5 font-bold {{ $isCorrect ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                                            {{ $uAns1 ?: '-' }}
                                                        </span>
                                                        @if($uAns2 !== '' || $cAns2 !== '')
                                                            <span class="rounded px-2 py-0.5 font-bold ml-1 {{ $isCorrect ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                                                {{ $uAns2 ?: '-' }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="text-slate-400 italic">Tidak dijawab</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3.5 text-center">
                                                    @if($isCorrect)
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-bold text-emerald-800">
                                                            ✓ Benar
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-2.5 py-0.5 text-[11px] font-bold text-rose-800">
                                                            ✗ Salah
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3.5 text-[11px] text-slate-600">{{ $dq->explanation ?: '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-4 py-8 text-center text-xs text-slate-500">
                                                    Data butir soal deret hitung tidak ditemukan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB CONTENT 3: SUB-TES 2 (ANALOGI VERBAL) --}}
                <div id="stage2-tab" class="tab-pane mt-6 hidden space-y-6">
                    <div class="rounded-[32px] border border-emerald-200 bg-white p-6 sm:p-8 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
                            <div>
                                <span class="rounded-full bg-emerald-50 border border-emerald-200 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-emerald-700">
                                    Evaluasi Sub-Tes 2: Analogi Verbal
                                </span>
                                <h2 class="mt-2 text-2xl font-light text-[#322d2a]">
                                    Detail Hasil & Analisis Jawaban Analogi Verbal
                                </h2>
                                <p class="text-xs text-slate-500 mt-1">
                                    Menampilkan 50 butir pertanyaan analogi, pilihan A-D, kunci jawaban benar, dan jawaban kandidat.
                                </p>
                            </div>
                            <div class="flex items-center gap-3 bg-emerald-50/70 border border-emerald-200 rounded-2xl p-3">
                                <div class="text-center px-3">
                                    <span class="block text-[10px] text-slate-500">Skor Sub-Tes 2</span>
                                    <span class="text-xl font-bold text-emerald-700">{{ number_format($session->stage_2_score ?? 0, 1) }}%</span>
                                </div>
                                <div class="h-8 w-px bg-emerald-200"></div>
                                <div class="text-center px-3">
                                    <span class="block text-[10px] text-slate-500">Benar / Total</span>
                                    <span class="text-xl font-bold text-emerald-700">{{ $session->stage_2_correct_count ?? 0 }} / {{ $analogiQuestions->count() }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- TABEL EVALUASI JAWABAN ANALOGI --}}
                        <div class="mt-6 overflow-hidden rounded-[24px] border border-slate-100">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100 text-xs">
                                    <thead class="bg-[#322d2a] text-left text-[11px] font-normal uppercase tracking-wider text-white">
                                        <tr>
                                            <th class="px-4 py-3.5">No</th>
                                            <th class="px-4 py-3.5">Pertanyaan Analogi</th>
                                            <th class="px-4 py-3.5">Pilihan Opsi (A, B, C, D)</th>
                                            <th class="px-4 py-3.5 text-center">Kunci</th>
                                            <th class="px-4 py-3.5 text-center">Jawaban</th>
                                            <th class="px-4 py-3.5 text-center">Status</th>
                                            <th class="px-4 py-3.5">Pembahasan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @forelse ($analogiQuestions as $aq)
                                            @php
                                                $uAns = $stage2Ans[$aq->id] ?? null;
                                                $cAns = $aq->correct_answer_1;
                                                $isCorrect = ($uAns !== null && strtoupper((string)$uAns) === strtoupper((string)$cAns));
                                            @endphp
                                            <tr class="transition hover:bg-slate-50/60 {{ $isCorrect ? 'bg-emerald-50/20' : '' }}">
                                                <td class="px-4 py-3.5 font-bold text-[#322d2a]">{{ $aq->question_number }}</td>
                                                <td class="px-4 py-3.5 font-medium text-[#322d2a]">{{ $aq->question_text }}</td>
                                                <td class="px-4 py-3.5">
                                                    <div class="grid grid-cols-2 gap-1 text-[11px]">
                                                        @foreach($aq->options_json ?? [] as $k => $v)
                                                            <span class="{{ $aq->correct_answer_1 === $k ? 'font-bold text-emerald-700 bg-emerald-100 rounded px-1' : 'text-slate-600' }}">
                                                                <strong>{{ $k }}.</strong> {{ $v }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3.5 text-center">
                                                    <span class="rounded-full bg-slate-200 px-2.5 py-0.5 font-mono font-bold text-slate-800 text-[11px]">
                                                        {{ $aq->correct_answer_1 }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3.5 text-center">
                                                    @if($uAns)
                                                        <span class="rounded-full px-2.5 py-0.5 font-mono font-bold text-[11px] {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }}">
                                                            {{ $uAns }}
                                                        </span>
                                                    @else
                                                        <span class="text-slate-400 italic">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3.5 text-center">
                                                    @if($isCorrect)
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-bold text-emerald-800">
                                                            ✓ Benar
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-2.5 py-0.5 text-[11px] font-bold text-rose-800">
                                                            ✗ Salah
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3.5 text-[11px] text-slate-600">{{ $aq->explanation ?: '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-4 py-8 text-center text-xs text-slate-500">
                                                    Data butir soal analogi verbal tidak ditemukan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB CONTENT 4: SUB-TES 3 (AUDIT KEPRIBADIAN / DISC) --}}
                <div id="stage3-tab" class="tab-pane mt-6 hidden space-y-6">
                    <div class="rounded-[32px] border border-purple-200 bg-white p-6 sm:p-8 shadow-sm">
                        <div class="border-b border-slate-100 pb-4">
                            <span class="rounded-full bg-purple-50 border border-purple-200 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-purple-700">
                                Audit Log Sub-Tes 3: Tes Kepribadian
                            </span>
                            <h2 class="mt-2 text-2xl font-light text-[#322d2a]">
                                Tinjauan 24 Butir Jawaban Peserta & Kunci Mapping DISC
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">
                                Pilihan Most (Paling Menggambarkan) dan Least (Paling Tidak Menggambarkan) yang dikunci dari snapshot master sesi.
                            </p>
                        </div>

                        <div class="mt-6 space-y-4">
                            @forelse ($session->answers->sortBy('question.question_number') as $ans)
                                <div class="rounded-3xl border border-slate-100 bg-slate-50/60 p-5">
                                    <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#322d2a] text-xs font-bold text-white">
                                            {{ $ans->question->question_number ?? $loop->iteration }}
                                        </span>
                                        <span class="text-xs font-semibold text-slate-600">Butir Soal Nomor {{ $ans->question->question_number ?? $loop->iteration }}</span>
                                    </div>

                                    <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        {{-- MOST --}}
                                        <div class="rounded-2xl border border-blue-200 bg-blue-50/50 p-3.5">
                                            <div class="flex items-center justify-between">
                                                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-700">PALING (Most)</span>
                                                <span class="rounded bg-blue-600 px-2 py-0.5 font-mono text-xs font-bold text-white">
                                                    Kunci: {{ $ans->mostOption->most_mapping ?? '-' }}
                                                </span>
                                            </div>
                                            <p class="mt-2 text-xs font-medium text-slate-800">
                                                [{{ $ans->mostOption->option_label ?? '-' }}] {{ $ans->mostOption->statement ?? 'Tidak memilih' }}
                                            </p>
                                        </div>

                                        {{-- LEAST --}}
                                        <div class="rounded-2xl border border-rose-200 bg-rose-50/50 p-3.5">
                                            <div class="flex items-center justify-between">
                                                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-700">PALING TIDAK (Least)</span>
                                                <span class="rounded bg-rose-600 px-2 py-0.5 font-mono text-xs font-bold text-white">
                                                    Kunci: {{ $ans->leastOption->least_mapping ?? '-' }}
                                                </span>
                                            </div>
                                            <p class="mt-2 text-xs font-medium text-slate-800">
                                                [{{ $ans->leastOption->option_label ?? '-' }}] {{ $ans->leastOption->statement ?? 'Tidak memilih' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl bg-slate-50 p-6 text-center text-xs text-slate-500">
                                    Belum ada catatan jawaban kepribadian untuk sesi ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- TAB CONTENT 5: SECURITY / ANTI-CHEATING LOGS --}}
                <div id="security-tab" class="tab-pane mt-6 hidden space-y-6">
                    <div class="rounded-[32px] border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                        <div class="border-b border-slate-100 pb-4">
                            <span class="rounded-full bg-slate-100 border border-slate-200 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-slate-700">
                                Audit Integritas & Anti-Cheating
                            </span>
                            <h2 class="mt-2 text-2xl font-light text-[#322d2a]">
                                Riwayat Monitoring Keamanan Sesi Asesmen
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">
                                Informasi perangkat kandidat, alamat IP, dan log perpindahan jendela/layar selama proses asesmen berlangsung.
                            </p>
                        </div>

                        {{-- DEVICE & CONNECTION INFO --}}
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <span class="block text-[11px] text-slate-500">Alamat IP Peserta</span>
                                <span class="font-mono text-xs font-bold text-slate-800">{{ $session->ip_address ?? '127.0.0.1' }}</span>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <span class="block text-[11px] text-slate-500">Waktu Mulai - Selesai</span>
                                <span class="text-xs font-bold text-slate-800">
                                    {{ $session->started_at ? $session->started_at->format('H:i:s') : '-' }} s/d {{ $session->completed_at ? $session->completed_at->format('H:i:s') : '-' }}
                                </span>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <span class="block text-[11px] text-slate-500">Indeks Keamanan Akhir</span>
                                <span class="font-bold text-sm {{ ($session->security_score ?? 100) >= 80 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $session->security_score ?? 100 }} / 100
                                </span>
                            </div>
                        </div>

                        {{-- SECURITY EVENTS TIMELINE --}}
                        <div class="mt-6">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-3">Catatan Insiden & Event Keamanan:</h3>
                            <div class="space-y-3">
                                @forelse ($session->securityEvents as $event)
                                    <div class="flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5 text-xs">
                                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-100 font-bold text-amber-800">
                                            ⚠️
                                        </span>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <span class="font-bold text-slate-800">{{ $event->event_type ?? 'SECURITY_ALERT' }}</span>
                                                <span class="font-mono text-[11px] text-slate-500">{{ $event->created_at->format('H:i:s') }}</span>
                                            </div>
                                            <p class="mt-1 text-slate-600">{{ $event->description ?? 'Terdeteksi aktivitas berpindah tab atau keluar dari layar tes.' }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/50 p-5 text-center text-xs text-emerald-800">
                                        ✓ Tidak ada aktivitas mencurigakan yang terdeteksi selama pengerjaan tes. Integritas sesi 100%.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    {{-- TAB SWITCHER & CHART JS SCRIPT --}}
    <script>
        function switchTab(tabId) {
            // Hide all tab panes
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));
            // Remove active style from all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-[#322d2a]', 'text-white');
                btn.classList.add('bg-white', 'text-[#322d2a]', 'border', 'border-[#322d2a]/15');
            });

            // Show current tab pane
            const selectedPane = document.getElementById(tabId);
            if (selectedPane) selectedPane.classList.remove('hidden');

            // Highlight current button
            const activeBtn = document.getElementById('btn-' + tabId);
            if (activeBtn) {
                activeBtn.classList.remove('bg-white', 'text-[#322d2a]', 'border', 'border-[#322d2a]/15');
                activeBtn.classList.add('bg-[#322d2a]', 'text-white');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const labels = ['Dominance (D)', 'Influence (I)', 'Steadiness (S)', 'Conscientiousness (C)'];
            const g1Data = [
                {{ $norm['graph1']['D'] ?? 0 }},
                {{ $norm['graph1']['I'] ?? 0 }},
                {{ $norm['graph1']['S'] ?? 0 }},
                {{ $norm['graph1']['C'] ?? 0 }}
            ];
            const g2Data = [
                {{ $norm['graph2']['D'] ?? 0 }},
                {{ $norm['graph2']['I'] ?? 0 }},
                {{ $norm['graph2']['S'] ?? 0 }},
                {{ $norm['graph2']['C'] ?? 0 }}
            ];
            const g3Data = [
                {{ $norm['graph3']['D'] ?? 0 }},
                {{ $norm['graph3']['I'] ?? 0 }},
                {{ $norm['graph3']['S'] ?? 0 }},
                {{ $norm['graph3']['C'] ?? 0 }}
            ];

            const chartOptions = (minVal = -8, maxVal = 8) => ({
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        min: minVal,
                        max: maxVal,
                        grid: {
                            color: (context) => context.tick.value === 0 ? '#322d2a' : '#f1f5f9',
                            lineWidth: (context) => context.tick.value === 0 ? 2 : 1,
                        },
                        ticks: { stepSize: 2 }
                    },
                    x: {
                        grid: { color: '#f8fafc' }
                    }
                },
                plugins: {
                    legend: { position: 'top' }
                }
            });

            // 1. Combined Chart
            new Chart(document.getElementById('combinedChart'), {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Graph 1: Mask (Most)',
                            data: g1Data,
                            borderColor: '#f97316',
                            backgroundColor: 'rgba(249, 115, 22, 0.1)',
                            borderWidth: 3,
                            tension: 0.2,
                            pointRadius: 6,
                        },
                        {
                            label: 'Graph 2: Core (Least)',
                            data: g2Data,
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            borderWidth: 3,
                            tension: 0.2,
                            pointRadius: 6,
                        },
                        {
                            label: 'Graph 3: Mirror (Change)',
                            data: g3Data,
                            borderColor: '#9333ea',
                            backgroundColor: 'rgba(147, 51, 234, 0.15)',
                            borderWidth: 4,
                            borderDash: [5, 5],
                            tension: 0.2,
                            pointRadius: 7,
                        }
                    ]
                },
                options: chartOptions(-8, 8)
            });

            // 2. Graph 1
            new Chart(document.getElementById('graph1Chart'), {
                type: 'line',
                data: {
                    labels: ['D', 'I', 'S', 'C'],
                    datasets: [{
                        label: 'Graph 1 (Most / Mask)',
                        data: g1Data,
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(249, 115, 22, 0.2)',
                        borderWidth: 3,
                        pointRadius: 5,
                        tension: 0.2,
                    }]
                },
                options: chartOptions(-8, 8)
            });

            // 3. Graph 2
            new Chart(document.getElementById('graph2Chart'), {
                type: 'line',
                data: {
                    labels: ['D', 'I', 'S', 'C'],
                    datasets: [{
                        label: 'Graph 2 (Least / Core)',
                        data: g2Data,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.2)',
                        borderWidth: 3,
                        pointRadius: 5,
                        tension: 0.2,
                    }]
                },
                options: chartOptions(-8, 8)
            });
        });
    </script>
</x-layouts.app>
