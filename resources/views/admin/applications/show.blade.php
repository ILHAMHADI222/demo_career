<x-layouts.app :title="$application->application_number . ' - Data Diri Pelamar'">
    @php
        $profile = $application->candidateProfile;
        $education = $profile?->educations?->first();
        $experience = $profile?->experiences?->first();
        $identityFields = [
            'Apply Date' => $application->submitted_at?->format('d M Y') ?? $application->created_at?->format('d M Y'),
            'Position Applied' => $application->job?->title,
            'Candidate Name' => $profile?->full_name,
            'Gender' => $profile?->gender,
            'Status Pernikahan' => $profile?->marital_status,
            'NIK' => $profile?->nik,
            'Birth Place' => $profile?->birth_place,
            'Birth Date' => $profile?->birth_date?->format('d M Y'),
            'Address' => $profile?->address,
            'RT/RW' => $profile?->rt_rw,
            'Kel/Desa' => $profile?->village,
            'Kecamatan' => $profile?->district,
            'Kab/Kota' => $profile?->city,
            'Provinsi' => $profile?->province,
            'Kode Pos' => $profile?->postal_code,
            'No. Telp' => $profile?->phone,
            'WhatsApp' => $profile?->whatsapp,
            'Email' => $profile?->email,
            'LinkedIn URL' => $profile?->linkedin_url,
            'Portfolio URL' => $profile?->portfolio_url,
            'Educ' => $education?->education_level,
            'School/University' => $education?->institution,
            'Major' => $education?->major,
            'IPK/Rata2' => $education?->ipk,
            'Skills/Certification' => $education?->certification,
            'SIM' => $profile?->driving_license,
            'Last Company' => $experience?->company_name,
            'Position' => $experience?->position,
            'Years Worked' => $experience?->work_years,
            'Nomor Lamaran' => $application->application_number,
            'Status Lamaran' => str($application->status)->headline(),
        ];
    @endphp

    <section class="bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">
            <div class="relative overflow-hidden rounded-[32px] sm:rounded-[36px] bg-[#322d2a] text-white">
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#f97316]/20 blur-3xl"></div>
                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>
                <div class="relative z-10 p-6 sm:p-7 md:p-8">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-[#f97316]">Data Diri Pelamar</p>
                            <h1 class="mt-2 text-2xl sm:text-3xl md:text-4xl font-light tracking-tight text-white">{{ $profile?->full_name ?? '-' }}</h1>
                            <p class="mt-1.5 text-xs sm:text-sm font-normal text-white/75">{{ $application->job?->title ?? '-' }} • {{ $application->application_number }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5">
                            <a href="{{ route('admin.applications.export.excel', ['keyword' => $profile?->email]) }}" class="inline-flex items-center gap-1.5 rounded-full bg-[#f97316] px-4 py-2 text-xs font-normal text-white shadow-sm transition hover:bg-[#ea580c]">Export Excel <span>↓</span></a>
                            <a href="{{ route('admin.applications.index') }}" class="inline-flex items-center gap-1.5 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-normal text-white transition hover:bg-white/20">← Kembali</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-[0.34fr_1fr]">
                <div class="rounded-[28px] sm:rounded-[32px] border border-white bg-white p-5 sm:p-6">
                    <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-[#f97316]">Pass Foto</p>
                    <div class="mt-4">
                        @if($profile?->photo)
                            <img src="{{ asset('storage/'.$profile->photo) }}" alt="Foto {{ $profile->full_name }}" class="aspect-[3/4] w-full rounded-[22px] object-cover ring-1 ring-[#322d2a]/10 shadow-sm">
                        @else
                            <div class="flex aspect-[3/4] w-full items-center justify-center rounded-[22px] bg-[#fff3e8] text-5xl font-light text-[#f97316] ring-1 ring-[#f97316]/20">{{ strtoupper(substr($profile?->full_name ?? 'K', 0, 1)) }}</div>
                        @endif
                    </div>
                    <div class="mt-4 rounded-[18px] bg-[#f0efe9] p-3.5 ring-1 ring-[#322d2a]/10 text-xs">
                        <p class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Status Lamaran</p>
                        <p class="mt-1 font-medium text-[#f97316]">{{ str($application->status)->headline() }}</p>
                    </div>
                </div>

                <div class="rounded-[28px] sm:rounded-[32px] border border-white bg-white p-5 sm:p-6 lg:p-7">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-[#322d2a]/10 pb-4">
                        <div>
                            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-[#f97316]">Candidate Full Profile</p>
                            <h2 class="mt-0.5 text-xl font-light text-[#322d2a] sm:text-2xl">Semua Data Diri</h2>
                        </div>
                        <span class="rounded-full bg-[#fff3e8] px-3.5 py-1 text-xs font-normal text-[#f97316] ring-1 ring-[#f97316]/20">{{ $profile?->profile_completion ?? 0 }}% Lengkap</span>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($identityFields as $label => $value)
                            <div class="rounded-[18px] bg-[#f0efe9] p-3.5 ring-1 ring-[#322d2a]/10 text-xs">
                                <p class="text-[10px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">{{ $label }}</p>
                                <p class="mt-1 break-words font-medium leading-relaxed text-[#322d2a]">{{ $value ?: '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

