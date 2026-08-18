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

    <section class="bg-slate-50 py-10 md:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-[2rem] bg-slate-950 text-white shadow-xl">
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-orange-500/20 blur-3xl"></div>
                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>
                <div class="relative z-10 p-8 md:p-10">
                    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-300">Data Diri Pelamar</p>
                            <h1 class="mt-4 text-4xl font-black tracking-tight">{{ $profile?->full_name ?? '-' }}</h1>
                            <p class="mt-4 text-sm font-semibold text-slate-200">{{ $application->job?->title ?? '-' }} • {{ $application->application_number }}</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.applications.export.excel', ['keyword' => $profile?->email]) }}" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-black text-white transition hover:bg-emerald-700">Export Excel <span>↓</span></a>
                            <a href="{{ route('admin.applications.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/20 bg-white/10 px-5 py-3 text-sm font-black text-white transition hover:bg-white/20">← Kembali</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[0.38fr_1fr]">
                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-orange-500">Pass Foto</p>
                    <div class="mt-5">
                        @if($profile?->photo)
                            <img src="{{ asset('storage/'.$profile->photo) }}" alt="Foto {{ $profile->full_name }}" class="aspect-[3/4] w-full rounded-[1.5rem] object-cover ring-1 ring-slate-200">
                        @else
                            <div class="flex aspect-[3/4] w-full items-center justify-center rounded-[1.5rem] bg-orange-50 text-6xl font-black text-orange-500 ring-1 ring-orange-100">{{ strtoupper(substr($profile?->full_name ?? 'K', 0, 1)) }}</div>
                        @endif
                    </div>
                    <div class="mt-5 rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
                        <p class="text-xs font-black uppercase tracking-wide text-slate-400">Status Lamaran</p>
                        <p class="mt-1 font-black text-orange-600">{{ str($application->status)->headline() }}</p>
                    </div>
                </div>

                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-orange-500">Candidate Full Profile</p>
                            <h2 class="mt-2 text-2xl font-black text-slate-950">Semua Data Diri</h2>
                        </div>
                        <span class="rounded-full bg-orange-50 px-4 py-2 text-xs font-black text-orange-600 ring-1 ring-orange-100">{{ $profile?->profile_completion ?? 0 }}% Lengkap</span>
                    </div>

                    <div class="mt-7 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($identityFields as $label => $value)
                            <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
                                <p class="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400">{{ $label }}</p>
                                <p class="mt-2 break-words text-sm font-bold leading-6 text-slate-800">{{ $value ?: '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
