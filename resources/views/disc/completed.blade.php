<x-layouts.app title="Asesmen Selesai - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-16 pt-28 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">

            <div class="overflow-hidden rounded-[48px] border border-white/80 bg-white p-8 shadow-xl shadow-slate-900/5 md:p-12 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 text-4xl text-emerald-600 shadow-md">
                    ✓
                </div>

                <span class="mt-6 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-4 py-1 text-xs font-semibold uppercase tracking-wider text-emerald-700">
                    Selesai & Tersimpan Aman
                </span>

                <h1 class="mt-3 text-3xl font-light text-[#322d2a] md:text-4xl">
                    Terima Kasih, <span class="font-normal text-[#f97316]">{{ auth()->user()->name }}</span>!
                </h1>

                <p class="mt-3 text-sm text-slate-500 leading-relaxed max-w-lg mx-auto">
                    Jawaban asesmen DISC Anda untuk <strong>{{ $assessment->title }}</strong> telah berhasil kami terima dan dikunci dalam sistem.
                </p>

                <div class="mt-6 inline-flex flex-col sm:flex-row items-center gap-4 rounded-3xl bg-slate-50 p-5 text-xs text-slate-600">
                    <div>Waktu Selesai: <strong>{{ $session->completed_at ? $session->completed_at->format('d M Y, H:i:s') : '-' }}</strong></div>
                    <div class="hidden sm:block text-slate-300">•</div>
                    <div>Kode Sesi: <strong class="font-mono text-slate-800">{{ $session->session_token }}</strong></div>
                </div>

                {{-- CONDITIONAL RESULT DISPLAY BASED ON HRD VISIBILITY POLICY --}}
                @if ($assessment->result_visibility === 'PARTICIPANT_AFTER_COMPLETION' && $session->disc_profile_code)
                    <div class="mt-8 rounded-3xl border border-orange-200 bg-orange-50/40 p-6 text-left">
                        <span class="text-xs uppercase tracking-wider font-bold text-[#f97316]">Hasil Profil DISC Anda</span>
                        <h2 class="mt-2 text-2xl font-light text-[#322d2a]">
                            Tipe: <strong class="font-mono text-3xl text-[#f97316]">{{ $session->disc_profile_code }}</strong> — {{ $session->disc_profile_name }}
                        </h2>
                        @php
                            $interp = $session->interpretation_json ?? [];
                        @endphp
                        <p class="mt-3 text-xs leading-relaxed text-slate-700">
                            {{ $interp['description'] ?? '' }}
                        </p>
                        @if (!empty($interp['strengths']))
                            <div class="mt-4 border-t border-orange-100 pt-3 text-xs text-slate-600">
                                <strong>Kekuatan Alami:</strong> {{ $interp['strengths'] }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="mt-8 rounded-3xl border border-slate-200 bg-slate-50/60 p-6 text-xs text-slate-500">
                        Hasil evaluasi psikotes dan tahapan seleksi selanjutnya akan ditinjau langsung oleh tim HRD PT Indodata Solusi Persada.
                    </div>
                @endif

                <div class="mt-8">
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 rounded-full bg-[#322d2a] px-8 py-3.5 text-xs font-semibold text-white shadow-lg shadow-slate-900/10 transition hover:bg-black">
                        Kembali ke Dashboard Utama →
                    </a>
                </div>
            </div>

        </div>
    </section>
</x-layouts.app>
