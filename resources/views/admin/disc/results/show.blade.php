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
                    <a href="{{ route('admin.disc.sessions.export-excel', $session) }}"
                       class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-2 text-xs font-semibold text-white shadow-md shadow-emerald-600/20 transition hover:bg-emerald-700">
                        📊 Unduh Hasil DISC (.xlsx)
                    </a>
                    <button type="button" onclick="printOfficialSheet()"
                            class="inline-flex items-center gap-2 rounded-full border border-[#322d2a]/20 bg-white px-4 py-2 text-xs font-semibold text-[#322d2a] shadow-xs transition hover:bg-[#f0efe9]">
                        🖨️ Cetak / Simpan PDF
                    </button>
                    <a href="{{ route('admin.disc.sessions.export-csv', $session) }}"
                       class="inline-flex items-center gap-2 rounded-full bg-[#322d2a] px-4 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-black">
                        📥 Unduh CSV
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
                            <span>Waktu Selesai: <strong class="text-white/90">{{ $session->completed_at_wib ? $session->completed_at_wib->format('d M Y, H:i') . ' WIB' : 'Belum Selesai (Proses Berjalan)' }}</strong></span>
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

                {{-- TAB CONTENT 1: DISC CHARTS & INTERPRETATION (EXACT MASTER EXCEL FORMAT) --}}
                @php
                    $candidateUser = $session->participant?->user;
                    $candProfile = $session->participant?->candidateProfile;
                    $candName = $candidateUser?->name ?? ($session->candidate_name ?? 'Peserta');
                    $birthDate = $candProfile?->birth_date;
                    $ageText = $birthDate ? \Carbon\Carbon::parse($birthDate)->age . ' TAHUN' : '-';
                    $genderVal = $candProfile?->gender;
                    $genderText = match(strtoupper($genderVal ?? '')) {
                        'MALE', 'L', 'LAKI-LAKI' => 'LAKI-LAKI',
                        'FEMALE', 'P', 'PEREMPUAN' => 'PEREMPUAN',
                        default => $genderVal ?: '-',
                    };
                    $testDateText = $session->completed_at_wib ? $session->completed_at_wib->format('d-M-y') : ($session->started_at_wib ? $session->started_at_wib->format('d-M-y') : now()->setTimezone('Asia/Jakarta')->format('d-M-y'));

                    $charProfiles = $scoringResult['character_profiles'] ?? [];
                    $maskProfile = $charProfiles['mask'] ?? null;
                    $coreProfile = $charProfiles['core'] ?? null;
                    $mirrorProfile = $charProfiles['mirror'] ?? null;

                    if (empty($maskProfile) && !empty($norm['graph1'])) {
                        $mIdx = \App\Data\DiscReferenceData::matchPattern($norm['graph1']['D'] ?? 0, $norm['graph1']['I'] ?? 0, $norm['graph1']['S'] ?? 0, $norm['graph1']['C'] ?? 0);
                        $maskProfile = \App\Data\DiscReferenceData::getProfile($mIdx);
                    }
                    if (empty($coreProfile) && !empty($norm['graph2'])) {
                        $cIdx = \App\Data\DiscReferenceData::matchPattern($norm['graph2']['D'] ?? 0, $norm['graph2']['I'] ?? 0, $norm['graph2']['S'] ?? 0, $norm['graph2']['C'] ?? 0);
                        $coreProfile = \App\Data\DiscReferenceData::getProfile($cIdx);
                    }
                    if (empty($mirrorProfile) && !empty($norm['graph3'])) {
                        $mrIdx = \App\Data\DiscReferenceData::matchPattern($norm['graph3']['D'] ?? 0, $norm['graph3']['I'] ?? 0, $norm['graph3']['S'] ?? 0, $norm['graph3']['C'] ?? 0);
                        $mirrorProfile = \App\Data\DiscReferenceData::getProfile($mrIdx);
                    }
                @endphp

                <div id="disc-tab" class="tab-pane mt-6 space-y-6">

                    {{-- PRINTABLE OFFICIAL DISC SHEET CONTAINER --}}
                    <div id="official-disc-sheet" class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-10 shadow-sm print:p-0 print:border-none print:shadow-none font-sans text-slate-800">
                        
                        {{-- SHEET HEADER --}}
                        <div class="border-b-2 border-slate-800 pb-3 mb-6">
                            <h1 class="text-3xl font-extrabold tracking-widest text-slate-900 uppercase">D I S C</h1>
                            <p class="text-sm font-semibold tracking-wider text-slate-600 italic">Personality System Graph Page</p>
                        </div>

                        {{-- METADATA & SCORE TABLE ROW --}}
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start pb-6 border-b border-slate-200">
                            {{-- PARTICIPANT INFO (Cols 1-5) --}}
                            <div class="lg:col-span-5 bg-slate-50/80 rounded-2xl p-4 border border-slate-100 space-y-2 text-xs sm:text-sm">
                                <div class="flex items-baseline">
                                    <span class="w-24 font-bold text-slate-700">Name</span>
                                    <span class="w-4 font-bold text-slate-400">:</span>
                                    <span class="font-bold text-slate-900 uppercase">{{ $candName }}</span>
                                </div>
                                <div class="flex items-baseline">
                                    <span class="w-24 font-bold text-slate-700">Age</span>
                                    <span class="w-4 font-bold text-slate-400">:</span>
                                    <span class="font-semibold text-slate-800 uppercase">{{ $ageText }}</span>
                                </div>
                                <div class="flex items-baseline">
                                    <span class="w-24 font-bold text-slate-700">Gender</span>
                                    <span class="w-4 font-bold text-slate-400">:</span>
                                    <span class="font-semibold text-slate-800 uppercase">{{ $genderText }}</span>
                                </div>
                                <div class="flex items-baseline">
                                    <span class="w-24 font-bold text-slate-700">Tgl. Tes</span>
                                    <span class="w-4 font-bold text-slate-400">:</span>
                                    <span class="font-semibold text-slate-800">{{ $testDateText }}</span>
                                </div>
                            </div>

                            {{-- RAW SCORE MATRIX TABLE (Cols 6-12) --}}
                            <div class="lg:col-span-7 overflow-x-auto">
                                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                                    <span>Tabel Skor Mentah DISC (24 Soal)</span>
                                    <span class="text-[10px] font-normal text-slate-400">Total jawaban per baris = 24</span>
                                </div>
                                <table class="w-full border-collapse border border-slate-300 text-center text-xs font-mono">
                                    <thead>
                                        <tr class="bg-slate-100 text-slate-700 font-bold">
                                            <th class="border border-slate-300 px-3 py-1.5 font-sans">Line</th>
                                            <th class="border border-slate-300 px-3 py-1.5 font-sans">D</th>
                                            <th class="border border-slate-300 px-3 py-1.5 font-sans">I</th>
                                            <th class="border border-slate-300 px-3 py-1.5 font-sans">S</th>
                                            <th class="border border-slate-300 px-3 py-1.5 font-sans">C</th>
                                            <th class="border border-slate-300 px-3 py-1.5 font-sans">*</th>
                                            <th class="border border-slate-300 px-3 py-1.5 font-sans bg-rose-50 text-rose-900">tot</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-slate-800">
                                        <tr>
                                            <td class="border border-slate-300 px-2 py-1 font-bold bg-slate-50 text-left font-sans">1 (Most)</td>
                                            <td class="border border-slate-300 px-2 py-1 font-bold">{{ $raw['graph1_most']['D'] ?? 0 }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-bold">{{ $raw['graph1_most']['I'] ?? 0 }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-bold">{{ $raw['graph1_most']['S'] ?? 0 }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-bold">{{ $raw['graph1_most']['C'] ?? 0 }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-semibold text-slate-500">{{ $raw['graph1_most']['*'] ?? 0 }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-extrabold bg-[#ffc7ce] text-[#9c0006]">
                                                {{ array_sum($raw['graph1_most'] ?? []) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border border-slate-300 px-2 py-1 font-bold bg-slate-50 text-left font-sans">2 (Least)</td>
                                            <td class="border border-slate-300 px-2 py-1 font-bold">{{ $raw['graph2_least']['D'] ?? 0 }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-bold">{{ $raw['graph2_least']['I'] ?? 0 }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-bold">{{ $raw['graph2_least']['S'] ?? 0 }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-bold">{{ $raw['graph2_least']['C'] ?? 0 }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-semibold text-slate-500">{{ $raw['graph2_least']['*'] ?? 0 }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-extrabold bg-[#ffc7ce] text-[#9c0006]">
                                                {{ array_sum($raw['graph2_least'] ?? []) }}
                                            </td>
                                        </tr>
                                        <tr class="bg-slate-50/50">
                                            <td class="border border-slate-300 px-2 py-1 font-bold bg-slate-100 text-left font-sans">3 (Change)</td>
                                            <td class="border border-slate-300 px-2 py-1 font-bold text-blue-700">{{ $raw['graph3_change']['D'] ?? 0 }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-bold text-blue-700">{{ $raw['graph3_change']['I'] ?? 0 }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-bold text-blue-700">{{ $raw['graph3_change']['S'] ?? 0 }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-bold text-blue-700">{{ $raw['graph3_change']['C'] ?? 0 }}</td>
                                            <td class="border border-slate-300 px-2 py-1 text-slate-400">-</td>
                                            <td class="border border-slate-300 px-2 py-1 text-slate-400 font-sans">-</td>
                                        </tr>
                                        <tr class="bg-amber-50/60 font-sans text-[11px]">
                                            <td class="border border-slate-300 px-2 py-1 font-bold text-amber-900 text-left">Norm Skor</td>
                                            <td class="border border-slate-300 px-2 py-1 font-bold text-amber-900">{{ number_format($norm['graph1']['D'] ?? 0, 1) }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-bold text-amber-900">{{ number_format($norm['graph1']['I'] ?? 0, 1) }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-bold text-amber-900">{{ number_format($norm['graph1']['S'] ?? 0, 1) }}</td>
                                            <td class="border border-slate-300 px-2 py-1 font-bold text-amber-900">{{ number_format($norm['graph1']['C'] ?? 0, 1) }}</td>
                                            <td colspan="2" class="border border-slate-300 px-2 py-1 text-slate-500 italic">Skor Terstandarisasi G1..G3</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- 3 SEPARATE DISC GRAPHS (EXACT EXCEL MASTER LAYOUT) --}}
                        <div class="mt-8">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                
                                {{-- GRAPH 1: MOST (MASK PUBLIC SELF) --}}
                                <div class="rounded-2xl border border-slate-300 bg-white p-4 shadow-2xs">
                                    <div class="text-center pb-2 border-b border-slate-100">
                                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Graph 1 Most</h3>
                                        <p class="text-[11px] font-medium text-slate-500">Mask Public Self</p>
                                    </div>
                                    <div class="mt-2 h-[340px] w-full relative">
                                        <canvas id="graph1OfficialChart"></canvas>
                                    </div>
                                    <div class="mt-2 flex justify-between text-[11px] font-mono font-bold text-slate-600 px-3 bg-slate-50 rounded-lg py-1">
                                        <span>D: {{ number_format($norm['graph1']['D'] ?? 0, 1) }}</span>
                                        <span>I: {{ number_format($norm['graph1']['I'] ?? 0, 1) }}</span>
                                        <span>S: {{ number_format($norm['graph1']['S'] ?? 0, 1) }}</span>
                                        <span>C: {{ number_format($norm['graph1']['C'] ?? 0, 1) }}</span>
                                    </div>
                                </div>

                                {{-- GRAPH 2: LEAST (CORE PRIVATE SELF) --}}
                                <div class="rounded-2xl border border-slate-300 bg-white p-4 shadow-2xs">
                                    <div class="text-center pb-2 border-b border-slate-100">
                                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Graph 2 Least</h3>
                                        <p class="text-[11px] font-medium text-slate-500">Core Private Self</p>
                                    </div>
                                    <div class="mt-2 h-[340px] w-full relative">
                                        <canvas id="graph2OfficialChart"></canvas>
                                    </div>
                                    <div class="mt-2 flex justify-between text-[11px] font-mono font-bold text-slate-600 px-3 bg-slate-50 rounded-lg py-1">
                                        <span>D: {{ number_format($norm['graph2']['D'] ?? 0, 1) }}</span>
                                        <span>I: {{ number_format($norm['graph2']['I'] ?? 0, 1) }}</span>
                                        <span>S: {{ number_format($norm['graph2']['S'] ?? 0, 1) }}</span>
                                        <span>C: {{ number_format($norm['graph2']['C'] ?? 0, 1) }}</span>
                                    </div>
                                </div>

                                {{-- GRAPH 3: CHANGE (MIRROR PERCEIVED SELF) --}}
                                <div class="rounded-2xl border-2 border-blue-400 bg-white p-4 shadow-sm relative">
                                    <span class="absolute -top-2.5 right-4 bg-blue-600 text-white text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full">Primary Profile</span>
                                    <div class="text-center pb-2 border-b border-blue-100">
                                        <h3 class="text-xs font-bold text-blue-900 uppercase tracking-wider">Graph 3 Change</h3>
                                        <p class="text-[11px] font-medium text-blue-700">Mirror Perceived Self</p>
                                    </div>
                                    <div class="mt-2 h-[340px] w-full relative">
                                        <canvas id="graph3OfficialChart"></canvas>
                                    </div>
                                    <div class="mt-2 flex justify-between text-[11px] font-mono font-bold text-blue-900 px-3 bg-blue-50/60 rounded-lg py-1">
                                        <span>D: {{ number_format($norm['graph3']['D'] ?? 0, 1) }}</span>
                                        <span>I: {{ number_format($norm['graph3']['I'] ?? 0, 1) }}</span>
                                        <span>S: {{ number_format($norm['graph3']['S'] ?? 0, 1) }}</span>
                                        <span>C: {{ number_format($norm['graph3']['C'] ?? 0, 1) }}</span>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- GAMBARAN KARAKTER SECTION (3 COLUMNS LIKE EXCEL) --}}
                        <div class="mt-10 pt-6 border-t-2 border-slate-800">
                            <h2 class="text-lg font-extrabold uppercase tracking-wider text-slate-900 mb-5">
                                Gambaran Karakter
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                
                                {{-- 1. MASK PUBLIC SELF --}}
                                <div class="rounded-2xl border border-slate-200 bg-slate-50/40 p-4">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block mb-1">
                                        Mask Public Self
                                    </span>
                                    <div class="rounded-xl bg-slate-800 text-white font-extrabold px-3 py-2 text-center text-sm uppercase tracking-wide shadow-xs mb-3">
                                        {{ $maskProfile['name'] ?? 'LOGICAL THINKER' }}
                                    </div>
                                    <ul class="space-y-1.5 text-xs text-slate-700">
                                        @forelse(($maskProfile['traits'] ?? []) as $t)
                                            @if($t !== '0' && trim($t) !== '')
                                                <li class="flex items-start gap-1.5">
                                                    <span class="text-blue-500 font-bold shrink-0">•</span>
                                                    <span>{{ $t }}</span>
                                                </li>
                                            @endif
                                        @empty
                                            <li class="text-slate-400 italic text-[11px]">Karakteristk Mask tidak tersedia</li>
                                        @endforelse
                                    </ul>
                                </div>

                                {{-- 2. CORE PRIVATE SELF --}}
                                <div class="rounded-2xl border border-slate-200 bg-slate-50/40 p-4">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block mb-1">
                                        Core Private Self
                                    </span>
                                    <div class="rounded-xl bg-slate-800 text-white font-extrabold px-3 py-2 text-center text-sm uppercase tracking-wide shadow-xs mb-3">
                                        {{ $coreProfile['name'] ?? 'PRECISIONIST' }}
                                    </div>
                                    <ul class="space-y-1.5 text-xs text-slate-700">
                                        @forelse(($coreProfile['traits'] ?? []) as $t)
                                            @if($t !== '0' && trim($t) !== '')
                                                <li class="flex items-start gap-1.5">
                                                    <span class="text-blue-500 font-bold shrink-0">•</span>
                                                    <span>{{ $t }}</span>
                                                </li>
                                            @endif
                                        @empty
                                            <li class="text-slate-400 italic text-[11px]">Karakteristk Core tidak tersedia</li>
                                        @endforelse
                                    </ul>
                                </div>

                                {{-- 3. MIRROR PERCEIVED SELF --}}
                                <div class="rounded-2xl border border-blue-200 bg-blue-50/20 p-4">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-blue-700 block mb-1">
                                        Mirror Perceived Self (Utama)
                                    </span>
                                    <div class="rounded-xl bg-blue-700 text-white font-extrabold px-3 py-2 text-center text-sm uppercase tracking-wide shadow-xs mb-3">
                                        {{ $mirrorProfile['name'] ?? 'PERFECTIONIST' }}
                                    </div>
                                    <ul class="space-y-1.5 text-xs text-slate-800">
                                        @forelse(($mirrorProfile['traits'] ?? []) as $t)
                                            @if($t !== '0' && trim($t) !== '')
                                                <li class="flex items-start gap-1.5">
                                                    <span class="text-blue-700 font-bold shrink-0">•</span>
                                                    <span>{{ $t }}</span>
                                                </li>
                                            @endif
                                        @empty
                                            <li class="text-slate-400 italic text-[11px]">Karakteristk Mirror tidak tersedia</li>
                                        @endforelse
                                    </ul>
                                </div>

                            </div>
                        </div>

                        {{-- DESKRIPSI KEPRIBADIAN (PARAGRAPH LIKE EXCEL ROW 76-77) --}}
                        <div class="mt-8 pt-6 border-t border-slate-200">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800 mb-2 flex items-center gap-2">
                                <span>📄 Deskripsi Kepribadian</span>
                                <span class="text-[11px] font-normal text-slate-500">(Berdasarkan Pola Mirror: {{ $mirrorProfile['name'] ?? 'DISC' }})</span>
                            </h3>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-5 text-xs sm:text-sm leading-relaxed text-slate-700 text-justify">
                                {{ $mirrorProfile['description'] ?? ($interp['description'] ?? 'Belum ada uraian deskripsi kepribadian untuk tipe ini.') }}
                            </div>
                        </div>

                        {{-- REKOMENDASI AREA PROFESI / JABATAN (JOB MATCH) --}}
                        @if(!empty($mirrorProfile['jobs']) || !empty($interp['development_suggestions']))
                            <div class="mt-6 pt-5 border-t border-slate-100">
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800 mb-2">
                                    💼 Rekomendasi Area Profesi / Jabatan (Job Match)
                                </h3>
                                <div class="rounded-2xl border border-emerald-100 bg-emerald-50/40 p-4 text-xs sm:text-sm text-slate-800 leading-relaxed">
                                    {{ $mirrorProfile['jobs'] ?? ($interp['development_suggestions'] ?? '-') }}
                                </div>
                            </div>
                        @endif

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
                                    {{ $session->formatted_time_range_wib }}
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
                                                <span class="font-mono text-[11px] text-slate-500">{{ $event->occurred_at_wib ? $event->occurred_at_wib->format('H:i:s') . ' WIB' : '-' }}</span>
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

    {{-- PRINT STYLING & SCRIPT --}}
    <style>
        @media print {
            body {
                background: #ffffff !important;
                color: #0f172a !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            header, nav, aside, footer, #result-tabs-container > div:first-child, .no-print, [onclick*="switchTab"], a[href*="admin.disc"], .tab-btn {
                display: none !important;
            }
            .min-h-screen {
                min-height: auto !important;
                padding: 0 !important;
                background: transparent !important;
            }
            #disc-tab {
                display: block !important;
                margin-top: 0 !important;
            }
            #official-disc-sheet {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }
            #official-disc-sheet > div {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
    </style>

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

        function printOfficialSheet() {
            switchTab('disc-tab');
            setTimeout(() => {
                window.print();
            }, 180);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const labels = ['D', 'I', 'S', 'C'];
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

            const officialChartOptions = (chartTitle) => ({
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 600 },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ` Skor: ${ctx.parsed.y}`
                        }
                    }
                },
                scales: {
                    y: {
                        min: -8,
                        max: 8,
                        grid: {
                            color: (context) => (context.tick && context.tick.value === 0) ? '#0f172a' : '#e2e8f0',
                            lineWidth: (context) => (context.tick && context.tick.value === 0) ? 2 : 1,
                        },
                        ticks: {
                            stepSize: 2,
                            font: { family: 'ui-monospace, monospace', size: 11, weight: 'bold' },
                            color: '#475569',
                        }
                    },
                    x: {
                        grid: {
                            color: '#f1f5f9',
                            lineWidth: 1,
                        },
                        ticks: {
                            font: { family: 'sans-serif', size: 13, weight: 'bold' },
                            color: '#0f172a',
                        }
                    }
                }
            });

            // 1. Graph 1 Most (Mask)
            const g1El = document.getElementById('graph1OfficialChart');
            if (g1El) {
                new Chart(g1El, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            data: g1Data,
                            borderColor: '#2e5b88',
                            backgroundColor: '#2e5b88',
                            borderWidth: 2.5,
                            tension: 0,
                            pointRadius: 5.5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: '#2e5b88',
                            fill: false,
                        }]
                    },
                    options: officialChartOptions('Graph 1 Most (Mask Public Self)')
                });
            }

            // 2. Graph 2 Least (Core)
            const g2El = document.getElementById('graph2OfficialChart');
            if (g2El) {
                new Chart(g2El, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            data: g2Data,
                            borderColor: '#2e5b88',
                            backgroundColor: '#2e5b88',
                            borderWidth: 2.5,
                            tension: 0,
                            pointRadius: 5.5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: '#2e5b88',
                            fill: false,
                        }]
                    },
                    options: officialChartOptions('Graph 2 Least (Core Private Self)')
                });
            }

            // 3. Graph 3 Change (Mirror)
            const g3El = document.getElementById('graph3OfficialChart');
            if (g3El) {
                new Chart(g3El, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            data: g3Data,
                            borderColor: '#1d4ed8',
                            backgroundColor: '#1d4ed8',
                            borderWidth: 3,
                            tension: 0,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointBackgroundColor: '#1d4ed8',
                            fill: false,
                        }]
                    },
                    options: officialChartOptions('Graph 3 Change (Mirror Perceived Self)')
                });
            }
        });
    </script>
</x-layouts.app>
