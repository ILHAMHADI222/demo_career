<x-layouts.app title="Dashboard Kandidat - Career Indodata">

    <section class="bg-[#f0efe9] px-4 pb-12 pt-28 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">

            {{-- HERO --}}
            <div class="grid gap-6 lg:grid-cols-[1.08fr_0.92fr] lg:items-stretch">
                <div class="rounded-[56px] border border-white bg-white p-6 sm:p-8 lg:p-10">
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        Candidate Dashboard
                    </div>

                    <h1 class="mt-8 max-w-3xl text-[44px] font-light leading-none tracking-[0.52px] text-[#322d2a] md:text-[56px]">
                        Halo,
                        <span class="text-[#f97316]">
                            {{ $profile?->full_name ?? auth()->user()->name }}
                        </span>
                    </h1>

                    <p class="mt-6 max-w-2xl text-sm font-normal leading-[1.6] tracking-[0.14px] text-[#6f6b67] md:text-base">
                        Kelola profil kandidat, pantau progres lamaran, jadwal interview,
                        serta informasi offering dalam satu dashboard terpusat.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a
                            href="{{ route('candidate.profile') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-full bg-[#f97316] px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#ea580c]"
                        >
                            Lengkapi Profil
                            <span>→</span>
                        </a>

                        <a
                            href="{{ route('jobs.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-full border border-[#322d2a]/30 bg-transparent px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-[#322d2a] transition hover:border-[#f97316] hover:bg-[#fff3e8] hover:text-[#f97316]"
                        >
                            Cari Lowongan
                        </a>
                    </div>
                </div>

                <div
                    class="relative min-h-[360px] overflow-hidden rounded-[56px] bg-cover bg-center text-white"
                    style="background-image: url('https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1800&q=85');"
                >
                    <div class="absolute inset-0 bg-[#0f0e12]/55"></div>
                    <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-[#f97316]/25 blur-3xl"></div>
                    <div class="absolute -bottom-24 left-8 h-56 w-56 rounded-full bg-[#f97316]/10 blur-3xl"></div>

                    <div class="relative z-10 flex h-full flex-col justify-between p-8 sm:p-10">
                        <div class="flex w-fit items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-normal uppercase tracking-[0.12em] text-white backdrop-blur-md">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                            Career Hallonet
                        </div>

                        <div class="rounded-[32px] border border-white/15 bg-white/10 p-6 backdrop-blur-md">
                            <p class="text-xs font-normal uppercase tracking-[0.12em] text-white/60">Candidate Number</p>
                            <p class="mt-3 break-all text-2xl font-light tracking-[0.24px] text-white">
                                {{ $profile?->candidate_number ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            {{-- KPI --}}
            <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

                <div class="rounded-[32px] border border-white bg-white p-6">
                    <p class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">
                        Candidate Number
                    </p>
                    <p class="mt-4 text-2xl font-light tracking-[0.24px] text-[#322d2a]">
                        {{ $profile?->candidate_number ?? '-' }}
                    </p>
                </div>

                <div class="rounded-[32px] border border-white bg-white p-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">
                                Profil Completion
                            </p>
                            <p class="mt-4 text-3xl font-light tracking-[0.32px] text-[#322d2a]">
                                {{ $profile?->profile_completion ?? 0 }}%
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-[20px] bg-[#fff3e8] text-sm font-normal text-[#f97316]">
                            %
                        </div>
                    </div>

                    <div class="mt-5 h-2 overflow-hidden rounded-full bg-[#f0efe9]">
                        <div
                            class="h-full rounded-full bg-[#f97316]"
                            style="width: {{ min($profile?->profile_completion ?? 0, 100) }}%;"
                        ></div>
                    </div>
                </div>

                <div class="rounded-[32px] border border-white bg-white p-6">
                    <p class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">
                        Lamaran Aktif
                    </p>
                    <p class="mt-4 text-3xl font-light tracking-[0.32px] text-[#322d2a]">
                        {{ $activeApplications }}
                    </p>
                </div>

                <div class="rounded-[32px] border border-white bg-white p-6">
                    <p class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">
                        Dokumen Tersimpan
                    </p>
                    <p class="mt-4 text-3xl font-light tracking-[0.32px] text-[#322d2a]">
                        {{ $profile?->documents?->count() ?? 0 }}
                    </p>
                </div>

            </div>


            {{-- MAIN GRID --}}
            <div class="mt-6 grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">

                {{-- LEFT --}}
                <div class="space-y-6">

                    {{-- PROFILE --}}
                    <div class="rounded-[40px] border border-white bg-white p-6 md:p-8">

                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                    <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                                    Candidate Profile
                                </p>

                                <h2 class="mt-4 text-[32px] font-light leading-none tracking-[0.32px] text-[#322d2a]">
                                    Profil Kandidat
                                </h2>
                            </div>

                            <a
                                href="{{ route('candidate.profile') }}"
                                class="text-sm font-normal tracking-[0.14px] text-[#f97316] transition hover:text-[#ea580c]"
                            >
                                Edit Profil
                            </a>
                        </div>

                        <div class="mt-6 space-y-3">

                            <div class="rounded-[24px] border border-[#322d2a]/10 bg-[#f0efe9] p-4">
                                <p class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">
                                    Email
                                </p>
                                <p class="mt-2 break-all text-sm font-normal tracking-[0.14px] text-[#322d2a]">
                                    {{ $profile?->email ?? auth()->user()->email }}
                                </p>
                            </div>

                            <div class="rounded-[24px] border border-[#322d2a]/10 bg-[#f0efe9] p-4">
                                <p class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">
                                    Telepon
                                </p>
                                <p class="mt-2 text-sm font-normal tracking-[0.14px] text-[#322d2a]">
                                    {{ $profile?->phone ?? '-' }}
                                </p>
                            </div>

                            <div class="rounded-[24px] border border-[#322d2a]/10 bg-[#f0efe9] p-4">
                                <p class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">
                                    Alamat
                                </p>
                                <p class="mt-2 text-sm font-normal leading-[1.6] tracking-[0.14px] text-[#322d2a]">
                                    {{ $profile?->address ?? '-' }}
                                </p>
                            </div>

                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a
                                href="{{ route('candidate.profile') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-full bg-[#322d2a] px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#0f0e12]"
                            >
                                Lengkapi Profil & Berkas
                                <span>→</span>
                            </a>

                            <a
                                href="{{ route('jobs.index') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-full bg-[#f97316] px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#ea580c]"
                            >
                                Cari Lowongan Baru
                            </a>
                        </div>

                    </div>


                    {{-- INTERVIEW --}}
                    <div class="rounded-[40px] border border-white bg-white p-6 md:p-8">

                        <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                            Interview Schedule
                        </p>

                        <h2 class="mt-4 text-[32px] font-light leading-none tracking-[0.32px] text-[#322d2a]">
                            Interview Mendatang
                        </h2>

                        <p class="mt-4 text-sm font-normal leading-[1.6] tracking-[0.14px] text-[#6f6b67]">
                            Jadwal interview yang perlu Anda persiapkan.
                        </p>

                        <div class="mt-6 space-y-4">

                            @forelse($upcomingInterviews as $interview)

                                <div class="rounded-[28px] border border-[#322d2a]/10 bg-[#f0efe9] p-5">

                                    <div class="flex items-start justify-between gap-4">

                                        <div>
                                            <p class="font-normal tracking-[0.16px] text-[#322d2a]">
                                                {{ $interview->type ?? 'Interview' }}
                                            </p>

                                            <p class="mt-2 text-sm font-normal text-[#6f6b67]">
                                                Status:
                                                <span class="text-[#322d2a]">
                                                    {{ str($interview->status)->headline() }}
                                                </span>
                                            </p>
                                        </div>

                                        <div class="flex h-10 w-10 items-center justify-center rounded-[16px] bg-[#fff3e8] text-[#f97316]">
                                            ◷
                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div class="rounded-[28px] border border-dashed border-[#322d2a]/25 bg-[#f0efe9] p-6 text-center">
                                    <p class="font-normal tracking-[0.16px] text-[#322d2a]">
                                        Belum ada interview terjadwal
                                    </p>
                                    <p class="mt-2 text-sm font-normal leading-[1.6] text-[#6f6b67]">
                                        Jadwal interview akan muncul di sini setelah dijadwalkan HRD.
                                    </p>
                                </div>

                            @endforelse

                        </div>

                    </div>


                    {{-- DISC ASSESSMENT --}}
                    <div class="rounded-[40px] border border-white bg-white p-6 md:p-8">

                        <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                            Psychometric Test
                        </p>

                        <h2 class="mt-4 text-[32px] font-light leading-none tracking-[0.32px] text-[#322d2a]">
                            Asesmen DISC
                        </h2>

                        <p class="mt-4 text-sm font-normal leading-[1.6] tracking-[0.14px] text-[#6f6b67]">
                            Uji kepribadian kerja DISC untuk melengkapi profil seleksi Anda.
                        </p>

                        <div class="mt-6 space-y-4">

                            @forelse($discAssessments as $discPart)
                                @php
                                    $assess = $discPart->assessment;
                                    $session = $discPart->latestSession;
                                @endphp
                                <div class="rounded-[28px] border {{ $session && $session->isCompleted() ? 'border-emerald-200 bg-emerald-50/50' : 'border-[#f97316]/30 bg-[#fff3e8]' }} p-5">

                                    <div class="flex items-start justify-between gap-4">

                                        <div>
                                            <span class="rounded-md bg-white px-2 py-0.5 font-mono text-[11px] font-bold text-slate-700">{{ $assess->code }}</span>
                                            <p class="mt-2 font-normal tracking-[0.16px] text-[#322d2a]">
                                                {{ $assess->title }}
                                            </p>

                                            <p class="mt-1 text-xs text-[#6f6b67]">
                                                Durasi: {{ $assess->duration_minutes }} Menit • Status: 
                                                <strong class="{{ $session && $session->isCompleted() ? 'text-emerald-700' : 'text-[#f97316]' }}">
                                                    {{ $session ? $session->status : 'BELUM MULAI' }}
                                                </strong>
                                            </p>

                                            <div class="mt-4">
                                                @if ($session && $session->isCompleted())
                                                    <a href="{{ route('disc.assessment.completed', $session->session_token) }}"
                                                       class="inline-flex rounded-full bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                                        Lihat Konfirmasi Selesai ✓
                                                    </a>
                                                @else
                                                    <a href="{{ route('disc.assessment.instruction', $assess) }}"
                                                       class="inline-flex rounded-full bg-[#f97316] px-5 py-2.5 text-xs font-semibold text-white shadow-md shadow-orange-500/20 transition hover:bg-[#ea580c]">
                                                        {{ $session ? 'Lanjutkan Tes DISC →' : 'Mulai Kerjakan Tes DISC →' }}
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[16px] {{ $session && $session->isCompleted() ? 'bg-emerald-100 text-emerald-600' : 'bg-[#fff3e8] text-[#f97316]' }} text-sm font-bold font-mono">
                                            DISC
                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div class="rounded-[28px] border border-dashed border-[#322d2a]/25 bg-[#f0efe9] p-6 text-center">
                                    <p class="font-normal tracking-[0.16px] text-[#322d2a]">
                                        Belum ada jadwal asesmen DISC
                                    </p>
                                    <p class="mt-2 text-sm font-normal leading-[1.6] text-[#6f6b67]">
                                        Jika diundang psikotes DISC oleh HRD, tautan pengerjaan akan otomatis tampil di sini.
                                    </p>
                                </div>

                            @endforelse

                        </div>

                    </div>

                </div>


                {{-- RIGHT --}}
                <div class="space-y-6">

                    {{-- APPLICATIONS --}}
                    <div class="rounded-[40px] border border-white bg-white p-6 md:p-8">

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

                            <div>
                                <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                    <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                                    Application Tracking
                                </p>

                                <h2 class="mt-4 text-[32px] font-light leading-none tracking-[0.32px] text-[#322d2a]">
                                    Lamaran Terbaru
                                </h2>
                            </div>
                            <a
                                href="{{ route('jobs.index') }}"
                                class="text-sm font-normal tracking-[0.14px] text-[#f97316] transition hover:text-[#ea580c]"
                            >
                                Cari Lowongan →
                            </a>

                        </div>

                        <div class="mt-6 space-y-4">

                            @forelse($applications as $application)

                                <div class="group rounded-[28px] border border-[#322d2a]/10 bg-[#f0efe9] p-5 transition hover:border-[#f97316]/40 hover:bg-[#fff3e8]">

                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                                        <div>

                                            <p class="text-lg font-normal tracking-[0.18px] text-[#322d2a] transition group-hover:text-[#f97316]">
                                                {{ $application->job?->title ?? 'Posisi tidak tersedia' }}
                                            </p>

                                            <p class="mt-1 text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">
                                                {{ $application->application_number }}
                                            </p>

                                        </div>
                                        <span class="inline-flex w-fit rounded-full bg-[#fff3e8] px-4 py-2 text-sm font-normal tracking-[0.14px] text-[#f97316] ring-1 ring-[#f97316]/20">
                                            {{ str($application->status)->headline() }}
                                        </span>

                                    </div>
                                    <div class="mt-5 flex items-center justify-between gap-4 border-t border-[#322d2a]/10 pt-4">

                                        <span class="text-xs font-normal leading-[1.5] text-[#6f6b67]">
                                            Status lamaran diperbarui oleh tim HRD
                                        </span>

                                        @if($application->job)
                                            <a
                                                href="{{ route('jobs.show', $application->job) }}"
                                                class="shrink-0 text-sm font-normal tracking-[0.14px] text-[#f97316] transition hover:text-[#ea580c]"
                                            >
                                                Detail →
                                            </a>
                                        @endif

                                    </div>

                                </div>

                            @empty

                                <div class="rounded-[28px] border border-dashed border-[#322d2a]/25 bg-[#f0efe9] p-8 text-center">

                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-[20px] bg-[#fff3e8] text-xl text-[#f97316]">
                                        📄
                                    </div>

                                    <p class="mt-4 font-normal tracking-[0.16px] text-[#322d2a]">
                                        Belum ada lamaran aktif
                                    </p>

                                    <p class="mx-auto mt-2 max-w-md text-sm font-normal leading-[1.6] text-[#6f6b67]">
                                        Temukan posisi yang sesuai dan mulai kirim lamaran melalui Career Indodata.
                                    </p>

                                    <a
                                        href="{{ route('jobs.index') }}"
                                        class="mt-5 inline-flex rounded-full bg-[#f97316] px-[22px] py-[14px] text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#ea580c]"
                                    >
                                        Lihat Lowongan
                                    </a>

                                </div>

                            @endforelse

                        </div>
                    </div>


                    {{-- OFFERING --}}
                    <div class="rounded-[40px] border border-white bg-white p-6 md:p-8">

                        <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                            Offer Management
                        </p>

                        <h2 class="mt-4 text-[32px] font-light leading-none tracking-[0.32px] text-[#322d2a]">
                            Offering
                        </h2>

                        <p class="mt-4 text-sm font-normal leading-[1.6] tracking-[0.14px] text-[#6f6b67]">
                            Informasi penawaran kerja yang tersedia untuk Anda.
                        </p>

                        <div class="mt-6 space-y-4">

                            @forelse($offers as $offer)

                                <div class="rounded-[28px] border border-[#f97316]/25 bg-[#fff3e8] p-5">

                                    <div class="flex items-start gap-4">

                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[18px] bg-[#f97316] font-normal text-white">
                                            ✓
                                        </div>

                                        <div>

                                            <p class="font-normal tracking-[0.16px] text-[#322d2a]">
                                                Offer tersedia
                                            </p>

                                            <p class="mt-1 text-sm font-normal text-[#6f6b67]">
                                                Application ID: {{ $offer->application_id }}
                                            </p>

                                            <p class="mt-2 text-xs font-normal leading-[1.6] text-[#6f6b67]">
                                                Silakan periksa informasi offering yang diberikan oleh tim recruitment.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div class="rounded-[28px] border border-dashed border-[#322d2a]/25 bg-[#f0efe9] p-6 text-center">

                                    <p class="font-normal tracking-[0.16px] text-[#322d2a]">
                                        Belum ada offering tersedia
                                    </p>

                                    <p class="mt-2 text-sm font-normal leading-[1.6] text-[#6f6b67]">
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
