<x-layouts.app title="Dashboard Kandidat - Career Indodata">

    <section class="bg-slate-50 py-10 md:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- HERO --}}
            <div
                class="relative overflow-hidden rounded-[2rem] bg-cover bg-center text-white shadow-2xl shadow-slate-300/30"
                style="background-image: url('https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1800&q=85');"
            >
                <div class="absolute inset-0 bg-slate-950/70"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/70 to-orange-950/30"></div>

                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-orange-500/20 blur-3xl"></div>
                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>

                <div class="relative z-10 p-8 md:p-10 lg:p-12">
                    <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">

                        <div class="max-w-3xl">
                            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-[0.22em] text-orange-300 backdrop-blur">
                                <span class="h-2 w-2 rounded-full bg-orange-400"></span>
                                Candidate Dashboard
                            </span>

                            <h1 class="mt-5 text-4xl font-black leading-tight tracking-tight md:text-5xl">
                                Halo,
                                <span class="text-orange-400">
                                    {{ $profile?->full_name ?? auth()->user()->name }}
                                </span>
                            </h1>

                            <p class="mt-5 max-w-2xl text-sm leading-7 text-slate-200 md:text-base">
                                Kelola profil kandidat, pantau progres lamaran, jadwal interview,
                                serta informasi offering dalam satu dashboard terpusat.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <a
                                href="{{ route('candidate.profile') }}"
                                class="inline-flex items-center gap-2 rounded-2xl bg-white px-5 py-3 text-sm font-black text-slate-900 shadow-lg transition hover:-translate-y-0.5"
                            >
                                Lengkapi Profil
                                <span>→</span>
                            </a>

                            <a
                                href="{{ route('jobs.index') }}"
                                class="inline-flex items-center gap-2 rounded-2xl border border-white/20 bg-white/10 px-5 py-3 text-sm font-black text-white backdrop-blur transition hover:bg-white/20"
                            >
                                Cari Lowongan
                            </a>
                        </div>

                    </div>
                </div>
            </div>


            {{-- KPI --}}
            <div class="mt-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">
                        Candidate Number
                    </p>
                    <p class="mt-4 text-2xl font-black text-slate-950">
                        {{ $profile?->candidate_number ?? '-' }}
                    </p>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">
                                Profil Completion
                            </p>
                            <p class="mt-4 text-3xl font-black text-slate-950">
                                {{ $profile?->profile_completion ?? 0 }}%
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-sm font-black text-orange-600">
                            %
                        </div>
                    </div>

                    <div class="mt-5 h-2 overflow-hidden rounded-full bg-slate-100">
                        <div
                            class="h-full rounded-full bg-orange-500"
                            style="width: {{ min($profile?->profile_completion ?? 0, 100) }}%;"
                        ></div>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">
                        Lamaran Aktif
                    </p>
                    <p class="mt-4 text-3xl font-black text-slate-950">
                        {{ $activeApplications }}
                    </p>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">
                        Dokumen Tersimpan
                    </p>
                    <p class="mt-4 text-3xl font-black text-slate-950">
                        {{ $profile?->documents?->count() ?? 0 }}
                    </p>
                </div>

            </div>


            {{-- MAIN GRID --}}
            <div class="mt-8 grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">

                {{-- LEFT --}}
                <div class="space-y-6">

                    {{-- PROFILE --}}
                    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">

                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">
                                    Candidate Profile
                                </p>

                                <h2 class="mt-2 text-2xl font-black text-slate-950">
                                    Profil Kandidat
                                </h2>
                            </div>

                            <a
                                href="{{ route('candidate.profile') }}"
                                class="text-sm font-black text-orange-500 hover:text-orange-600"
                            >
                                Edit Profil
                            </a>
                        </div>

                        <div class="mt-6 space-y-4">

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs font-black uppercase tracking-[0.14em] text-slate-400">
                                    Email
                                </p>
                                <p class="mt-2 text-sm font-black text-slate-800">
                                    {{ $profile?->email ?? auth()->user()->email }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs font-black uppercase tracking-[0.14em] text-slate-400">
                                    Telepon
                                </p>
                                <p class="mt-2 text-sm font-black text-slate-800">
                                    {{ $profile?->phone ?? '-' }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs font-black uppercase tracking-[0.14em] text-slate-400">
                                    Alamat
                                </p>
                                <p class="mt-2 text-sm leading-6 text-slate-700">
                                    {{ $profile?->address ?? '-' }}
                                </p>
                            </div>

                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a
                                href="{{ route('candidate.profile') }}"
                                class="inline-flex items-center gap-2 rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black text-white shadow-lg shadow-slate-950/15 transition hover:-translate-y-0.5 hover:bg-orange-500"
                            >
                                Lengkapi Profil & Berkas
                                <span>→</span>
                            </a>

                            <a
                                href="{{ route('jobs.index') }}"
                                class="inline-flex items-center gap-2 rounded-2xl bg-orange-500 px-5 py-3 text-sm font-black text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-orange-600"
                            >
                                Cari Lowongan Baru
                            </a>
                        </div>

                    </div>


                    {{-- INTERVIEW --}}
                    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">

                        <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">
                            Interview Schedule
                        </p>

                        <h2 class="mt-2 text-2xl font-black text-slate-950">
                            Interview Mendatang
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Jadwal interview yang perlu Anda persiapkan.
                        </p>

                        <div class="mt-6 space-y-4">

                            @forelse($upcomingInterviews as $interview)

                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                                    <div class="flex items-start justify-between gap-4">

                                        <div>
                                            <p class="font-black text-slate-950">
                                                {{ $interview->type ?? 'Interview' }}
                                            </p>

                                            <p class="mt-2 text-sm font-semibold text-slate-500">
                                                Status:
                                                <span class="font-black text-slate-700">
                                                    {{ str($interview->status)->headline() }}
                                                </span>
                                            </p>
                                        </div>

                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-500">
                                            ◷
                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">
                                    <p class="font-black text-slate-700">
                                        Belum ada interview terjadwal
                                    </p>
                                    <p class="mt-2 text-sm text-slate-500">
                                        Jadwal interview akan muncul di sini setelah dijadwalkan HRD.
                                    </p>
                                </div>

                            @endforelse

                        </div>

                    </div>

                </div>


                {{-- RIGHT --}}
                <div class="space-y-6">

                    {{-- APPLICATIONS --}}
                    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">
                                    Application Tracking
                                </p>

                                <h2 class="mt-2 text-2xl font-black text-slate-950">
                                    Lamaran Terbaru
                                </h2>
                            </div>

                            <a
                                href="{{ route('jobs.index') }}"
                                class="text-sm font-black text-orange-500 hover:text-orange-600"
                            >
                                Cari Lowongan →
                            </a>

                        </div>

                        <div class="mt-6 space-y-4">

                            @forelse($applications as $application)

                                <div class="group rounded-2xl border border-slate-200 bg-slate-50 p-5 transition hover:-translate-y-0.5 hover:bg-white hover:shadow-lg">

                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                                        <div>

                                            <p class="text-lg font-black text-slate-950 group-hover:text-orange-500">
                                                {{ $application->job?->title ?? 'Posisi tidak tersedia' }}
                                            </p>

                                            <p class="mt-1 text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                                                {{ $application->application_number }}
                                            </p>

                                        </div>

                                        <span class="inline-flex w-fit rounded-full bg-orange-50 px-3 py-1 text-xs font-black uppercase tracking-wide text-orange-600 ring-1 ring-orange-100">
                                            {{ str($application->status)->headline() }}
                                        </span>

                                    </div>

                                    <div class="mt-5 flex items-center justify-between border-t border-slate-200 pt-4">

                                        <span class="text-xs font-semibold text-slate-500">
                                            Status lamaran diperbarui oleh tim HRD
                                        </span>

                                        @if($application->job)
                                            <a
                                                href="{{ route('jobs.show', $application->job) }}"
                                                class="text-sm font-black text-orange-500 hover:text-orange-600"
                                            >
                                                Detail →
                                            </a>
                                        @endif

                                    </div>

                                </div>

                            @empty

                                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">

                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-50 text-xl">
                                        📄
                                    </div>

                                    <p class="mt-4 font-black text-slate-800">
                                        Belum ada lamaran aktif
                                    </p>

                                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                                        Temukan posisi yang sesuai dan mulai kirim lamaran melalui Career Indodata.
                                    </p>

                                    <a
                                        href="{{ route('jobs.index') }}"
                                        class="mt-5 inline-flex rounded-2xl bg-orange-500 px-5 py-3 text-sm font-black text-white transition hover:bg-orange-600"
                                    >
                                        Lihat Lowongan
                                    </a>

                                </div>

                            @endforelse

                        </div>

                    </div>


                    {{-- OFFERING --}}
                    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">

                        <p class="text-xs font-black uppercase tracking-[0.22em] text-emerald-600">
                            Offer Management
                        </p>

                        <h2 class="mt-2 text-2xl font-black text-slate-950">
                            Offering
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Informasi penawaran kerja yang tersedia untuk Anda.
                        </p>

                        <div class="mt-6 space-y-4">

                            @forelse($offers as $offer)

                                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5">

                                    <div class="flex items-start gap-4">

                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-500 font-black text-white">
                                            ✓
                                        </div>

                                        <div>

                                            <p class="font-black text-emerald-950">
                                                Offer tersedia
                                            </p>

                                            <p class="mt-1 text-sm text-emerald-800">
                                                Application ID: {{ $offer->application_id }}
                                            </p>

                                            <p class="mt-2 text-xs leading-5 text-emerald-700">
                                                Silakan periksa informasi offering yang diberikan oleh tim recruitment.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">

                                    <p class="font-black text-slate-700">
                                        Belum ada offering tersedia
                                    </p>

                                    <p class="mt-2 text-sm leading-6 text-slate-500">
                                        Informasi offering akan muncul setelah Anda mencapai tahap penawaran kerja.
                                    </p>

                                </div>

                            @endforelse

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>

</x-layouts.app>