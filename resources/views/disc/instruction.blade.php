<x-layouts.app title="Petunjuk Tes Psikotes - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">

            <div class="overflow-hidden rounded-[32px] sm:rounded-[36px] border border-white bg-white p-6 sm:p-8 shadow-sm">
                <div class="border-b border-[#322d2a]/10 pb-5 text-center">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-orange-50 px-3.5 py-1 text-xs font-semibold uppercase tracking-wider text-[#f97316]">
                        Psychometric Evaluation
                    </span>
                    <h1 class="mt-3 text-2xl sm:text-3xl font-light text-[#322d2a]">
                        {{ $assessment->title }}
                    </h1>
                    <p class="mt-2 text-xs sm:text-sm text-[#6f6b67]">
                        Tes Psikotes Terpadu PT Indodata Solusi Persada • <strong>3 Sub-Tes Wajib Berurutan</strong>
                    </p>
                </div>

                {{-- 3 STAGES OVERVIEW --}}
                <div class="mt-6 grid gap-3 sm:grid-cols-3 text-xs">
                    <div class="rounded-[20px] border border-blue-200 bg-blue-50/60 p-4">
                        <span class="inline-flex rounded-full bg-blue-600 px-2.5 py-0.5 text-[10px] font-bold text-white uppercase">Tahap 1</span>
                        <h4 class="mt-2 font-bold text-blue-950">Deret Hitung</h4>
                        <p class="mt-1 text-[11px] text-blue-900/80">25 Soal Deret Angka</p>
                        <p class="mt-2 text-[11px] font-semibold text-blue-700">⏱ Waktu: {{ $assessment->duration_stage_1 ?: 10 }} Menit</p>
                    </div>

                    <div class="rounded-[20px] border border-emerald-200 bg-emerald-50/60 p-4">
                        <span class="inline-flex rounded-full bg-emerald-600 px-2.5 py-0.5 text-[10px] font-bold text-white uppercase">Tahap 2</span>
                        <h4 class="mt-2 font-bold text-emerald-950">Analogi Verbal</h4>
                        <p class="mt-1 text-[11px] text-emerald-900/80">50 Soal Pilihan Ganda</p>
                        <p class="mt-2 text-[11px] font-semibold text-emerald-700">⏱ Waktu: {{ $assessment->duration_stage_2 ?: 10 }} Menit</p>
                    </div>

                    <div class="rounded-[20px] border border-purple-200 bg-purple-50/60 p-4">
                        <span class="inline-flex rounded-full bg-purple-600 px-2.5 py-0.5 text-[10px] font-bold text-white uppercase">Tahap 3</span>
                        <h4 class="mt-2 font-bold text-purple-950">Tes Kepribadian</h4>
                        <p class="mt-1 text-[11px] text-purple-900/80">24 Soal Most & Least</p>
                        <p class="mt-2 text-[11px] font-semibold text-purple-700">✨ Tanpa Batas Waktu</p>
                    </div>
                </div>

                {{-- INSTRUCTION DETAILS --}}
                <div class="mt-6 space-y-4 text-xs text-[#322d2a]">
                    <div class="rounded-[22px] bg-[#f0efe9] p-5">
                        <h3 class="font-bold text-sm text-[#322d2a]">Tata Cara Pengerjaan:</h3>
                        <ol class="mt-2.5 list-decimal space-y-2 pl-4 leading-relaxed text-[#6f6b67]">
                            <li><strong>Alur Berurutan:</strong> Anda wajib mengerjakan Tahap 1 (Deret Hitung) sampai selesai, kemudian sistem akan otomatis melanjutkan ke Tahap 2 (Analogi Verbal), dan terakhir ke Tahap 3 (Tes Kepribadian).</li>
                            <li><strong>Tahap 1 (Deret Hitung):</strong> Ketikkan 2 angka kelanjutan dari pola bilangan yang diberikan. Terdapat timer mundur selama {{ $assessment->duration_stage_1 ?: 10 }} menit.</li>
                            <li><strong>Tahap 2 (Analogi Verbal):</strong> Pilih 1 dari 4 pilihan kata yang memiliki korelasi makna yang paling sesuai. Terdapat timer mundur selama {{ $assessment->duration_stage_2 ?: 10 }} menit.</li>
                            <li><strong>Tahap 3 (Tes Kepribadian):</strong> Pilih 1 pernyataan yang <em>Paling Menggambarkan</em> diri Anda (Most) dan 1 pernyataan yang <em>Paling Tidak Menggambarkan</em> diri Anda (Least). <strong>Tidak ada batas waktu</strong> pada tahap ini.</li>
                        </ol>
                    </div>

                    <div class="rounded-[22px] border border-amber-200 bg-amber-50/60 p-4">
                        <h3 class="font-bold text-amber-900">Perhatian & Ketertiban:</h3>
                        <ul class="mt-1.5 list-disc space-y-1 pl-4 text-amber-800 text-[11px]">
                            <li>Pastikan koneksi internet stabil. Tes dilengkapi sistem keamanan otomatis.</li>
                            <li>Bila waktu pada Tahap 1 atau Tahap 2 habis, jawaban yang telah diisi akan tersimpan otomatis dan sistem berpindah ke tahap berikutnya.</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-6 border-t border-[#322d2a]/10 pt-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <a href="{{ route('dashboard') }}" class="text-xs font-medium text-[#6f6b67] hover:text-[#322d2a]">
                        ← Kembali ke Dashboard
                    </a>

                    @if ($canStartNew)
                        <form method="POST" action="{{ route('disc.assessment.start', $assessment) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-full bg-[#f97316] px-6 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-[#ea580c]">
                                <span>{{ $completedAttempts > 0 ? 'Mulai Percobaan ke-' . ($completedAttempts + 1) : 'Mulai Tes Psikotes Sekarang' }}</span>
                                <span>→</span>
                            </button>
                        </form>
                    @else
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-[#f0efe9] px-4 py-2 text-xs font-medium text-[#6f6b67]">
                                Tes Selesai ({{ $completedAttempts }}/{{ $maxAttempts }})
                            </span>
                            @if ($latestSession)
                                <a href="{{ route('disc.assessment.completed', $latestSession->session_token) }}"
                                   class="rounded-full bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-emerald-700">
                                    Lihat Hasil Selesai ✓
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </section>
</x-layouts.app>
