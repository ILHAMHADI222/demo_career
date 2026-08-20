<x-layouts.app title="Petunjuk Asesmen DISC - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-16 pt-28 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">

            <div class="overflow-hidden rounded-[48px] border border-white/80 bg-white p-8 shadow-xl shadow-slate-900/5 md:p-12">
                <div class="border-b border-slate-100 pb-6 text-center">
                    <span class="inline-flex items-center gap-2 rounded-full bg-orange-50 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-[#f97316]">
                        Psychometric Evaluation
                    </span>
                    <h1 class="mt-4 text-3xl font-light text-[#322d2a] md:text-4xl">
                        {{ $assessment->title }}
                    </h1>
                    <p class="mt-2 text-sm text-slate-500">
                        Durasi Waktu: <strong class="text-slate-800">{{ $assessment->duration_minutes }} Menit</strong> • Total: <strong class="text-slate-800">24 Butir Pertanyaan</strong>
                    </p>
                </div>

                {{-- INSTRUCTION CARD --}}
                <div class="mt-8 space-y-6 text-sm text-slate-700">
                    <div class="rounded-3xl bg-slate-50 p-6">
                        <h3 class="font-bold text-[#322d2a]">Tata Cara Menjawab:</h3>
                        <ol class="mt-3 list-decimal space-y-2 pl-5 text-xs leading-relaxed text-slate-600">
                            <li>Terdapat <strong>24 butir soal</strong>, di mana setiap soal memuat <strong>4 pilihan pernyataan</strong>.</li>
                            <li>Pada setiap nomor, Anda wajib memilih:
                                <ul class="mt-1 list-disc pl-4 space-y-1">
                                    <li><strong>Satu (1) pilihan PALING</strong> menggambarkan diri Anda dalam bekerja (Most).</li>
                                    <li><strong>Satu (1) pilihan PALING TIDAK</strong> menggambarkan diri Anda (Least).</li>
                                </ul>
                            </li>
                            <li>Anda <strong>tidak boleh memilih pernyataan yang sama</strong> untuk kolom Paling dan Paling Tidak.</li>
                            <li>Jawablah secara jujur dan spontan sesuai kepribadian alami Anda. Tidak ada jawaban benar maupun salah.</li>
                        </ol>
                    </div>

                    <div class="rounded-3xl border border-amber-200 bg-amber-50/60 p-6">
                        <h3 class="font-bold text-amber-900">Perhatian & Keamanan Asesmen:</h3>
                        <ul class="mt-2 list-disc space-y-1.5 pl-5 text-xs text-amber-800">
                            <li>Asesmen dilengkapi dengan <strong>pemantau keamanan (proctoring)</strong>, deteksi perpindahan tab browser, dan watermark identitas digital.</li>
                            <li>Pastikan koneksi internet stabil. Jawaban Anda tersimpan otomatis (autosave) setiap kali memilih.</li>
                            <li>Setelah menekan tombol submit atau waktu habis, jawaban Anda akan langsung dikunci dan dinilai oleh sistem secara otomatis.</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 border-t border-slate-100 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                        ← Kembali ke Dashboard
                    </a>

                    @if ($canStartNew)
                        <form method="POST" action="{{ route('disc.assessment.start', $assessment) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-full bg-[#f97316] px-8 py-4 text-sm font-semibold text-white shadow-xl shadow-[#f97316]/30 transition hover:-translate-y-0.5 hover:bg-[#ea580c]">
                                <span>{{ $completedAttempts > 0 ? 'Mulai Percobaan ke-' . ($completedAttempts + 1) . ' (Kesempatan: ' . ($completedAttempts) . '/' . $maxAttempts . ')' : 'Mulai Asesmen DISC Sekarang (10 Menit)' }}</span>
                                <span>→</span>
                            </button>
                        </form>
                    @else
                        <div class="flex items-center gap-3">
                            <span class="rounded-full bg-slate-100 px-5 py-3 text-xs font-semibold text-slate-600">
                                Batas Kesempatan Tercapai ({{ $completedAttempts }}/{{ $maxAttempts }} Selesai)
                            </span>
                            @if ($latestSession)
                                <a href="{{ route('disc.assessment.completed', $latestSession->session_token) }}"
                                   class="rounded-full bg-emerald-600 px-5 py-3 text-xs font-semibold text-white shadow-md hover:bg-emerald-700">
                                    Lihat Konfirmasi Selesai ✓
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </section>
</x-layouts.app>
