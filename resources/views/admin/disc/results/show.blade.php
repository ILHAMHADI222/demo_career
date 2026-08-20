<x-layouts.app title="Hasil Analisis DISC - {{ $session->participant->name ?? 'Kandidat' }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <section class="min-h-screen bg-[#f0efe9] px-4 pb-16 pt-28 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">

            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('admin.disc.assessments.show', $session->disc_assessment_id) }}"
                   class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    ← Kembali ke Detail Asesmen
                </a>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.disc.sessions.export-csv', $session) }}"
                       class="rounded-full bg-[#322d2a] px-5 py-2 text-xs font-semibold text-white shadow-md transition hover:bg-black">
                        📥 Unduh Laporan (CSV)
                    </a>
                </div>
            </div>

            {{-- PARTICIPANT & PROFILE HERO CARD --}}
            <div class="relative overflow-hidden rounded-[48px] bg-[#322d2a] p-8 text-white shadow-xl shadow-slate-900/10 md:p-12">
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#f97316]/20 blur-3xl"></div>
                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/15 blur-3xl"></div>

                <div class="relative z-10 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-white/10 px-3.5 py-1 text-xs uppercase tracking-wider text-white backdrop-blur">
                                DISC Assessment Result
                            </span>
                            <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-xs text-emerald-300">
                                Selesai: {{ $session->completed_at ? $session->completed_at->format('d M Y, H:i') : '-' }}
                            </span>
                        </div>

                        <h1 class="mt-4 text-3xl font-light tracking-tight md:text-4xl">
                            {{ $session->participant->name ?? '-' }}
                        </h1>
                        <p class="mt-1 text-sm text-white/70">
                            {{ $session->participant->email ?? '-' }} • No. Kandidat: {{ $session->participant->candidateProfile->candidate_number ?? '-' }}
                        </p>
                        <p class="mt-2 text-xs text-white/50">
                            Asesmen: {{ $session->assessment->title }} ({{ $session->assessment->code }}) • Attempt ke-{{ $session->attempt_number }}
                        </p>
                    </div>

                    {{-- BIG PROFILE BADGE --}}
                    <div class="flex flex-col items-center rounded-3xl border border-white/15 bg-white/10 p-6 text-center backdrop-blur md:min-w-[240px]">
                        <span class="text-xs uppercase tracking-widest text-[#f97316]">Profil Kepribadian</span>
                        <span class="mt-1 font-mono text-5xl font-extrabold text-white">{{ $session->disc_profile_code }}</span>
                        <span class="mt-2 text-xs font-semibold text-amber-200">{{ $session->disc_profile_name }}</span>
                    </div>
                </div>
            </div>

            @php
                $raw = $session->raw_scores_json ?? [];
                $norm = $session->normalized_scores_json ?? [];
                $interp = $session->interpretation_json ?? [];
            @endphp

            {{-- DISC SCORE SUMMARY TABLE --}}
            <div class="mt-8 overflow-hidden rounded-[40px] border border-white/80 bg-white p-8 shadow-sm">
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-xl font-light text-[#322d2a]">
                        Tabel Skor Mentah & Skor Normalisasi DISC
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Graph 1 (Mask / Publik), Graph 2 (Core / Pribadi), Graph 3 (Perceived / Perubahan: Most - Least).
                    </p>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-center text-sm text-slate-700">
                        <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-600">
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
            <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">

                {{-- COMBINED DISC GRAPH --}}
                <div class="rounded-[40px] border border-white/80 bg-white p-8 shadow-sm lg:col-span-2">
                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h2 class="text-xl font-light text-[#322d2a]">
                                Grafik Gabungan <span class="font-normal text-[#f97316]">Combined DISC Graph</span>
                            </h2>
                            <p class="text-xs text-slate-500">
                                Perbandingan Graph 1 (Mask), Graph 2 (Core), dan Graph 3 (Perceived) pada satu visualisasi linier dengan baseline 0.
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 h-[340px] w-full">
                        <canvas id="combinedChart"></canvas>
                    </div>
                </div>

                {{-- GRAPH 1: MOST / MASK --}}
                <div class="rounded-[40px] border border-white/80 bg-white p-6 shadow-sm">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="text-base font-semibold text-[#f97316]">Graph 1: Mask / Public Behavior (Most)</h3>
                        <p class="text-xs text-slate-500">Respon perilaku yang ditampilkan di lingkungan sosial/kerja.</p>
                    </div>
                    <div class="mt-4 h-[240px] w-full">
                        <canvas id="graph1Chart"></canvas>
                    </div>
                </div>

                {{-- GRAPH 2: LEAST / CORE --}}
                <div class="rounded-[40px] border border-white/80 bg-white p-6 shadow-sm">
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
            <div class="mt-8 rounded-[40px] border border-white/80 bg-white p-8 shadow-sm">
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

            {{-- 24 QUESTIONS AUDIT REVIEW --}}
            <div class="mt-8 overflow-hidden rounded-[40px] border border-white/80 bg-white p-8 shadow-sm">
                <div class="border-b border-slate-100 pb-4">
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                        Audit Log Jawaban
                    </span>
                    <h2 class="mt-2 text-xl font-light text-[#322d2a]">
                        Tinjauan 24 Jawaban Peserta & Kunci Mapping
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Hanya dapat dilihat oleh HRD berwenang. Kunci pemetaan Most dan Least diverifikasi dari master snapshot sesi.
                    </p>
                </div>

                <div class="mt-6 space-y-4">
                    @foreach ($session->answers->sortBy('question.question_number') as $ans)
                        <div class="rounded-3xl border border-slate-100 bg-slate-50/60 p-5">
                            <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#322d2a] text-xs font-bold text-white">
                                    {{ $ans->question->question_number ?? $loop->iteration }}
                                </span>
                                <span class="text-xs text-slate-500">Soal No. {{ $ans->question->question_number ?? $loop->iteration }}</span>
                            </div>

                            <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                {{-- MOST --}}
                                <div class="rounded-2xl border border-blue-200 bg-blue-50/40 p-3.5">
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
                                <div class="rounded-2xl border border-rose-200 bg-rose-50/40 p-3.5">
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
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    {{-- CHART.JS INITIALIZATION --}}
    <script>
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
