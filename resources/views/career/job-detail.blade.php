<x-layouts.app :title="$job->title . ' - Career Indodata'">
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div>
                <div class="rounded-[2rem] bg-gradient-to-br from-orange-500 via-amber-400 to-orange-500 p-8 text-white shadow-2xl shadow-orange-500/20 md:p-10">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-black uppercase tracking-wide">{{ $job->department?->name ?? 'General' }}</span>
                        <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-black uppercase tracking-wide">{{ str_replace('_', ' ', $job->employment_type) }}</span>
                        <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-black uppercase tracking-wide">{{ str_replace('_', ' ', $job->work_arrangement) }}</span>
                    </div>
                    <h1 class="mt-5 text-4xl font-black tracking-tight md:text-5xl">{{ $job->title }}</h1>
                    <p class="mt-4 text-base font-semibold text-white/90">{{ $job->location }} • Deadline {{ optional($job->deadline_at)->format('d M Y') ?? 'Flexible' }}</p>
                    <p class="mt-6 max-w-3xl text-sm leading-8 text-white/90 md:text-base">{{ $job->description }}</p>
                </div>

                <div class="mt-8 grid gap-6">
                    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/70 md:p-8">
                        <h2 class="text-2xl font-black text-slate-950">Tanggung Jawab</h2>
                        <div class="prose mt-4 max-w-none text-slate-600">{!! nl2br(e($job->responsibilities ?: 'Informasi tanggung jawab akan dijelaskan saat proses screening.')) !!}</div>
                    </div>
                    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/70 md:p-8">
                        <h2 class="text-2xl font-black text-slate-950">Kualifikasi</h2>
                        <div class="prose mt-4 max-w-none text-slate-600">{!! nl2br(e($job->requirements ?: 'Informasi kualifikasi akan disesuaikan dengan kebutuhan posisi.')) !!}</div>
                    </div>
                    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/70 md:p-8">
                        <h2 class="text-2xl font-black text-slate-950">Benefit</h2>
                        <div class="prose mt-4 max-w-none text-slate-600">{!! nl2br(e($job->benefits ?: 'Benefit akan diinformasikan lebih lanjut saat proses rekrutmen berjalan.')) !!}</div>
                    </div>
                    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/70 md:p-8">
                        <h2 class="text-2xl font-black text-slate-950">Pertanyaan Screening</h2>
                        <div class="mt-4 space-y-4">
                            @forelse($job->questions as $question)
                                <div class="rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-200">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-black text-slate-950">{{ $question->question }}</p>
                                            <p class="mt-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $question->type }} {{ $question->required ? '• Wajib' : '• Opsional' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-300 p-5 text-sm text-slate-500">Belum ada pertanyaan screening tambahan untuk posisi ini.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/70">
                    <h2 class="text-xl font-black text-slate-950">Ringkasan Posisi</h2>
                    <div class="mt-5 space-y-4 text-sm">
                        <div class="rounded-2xl bg-slate-50 p-4"><p class="font-black text-slate-950">Departemen</p><p class="mt-1 text-slate-600">{{ $job->department?->name ?? '-' }}</p></div>
                        <div class="rounded-2xl bg-slate-50 p-4"><p class="font-black text-slate-950">Divisi</p><p class="mt-1 text-slate-600">{{ $job->division?->name ?? '-' }}</p></div>
                        <div class="rounded-2xl bg-slate-50 p-4"><p class="font-black text-slate-950">Posisi</p><p class="mt-1 text-slate-600">{{ $job->position?->name ?? '-' }}</p></div>
                        <div class="rounded-2xl bg-slate-50 p-4"><p class="font-black text-slate-950">Pembukaan</p><p class="mt-1 text-slate-600">{{ $job->number_of_openings ?? 1 }} kebutuhan</p></div>
                    </div>
                    @auth
                        @if (auth()->user()->hasAnyRole(['SUPERADMIN', 'ADMIN']))
                            <a href="{{ route('admin.applications.index') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-orange-500 px-5 py-3 text-sm font-black text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-orange-600">Lihat Pelamar</a>
                        @else
                            <form method="POST" action="{{ route('jobs.apply', $job) }}" class="mt-6">
                                @csrf
                                <button class="inline-flex w-full items-center justify-center rounded-2xl bg-orange-500 px-5 py-3 text-sm font-black text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-orange-600">Lamar Posisi Ini</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-orange-500 px-5 py-3 text-sm font-black text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-orange-600">Daftar untuk Melamar</a>
                    @endauth
                </div>

                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/70">
                    <h2 class="text-xl font-black text-slate-950">Alur Rekrutmen</h2>
                    <div class="mt-5 space-y-4">
                        @foreach(['Daftar & lengkapi data', 'Upload berkas PDF', 'Review dokumen HRD', 'Interview / Hiring'] as $step)
                            <div class="flex items-center gap-3 rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-orange-500 text-sm font-black text-white">{{ $loop->iteration }}</span>
                                <p class="text-sm font-bold text-slate-700">{{ $step }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </section>
</x-layouts.app>
