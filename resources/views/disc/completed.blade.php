<x-layouts.app title="Psikotes Selesai - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">

            <div class="overflow-hidden rounded-[32px] sm:rounded-[36px] border border-white bg-white p-6 sm:p-8 shadow-sm text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-3xl text-emerald-600 shadow-sm">
                    ✓
                </div>

                <span class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3.5 py-1 text-xs font-semibold uppercase tracking-wider text-emerald-700">
                    3 Sub-Tes Selesai & Tersimpan Aman
                </span>

                <h1 class="mt-3 text-2xl sm:text-3xl font-light text-[#322d2a]">
                    Terima Kasih, <span class="font-normal text-[#f97316]">{{ auth()->user()->name }}</span>!
                </h1>

                <p class="mt-2 text-xs sm:text-sm text-[#6f6b67] leading-relaxed max-w-lg mx-auto">
                    Seluruh rangkaian tes psikotes Anda untuk <strong>{{ $session->assessment?->title ?? ($assessment?->title ?? 'Asesmen Psikotes') }}</strong> telah berhasil kami terima dan tersimpan lengkap dalam sistem seleksi.
                </p>

                {{-- 3 SUBTEST STATUS BADGES --}}
                <div class="mt-6 grid gap-2.5 sm:grid-cols-3 text-xs">
                    <div class="rounded-[18px] border border-blue-200 bg-blue-50/70 p-3">
                        <p class="font-bold text-blue-900">Tahap 1: Deret Hitung</p>
                        <p class="mt-0.5 text-[11px] text-blue-700">✓ Selesai Dikerjakan</p>
                    </div>
                    <div class="rounded-[18px] border border-emerald-200 bg-emerald-50/70 p-3">
                        <p class="font-bold text-emerald-900">Tahap 2: Analogi Verbal</p>
                        <p class="mt-0.5 text-[11px] text-emerald-700">✓ Selesai Dikerjakan</p>
                    </div>
                    <div class="rounded-[18px] border border-purple-200 bg-purple-50/70 p-3">
                        <p class="font-bold text-purple-900">Tahap 3: Kepribadian</p>
                        <p class="mt-0.5 text-[11px] text-purple-700">✓ Selesai Dikerjakan</p>
                    </div>
                </div>

                <div class="mt-5 inline-flex flex-col sm:flex-row items-center gap-3 rounded-[20px] bg-[#f0efe9] p-4 text-xs text-[#6f6b67]">
                    <div>Waktu Selesai: <strong>{{ $session->completed_at_wib ? $session->completed_at_wib->format('d M Y, H:i') : now()->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</strong></div>
                    <div class="hidden sm:block text-[#6f6b67]/40">•</div>
                    <div>Kode Sesi: <strong class="font-mono text-[#322d2a]">{{ $session->session_token }}</strong></div>
                </div>

                <div class="mt-6 rounded-[22px] border border-[#322d2a]/10 bg-[#fbfaf8] p-4 text-xs text-[#6f6b67] leading-relaxed">
                    Hasil evaluasi psikotes dan tahapan seleksi selanjutnya akan ditinjau langsung oleh tim HRD PT Indodata Solusi Persada. Status lamaran Anda akan diperbarui melalui sistem dan notifikasi email resmi.
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('disc.assessment.export-excel', $session->session_token) }}"
                       class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-6 py-2.5 text-xs font-semibold text-white shadow-md shadow-emerald-600/20 transition hover:bg-emerald-700">
                        📊 Unduh Hasil Psikotes (.xlsx)
                    </a>
                    <a href="{{ route('disc.assessment.export-csv', $session->session_token) }}"
                       class="inline-flex items-center gap-2 rounded-full border border-[#322d2a]/20 bg-white px-5 py-2.5 text-xs font-semibold text-[#322d2a] shadow-xs transition hover:bg-[#f0efe9]">
                        📥 Unduh CSV
                    </a>
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 rounded-full bg-[#322d2a] px-6 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-[#f97316]">
                        Kembali ke Dashboard Utama →
                    </a>
                </div>
            </div>

        </div>
    </section>
</x-layouts.app>
