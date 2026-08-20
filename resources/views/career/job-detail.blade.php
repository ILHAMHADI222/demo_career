<x-layouts.app :title="$job->title . ' - Career Indodata'">

    {{-- HERO --}}
    <section class="relative overflow-hidden">
        <div
            class="relative bg-cover bg-center bg-no-repeat"
            style="background-image: url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=2000&q=85');"
        >
            <div class="absolute inset-0 bg-slate-950/70"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/75 to-slate-950/35"></div>

            <div class="absolute -left-24 top-20 h-72 w-72 rounded-full bg-orange-500/20 blur-3xl"></div>
            <div class="absolute right-10 top-12 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>

            <div class="relative z-10 mx-auto max-w-7xl px-4 py-12 sm:px-6 md:py-16 lg:px-8">
                <div class="max-w-4xl">
                    <a
                        href="{{ route('jobs.index') }}"
                        class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-slate-200 backdrop-blur transition hover:bg-white/15 hover:text-orange-300"
                    >
                        <span>←</span>
                        Kembali ke Lowongan
                    </a>

                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        <span class="rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-wide text-orange-300 backdrop-blur">
                            {{ $job->department?->name ?? 'General' }}
                        </span>
                        <span class="rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-wide text-white backdrop-blur">
                            {{ ucwords(str_replace('_', ' ', $job->employment_type)) }}
                        </span>
                        <span class="rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-wide text-white backdrop-blur">
                            {{ ucwords(str_replace('_', ' ', $job->work_arrangement)) }}
                        </span>
                    </div>

                    <h1 class="mt-4 text-3xl font-black leading-tight tracking-tight text-white sm:text-4xl md:text-5xl">
                        {{ $job->title }}
                    </h1>

                    <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-200 md:text-base">
                        {{ $job->description }}
                    </p>

                    <div class="mt-5 flex flex-wrap gap-2 text-sm font-bold text-slate-200">
                        <span class="rounded-full border border-white/15 bg-white/10 px-4 py-2 backdrop-blur">
                            {{ $job->location }}
                        </span>
                        <span class="rounded-full border border-white/15 bg-white/10 px-4 py-2 backdrop-blur">
                            {{ $job->number_of_openings ?? 1 }} Kebutuhan
                        </span>
                        <span class="rounded-full border border-emerald-300/30 bg-emerald-400/10 px-4 py-2 text-emerald-200 backdrop-blur">
                            Pendaftaran Dibuka
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- CONTENT --}}
    <section class="bg-slate-50 py-8 md:py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-5 lg:grid-cols-[1.2fr_0.8fr] lg:items-start">

                {{-- MAIN DETAIL --}}
                <div class="space-y-4">

                    <div class="-mt-14 relative z-20 overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-xl shadow-slate-300/25">
                        <div class="h-1.5 w-full bg-gradient-to-r from-orange-500 via-amber-400 to-orange-500"></div>
                        <div class="p-5 md:p-6">
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">
                                Detail Lowongan
                            </p>
                            <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950 md:text-3xl">
                                Informasi posisi
                            </h2>
                            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">
                                Pelajari tanggung jawab, kualifikasi, benefit, dan pertanyaan screening sebelum mengirim lamaran Anda.
                            </p>

                            <div class="mt-5 grid gap-3 sm:grid-cols-3">
                                <div class="rounded-2xl bg-slate-50 p-3 ring-1 ring-slate-100">
                                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">Mulai</p>
                                    <p class="mt-1 text-sm font-black text-slate-800">{{ optional($job->registration_start_at)->format('d M Y') ?? 'Sudah dibuka' }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-3 ring-1 ring-slate-100">
                                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">Berakhir</p>
                                    <p class="mt-1 text-sm font-black text-slate-800">{{ optional($job->deadline_at)->format('d M Y') ?? 'Tanpa batas waktu' }}</p>
                                </div>
                                <div class="rounded-2xl bg-orange-50 p-3 ring-1 ring-orange-100">
                                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-orange-400">Status</p>
                                    <p class="mt-1 text-sm font-black text-orange-600">Open</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach([
                        ['title' => 'Tanggung Jawab', 'content' => $job->responsibilities ?: 'Informasi tanggung jawab akan dijelaskan saat proses screening.'],
                        ['title' => 'Kualifikasi', 'content' => $job->requirements ?: 'Informasi kualifikasi akan disesuaikan dengan kebutuhan posisi.'],
                        ['title' => 'Benefit', 'content' => $job->benefits ?: 'Benefit akan diinformasikan lebih lanjut saat proses rekrutmen berjalan.'],
                    ] as $section)
                        <article class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm shadow-slate-200/70">
                            <div class="h-1 w-full bg-gradient-to-r from-orange-500 via-amber-400 to-orange-500"></div>
                            <div class="p-5 md:p-6">
                                <div class="flex items-start gap-4">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-orange-50 text-sm font-black text-orange-500 ring-1 ring-orange-100">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h2 class="text-2xl font-black text-slate-950">{{ $section['title'] }}</h2>
                                        <div class="prose mt-3 max-w-none text-sm leading-7 text-slate-600 md:text-base">
                                            {!! nl2br(e($section['content'])) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach

                    <article class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm shadow-slate-200/70">
                        <div class="h-1 w-full bg-gradient-to-r from-orange-500 via-amber-400 to-orange-500"></div>
                        <div class="p-5 md:p-6">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">Screening</p>
                                    <h2 class="mt-2 text-2xl font-black text-slate-950">Pertanyaan Tambahan</h2>
                                </div>
                                <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-black uppercase tracking-wide text-slate-500">
                                    {{ $job->questions->count() }} Pertanyaan
                                </span>
                            </div>

                            <div class="mt-4 space-y-3">
                                @forelse($job->questions as $question)
                                    <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
                                        <p class="text-sm font-black leading-6 text-slate-950">{{ $question->question }}</p>
                                        <p class="mt-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                            {{ $question->type }} {{ $question->required ? '• Wajib' : '• Opsional' }}
                                        </p>
                                    </div>
                                @empty
                                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-5 text-sm leading-7 text-slate-500">
                                        Belum ada pertanyaan screening tambahan untuk posisi ini.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </article>
                </div>

                {{-- SIDEBAR --}}
                <aside class="space-y-4 lg:sticky lg:top-28">
                    <div class="-mt-0 overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-xl shadow-slate-300/25 lg:-mt-14">
                        <div class="h-1.5 w-full bg-gradient-to-r from-orange-500 via-amber-400 to-orange-500"></div>
                        <div class="p-5">
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">Ringkasan</p>
                            <h2 class="mt-2 text-xl font-black text-slate-950">Ringkasan Posisi</h2>

                            <div class="mt-5 space-y-3 text-sm">
                                @foreach([
                                    'Departemen' => $job->department?->name ?? '-',
                                    'Divisi' => $job->division?->name ?? '-',
                                    'Posisi' => $job->position?->name ?? '-',
                                    'Lokasi' => $job->location,
                                    'Pembukaan' => ($job->number_of_openings ?? 1) . ' kebutuhan',
                                ] as $label => $value)
                                    <div class="rounded-2xl bg-slate-50 p-3 ring-1 ring-slate-100">
                                        <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-400">{{ $label }}</p>
                                        <p class="mt-1 font-black text-slate-800">{{ $value }}</p>
                                    </div>
                                @endforeach

                                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-3">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-emerald-600">Periode Pendaftaran</p>
                                    <p class="mt-2 text-sm font-bold text-slate-700">Mulai: {{ optional($job->registration_start_at)->format('d M Y H:i') ?? 'Sudah dibuka' }}</p>
                                    <p class="mt-1 text-sm font-bold text-slate-700">Berakhir: {{ optional($job->deadline_at)->format('d M Y H:i') ?? 'Tanpa batas waktu' }}</p>
                                    <p class="mt-3 text-xs font-black uppercase tracking-wide text-emerald-700">Status: Pendaftaran sedang dibuka.</p>
                                </div>
                            </div>

                            @auth
                                @if (auth()->user()->hasAnyRole(['SUPERADMIN', 'ADMIN']))
                                    <a href="{{ route('admin.applications.index') }}" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-orange-500 px-5 py-3.5 text-sm font-black text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-orange-600">
                                        Lihat Pelamar
                                        <span>→</span>
                                    </a>
                                @else
                                    <form method="POST" action="{{ route('jobs.apply', $job) }}" class="mt-6">
                                        @csrf
                                        <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-orange-500 px-5 py-3.5 text-sm font-black text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-orange-600">
                                            Lamar Posisi Ini
                                            <span>→</span>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('register') }}" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-orange-500 px-5 py-3.5 text-sm font-black text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-orange-600">
                                    Daftar untuk Melamar
                                    <span>→</span>
                                </a>
                            @endauth
                        </div>
                    </div>

                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm shadow-slate-200/70">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">Tahapan</p>
                        <h2 class="mt-2 text-xl font-black text-slate-950">Alur Rekrutmen</h2>
                        <div class="mt-5 space-y-3">
                            @foreach(['Daftar & lengkapi data', 'Upload berkas PDF', 'Review dokumen HRD', 'Interview / Hiring'] as $step)
                                <div class="group flex items-center gap-3 rounded-2xl bg-slate-50 p-3 ring-1 ring-slate-200 transition hover:bg-orange-50 hover:ring-orange-100">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-500 text-sm font-black text-white shadow-lg shadow-orange-500/20">
                                        {{ $loop->iteration }}
                                    </span>
                                    <p class="text-sm font-black text-slate-700">{{ $step }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>


    {{-- BOTTOM CTA --}}
    <section class="bg-white py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-[1.5rem] bg-slate-950 px-6 py-8 text-white shadow-2xl md:px-8 lg:flex lg:items-center lg:justify-between">
                <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-orange-500/20 blur-3xl"></div>
                <div class="absolute -bottom-24 left-20 h-56 w-56 rounded-full bg-amber-400/10 blur-3xl"></div>

                <div class="relative z-10 max-w-2xl">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-400">Career Indodata</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight md:text-4xl">Siap bergabung bersama kami?</h2>
                    <p class="mt-4 max-w-xl text-sm leading-7 text-slate-300">
                        Pastikan profil kandidat dan dokumen pendukung Anda sudah lengkap agar proses seleksi berjalan lebih cepat.
                    </p>
                </div>

                <div class="relative z-10 mt-8 flex flex-wrap gap-3 lg:mt-0">
                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-2xl bg-orange-500 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-orange-600">
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="rounded-2xl bg-orange-500 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-orange-600">
                            Daftar Kandidat
                        </a>
                    @endauth

                    <a href="{{ route('jobs.index') }}" class="rounded-2xl border border-white/15 bg-white/10 px-6 py-3.5 text-sm font-black text-white transition hover:bg-white/15">
                        Lowongan Lainnya
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
