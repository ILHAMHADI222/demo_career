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

    <section class="bg-[#f0efe9] px-4 pb-12 pt-28 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">
            <div class="relative overflow-hidden rounded-[56px] bg-[#322d2a] text-white">
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#f97316]/20 blur-3xl"></div>
                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>
                <div class="relative z-10 p-8 md:p-10">
                    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-xs font-normal uppercase tracking-[0.22em] text-[#f97316]">Data Diri Pelamar</p>
                            <h1 class="mt-4 text-4xl font-normal tracking-tight">{{ $profile?->full_name ?? '-' }}</h1>
                            <p class="mt-4 text-sm font-normal text-white/75">{{ $application->job?->title ?? '-' }} • {{ $application->application_number }}</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.applications.export.excel', ['keyword' => $profile?->email]) }}" class="inline-flex items-center gap-2 rounded-[24px] bg-[#f97316] px-5 py-3 text-sm font-normal text-white transition hover:bg-[#ea580c]">Export Excel <span>↓</span></a>
                            <a href="{{ route('admin.applications.index') }}" class="inline-flex items-center gap-2 rounded-[24px] border border-white/20 bg-white/10 px-5 py-3 text-sm font-normal text-white transition hover:bg-white/20">← Kembali</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[0.38fr_1fr]">
                <div class="rounded-[40px] border border-white bg-white p-6">
                    <p class="text-xs font-normal uppercase tracking-[0.2em] text-[#f97316]">Pass Foto</p>
                    <div class="mt-5">
                        @if($profile?->photo)
                            <img src="{{ asset('storage/'.$profile->photo) }}" alt="Foto {{ $profile->full_name }}" class="aspect-[3/4] w-full rounded-[28px] object-cover ring-1 ring-[#322d2a]/10">
                        @else
                            <div class="flex aspect-[3/4] w-full items-center justify-center rounded-[28px] bg-[#fff3e8] text-6xl font-normal text-[#f97316] ring-1 ring-[#f97316]/20">{{ strtoupper(substr($profile?->full_name ?? 'K', 0, 1)) }}</div>
                        @endif
                    </div>
                    <div class="mt-5 rounded-[24px] bg-[#f0efe9] p-4 ring-1 ring-[#322d2a]/10">
                        <p class="text-xs font-normal uppercase tracking-wide text-[#6f6b67]/70">Status Lamaran</p>
                        <p class="mt-1 font-normal text-[#f97316]">{{ str($application->status)->headline() }}</p>
                    </div>
                </div>

                <div class="rounded-[40px] border border-white bg-white p-6 md:p-8">
                    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="text-xs font-normal uppercase tracking-[0.2em] text-[#f97316]">Candidate Full Profile</p>
                            <h2 class="mt-2 text-2xl font-normal text-[#322d2a]">Semua Data Diri</h2>
                        </div>
                        <span class="rounded-full bg-[#fff3e8] px-4 py-2 text-xs font-normal text-[#f97316] ring-1 ring-[#f97316]/20">{{ $profile?->profile_completion ?? 0 }}% Lengkap</span>
                    </div>

                    <div class="mt-7 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($identityFields as $label => $value)
                            <div class="rounded-[24px] bg-[#f0efe9] p-4 ring-1 ring-[#322d2a]/10">
                                <p class="text-[10px] font-normal uppercase tracking-[0.15em] text-[#6f6b67]/70">{{ $label }}</p>
                                <p class="mt-2 break-words text-sm font-normal leading-6 text-[#322d2a]">{{ $value ?: '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

