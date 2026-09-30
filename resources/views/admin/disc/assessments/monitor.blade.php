<x-layouts.app :title="$assessment->title . ' - Monitoring Asesmen'">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">

            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('admin.disc.assessments.show', $assessment) }}"
                   class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    ← Kembali ke Detail Asesmen
                </a>

                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span class="inline-block h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span>Monitoring Aktif • Auto-refresh log</span>
                </div>
            </div>

            <div class="rounded-[40px] border border-white/80 bg-white p-8 shadow-sm">
                <span class="rounded-full bg-blue-50 px-3.5 py-1 text-xs font-semibold text-blue-700">
                    Real-time Proctoring & Activity Log
                </span>
                <h1 class="mt-3 text-2xl font-light text-[#322d2a] md:text-3xl">
                    Live Monitoring: <span class="font-normal text-[#f97316]">{{ $assessment->title }}</span>
                </h1>
                <p class="mt-1 text-xs text-slate-500">
                    Pantau progres pengisian 24 butir soal, deteksi event perpindahan tab, window blur, dan fullscreen exit peserta.
                </p>
            </div>

            <div class="mt-8 space-y-4">
                @forelse ($activeSessions as $sess)
                    <div class="overflow-hidden rounded-3xl border border-white/80 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between border-b border-slate-100 pb-4">
                            <div>
                                <h3 class="text-base font-semibold text-[#322d2a]">{{ $sess->participant->name ?? '-' }}</h3>
                                <p class="text-xs text-slate-500">{{ $sess->participant->email ?? '-' }} • IP: {{ $sess->ip_address ?? '-' }}</p>
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $sess->status === 'COMPLETED' ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700' }}">
                                    {{ $sess->status }}
                                </span>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-mono text-slate-700">
                                    Skor Keamanan: <strong class="{{ $sess->security_score >= 80 ? 'text-emerald-600' : 'text-amber-600' }}">{{ $sess->security_score }}/100</strong>
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-6 text-xs">
                            <div class="rounded-2xl bg-slate-50 p-3">
                                <p class="text-slate-500">Tahap Berjalan</p>
                                <p class="mt-1 font-bold text-[#322d2a]">
                                    Tahap {{ $sess->current_stage ?? 1 }}/3
                                </p>
                            </div>
                            <div class="rounded-2xl bg-blue-50/60 border border-blue-100 p-3">
                                <p class="text-blue-900 font-medium">Sub-Tes 1 (Deret)</p>
                                <p class="mt-1 font-bold text-blue-700">
                                    {{ $sess->stage_1_completed_at ? number_format($sess->stage_1_score ?? 0, 1) . '% (' . ($sess->stage_1_correct_count ?? 0) . '/25)' : (($sess->current_stage ?? 1) == 1 ? 'Mengerjakan...' : '-') }}
                                </p>
                            </div>
                            <div class="rounded-2xl bg-emerald-50/60 border border-emerald-100 p-3">
                                <p class="text-emerald-900 font-medium">Sub-Tes 2 (Analogi)</p>
                                <p class="mt-1 font-bold text-emerald-700">
                                    {{ $sess->stage_2_completed_at ? number_format($sess->stage_2_score ?? 0, 1) . '% (' . ($sess->stage_2_correct_count ?? 0) . '/50)' : (($sess->current_stage ?? 1) == 2 ? 'Mengerjakan...' : '-') }}
                                </p>
                            </div>
                            <div class="rounded-2xl bg-purple-50/60 border border-purple-100 p-3">
                                <p class="text-purple-900 font-medium">Tahap 3 (DISC)</p>
                                <p class="mt-1 font-bold text-purple-700">
                                    {{ $sess->disc_profile_code ?: (($sess->current_stage ?? 1) == 3 ? 'Mengerjakan...' : '-') }}
                                </p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-3">
                                <p class="text-slate-500">Waktu Mulai</p>
                                <p class="mt-1 font-semibold text-slate-800">{{ $sess->started_at_wib ? $sess->started_at_wib->format('H:i:s') . ' WIB' : '-' }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-3">
                                <p class="text-slate-500">Aktivitas Terakhir</p>
                                <p class="mt-1 font-semibold text-slate-800">{{ $sess->last_activity_at ? $sess->last_activity_at->diffForHumans() : '-' }}</p>
                            </div>
                        </div>

                        @if ($sess->securityEvents->isNotEmpty())
                            <div class="mt-4 border-t border-slate-100 pt-3">
                                <p class="text-xs font-bold text-slate-700 mb-2">Riwayat Event Terakhir:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($sess->securityEvents->take(5) as $ev)
                                        <span class="rounded-md border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-mono text-slate-700">
                                            ⚠️ {{ $ev->event_type }} ({{ $ev->occurred_at_wib ? $ev->occurred_at_wib->format('H:i:s') . ' WIB' : '' }})
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="rounded-3xl border border-white/80 bg-white p-12 text-center text-slate-500 shadow-sm">
                        Belum ada sesi peserta aktif pada asesmen ini.
                    </div>
                @endforelse
            </div>

        </div>
    </section>
</x-layouts.app>
