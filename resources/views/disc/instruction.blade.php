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
                @php
                    $candidateUser = auth()->user();
                @endphp
                <div class="mt-6 grid gap-3 sm:grid-cols-3 text-xs">
                    <div class="rounded-[20px] border {{ $candidateUser->canTakeStage(1) ? 'border-blue-200 bg-blue-50/60' : 'border-slate-200 bg-slate-50 opacity-60' }} p-4">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex rounded-full {{ $candidateUser->canTakeStage(1) ? 'bg-blue-600' : 'bg-slate-500' }} px-2.5 py-0.5 text-[10px] font-bold text-white uppercase">Tahap 1</span>
                            @if ($candidateUser->canTakeStage(1))
                                <span class="text-[10px] font-semibold text-blue-700">Wajib ✓</span>
                            @else
                                <span class="text-[10px] font-medium text-slate-500">Dilewati</span>
                            @endif
                        </div>
                        <h4 class="mt-2 font-bold {{ $candidateUser->canTakeStage(1) ? 'text-blue-950' : 'text-slate-700' }}">Deret Hitung</h4>
                        <p class="mt-1 text-[11px] {{ $candidateUser->canTakeStage(1) ? 'text-blue-900/80' : 'text-slate-500' }}">25 Soal Deret Angka</p>
                        <p class="mt-2 text-[11px] font-semibold {{ $candidateUser->canTakeStage(1) ? 'text-blue-700' : 'text-slate-500' }}">⏱ Waktu: {{ $assessment->duration_stage_1 ?: 10 }} Menit</p>
                    </div>

                    <div class="rounded-[20px] border {{ $candidateUser->canTakeStage(2) ? 'border-emerald-200 bg-emerald-50/60' : 'border-slate-200 bg-slate-50 opacity-60' }} p-4">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex rounded-full {{ $candidateUser->canTakeStage(2) ? 'bg-emerald-600' : 'bg-slate-500' }} px-2.5 py-0.5 text-[10px] font-bold text-white uppercase">Tahap 2</span>
                            @if ($candidateUser->canTakeStage(2))
                                <span class="text-[10px] font-semibold text-emerald-700">Wajib ✓</span>
                            @else
                                <span class="text-[10px] font-medium text-slate-500">Dilewati</span>
                            @endif
                        </div>
                        <h4 class="mt-2 font-bold {{ $candidateUser->canTakeStage(2) ? 'text-emerald-950' : 'text-slate-700' }}">Analogi Verbal</h4>
                        <p class="mt-1 text-[11px] {{ $candidateUser->canTakeStage(2) ? 'text-emerald-900/80' : 'text-slate-500' }}">50 Soal Pilihan Ganda</p>
                        <p class="mt-2 text-[11px] font-semibold {{ $candidateUser->canTakeStage(2) ? 'text-emerald-700' : 'text-slate-500' }}">⏱ Waktu: {{ $assessment->duration_stage_2 ?: 10 }} Menit</p>
                    </div>

                    <div class="rounded-[20px] border {{ $candidateUser->canTakeStage(3) ? 'border-purple-200 bg-purple-50/60' : 'border-slate-200 bg-slate-50 opacity-60' }} p-4">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex rounded-full {{ $candidateUser->canTakeStage(3) ? 'bg-purple-600' : 'bg-slate-500' }} px-2.5 py-0.5 text-[10px] font-bold text-white uppercase">Tahap 3</span>
                            @if ($candidateUser->canTakeStage(3))
                                <span class="text-[10px] font-semibold text-purple-700">Wajib ✓</span>
                            @else
                                <span class="text-[10px] font-medium text-slate-500">Dilewati</span>
                            @endif
                        </div>
                        <h4 class="mt-2 font-bold {{ $candidateUser->canTakeStage(3) ? 'text-purple-950' : 'text-slate-700' }}">Tes Kepribadian</h4>
                        <p class="mt-1 text-[11px] {{ $candidateUser->canTakeStage(3) ? 'text-purple-900/80' : 'text-slate-500' }}">24 Soal Most & Least</p>
                        <p class="mt-2 text-[11px] font-semibold {{ $candidateUser->canTakeStage(3) ? 'text-purple-700' : 'text-slate-500' }}">✨ Tanpa Batas Waktu</p>
                    </div>
                </div>

                {{-- INSTRUCTION DETAILS --}}
                <div class="mt-6 space-y-4 text-xs text-[#322d2a]">
                    <div class="rounded-[22px] bg-[#f0efe9] p-5">
                        <h3 class="font-bold text-sm text-[#322d2a]">Tata Cara Pengerjaan:</h3>
                        <ol class="mt-2.5 list-decimal space-y-2 pl-4 leading-relaxed text-[#6f6b67]">
                            <li><strong>Batas Waktu Akses:</strong> Tes psikotes ini hanya dapat diakses dalam kurun waktu <strong>24 jam</strong> sejak undangan dikirimkan.</li>
                            <li><strong>Alur Berurutan:</strong> Anda wajib mengerjakan Tahap 1 (Deret Hitung) sampai selesai, kemudian sistem akan otomatis melanjutkan ke Tahap 2 (Analogi Verbal), dan terakhir ke Tahap 3 (Tes Kepribadian).</li>
                            <li><strong>Tahap 1 (Deret Hitung):</strong> Ketikkan 2 angka kelanjutan dari pola bilangan yang diberikan. Terdapat timer mundur selama {{ $assessment->duration_stage_1 ?: 10 }} menit.</li>
                            <li><strong>Tahap 2 (Analogi Verbal):</strong> Pilih 1 dari 4 pilihan kata yang memiliki korelasi makna yang paling sesuai. Terdapat timer mundur selama {{ $assessment->duration_stage_2 ?: 10 }} menit.</li>
                            <li><strong>Tahap 3 (Tes Kepribadian):</strong> Pilih 1 pernyataan yang <em>Paling Menggambarkan</em> diri Anda (Most) dan 1 pernyataan yang <em>Paling Tidak Menggambarkan</em> diri Anda (Least). <strong>Tidak ada batas waktu</strong> pada tahap ini.</li>
                        </ol>
                    </div>

                    @if ($isExpired)
                        <div class="rounded-[22px] border border-rose-200 bg-rose-50/80 p-4">
                            <h3 class="font-bold text-rose-900 flex items-center gap-1.5">
                                <span>⛔</span>
                                <span>Masa Akses Undangan Telah Berakhir</span>
                            </h3>
                            <p class="mt-1 text-xs text-rose-700 leading-relaxed">
                                Batas waktu akses pengerjaan tes ini adalah <strong>24 jam</strong> sejak undangan dikirimkan. Waktu akses Anda telah berakhir pada <strong>{{ $expiresAt ? $expiresAt->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB' : '24 jam setelah undangan' }}</strong>. Apabila Anda mengalami kendala atau memerlukan perpanjangan waktu, silakan menghubungi tim HRD PT Indodata Solusi Persada.
                            </p>
                        </div>
                    @endif

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
                    @elseif ($isExpired)
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-rose-100 border border-rose-200 px-4 py-2 text-xs font-semibold text-rose-700">
                                ⛔ Masa Akses 24 Jam Telah Berakhir
                            </span>
                        </div>
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
