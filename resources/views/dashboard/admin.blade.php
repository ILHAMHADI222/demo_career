<x-layouts.app title="Dashboard HRD - Career Indodata">
    <section class="bg-slate-50 py-10 md:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-[2rem] bg-slate-950 text-white shadow-2xl shadow-slate-300/30">
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-orange-500/20 blur-3xl"></div>
                <div class="absolute -right-20 bottom-0 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>
                <div class="relative z-10 p-8 md:p-10 lg:p-12">
                    <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-3xl">
                            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-[0.22em] text-orange-300 backdrop-blur">
                                <span class="h-2 w-2 rounded-full bg-orange-400"></span>
                                HRD Dashboard
                            </span>
                            <h1 class="mt-5 text-4xl font-black leading-tight tracking-tight md:text-5xl">
                                Data lengkap pelamar <span class="text-orange-400">dalam satu tabel.</span>
                            </h1>
                            <p class="mt-5 max-w-2xl text-sm leading-7 text-slate-200 md:text-base">
                                Pantau semua pelamar, data diri, foto, pendidikan, pengalaman kerja, dan update status seleksi seperti lolos administrasi, psikotesa, atau interview user.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.applications.index') }}" class="inline-flex items-center gap-2 rounded-2xl bg-orange-500 px-5 py-3 text-sm font-black text-white shadow-lg shadow-orange-500/25 transition hover:-translate-y-0.5 hover:bg-orange-600">
                                Kelola Pelamar <span>→</span>
                            </a>
                            <a href="{{ route('admin.jobs.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/20 bg-white/10 px-5 py-3 text-sm font-black text-white backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/20">
                                Kelola Lowongan <span>→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-5">
                @foreach([
                    ['label' => 'Lowongan Aktif', 'value' => $activeJobs, 'bg' => 'bg-blue-50', 'text' => 'text-blue-700'],
                    ['label' => 'Total Kandidat', 'value' => $totalApplicants, 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700'],
                    ['label' => 'Pelamar Baru Hari Ini', 'value' => $newApplicants, 'bg' => 'bg-amber-50', 'text' => 'text-amber-700'],
                    ['label' => 'Sedang Interview', 'value' => $interview, 'bg' => 'bg-violet-50', 'text' => 'text-violet-700'],
                    ['label' => 'Admin HRD', 'value' => $totalAdmin, 'bg' => 'bg-slate-100', 'text' => 'text-slate-700'],
                ] as $index => $item)
                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">{{ $item['label'] }}</p>
                                <p class="mt-4 text-4xl font-black tracking-tight text-slate-950">{{ $item['value'] }}</p>
                            </div>
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $item['bg'] }} {{ $item['text'] }} text-xs font-black">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">Recruitment Funnel</p>
                    <h2 class="mt-2 text-2xl font-black text-slate-950">Pipeline Rekrutmen</h2>
                    <div class="mt-7 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach([
                            ['label' => 'Review Dokumen', 'value' => $documentReview],
                            ['label' => 'Psikotesa / Test', 'value' => $test],
                            ['label' => 'Offering', 'value' => $offering],
                            ['label' => 'Hired', 'value' => $hired],
                            ['label' => 'Rejected', 'value' => $rejected],
                        ] as $stage)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <p class="text-sm font-black text-slate-700">{{ $stage['label'] }}</p>
                                <p class="mt-3 text-3xl font-black text-slate-950">{{ $stage['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">Status Cepat</p>
                    <h2 class="mt-2 text-2xl font-black text-slate-950">Pilihan Status HRD</h2>
                    <div class="mt-6 flex flex-wrap gap-2">
                        @foreach($statusOptions as $label)
                            <span class="rounded-full bg-orange-50 px-3 py-1 text-[11px] font-black text-orange-700 ring-1 ring-orange-100">{{ $label }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-8 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">HRD Applicant Tracking</p>
                        <h2 class="mt-2 text-2xl font-black text-slate-950">Full List Data Pelamar</h2>
                        <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">HRD dapat melihat data diri lengkap, foto, pendidikan, pengalaman, posisi yang dilamar, serta mengubah status pelamar langsung dari tabel.</p>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-2xl bg-slate-50 px-5 py-3 text-sm font-black text-slate-700 ring-1 ring-slate-200">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        {{ $allApplications->count() }} total pelamar
                    </div>
                </div>

                <div class="mt-7 overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="min-w-[1800px] w-full divide-y divide-slate-200 text-left">
                        <thead class="bg-slate-950 text-xs font-black uppercase tracking-[0.16em] text-white">
                            <tr>
                                <th class="px-4 py-4">Foto</th>
                                <th class="px-4 py-4">Apply Date</th>
                                <th class="px-4 py-4">Position Applied</th>
                                <th class="px-4 py-4">Candidate Name</th>
                                <th class="px-4 py-4">Gender</th>
                                <th class="px-4 py-4">NIK</th>
                                <th class="px-4 py-4">TTL</th>
                                <th class="px-4 py-4">Alamat</th>
                                <th class="px-4 py-4">Kontak</th>
                                <th class="px-4 py-4">Pendidikan</th>
                                <th class="px-4 py-4">Pengalaman</th>
                                <th class="px-4 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse ($allApplications as $application)
                                @php
                                    $profile = $application->candidateProfile;
                                    $education = $profile?->educations?->first();
                                    $experience = $profile?->experiences?->first();
                                @endphp
                                <tr class="align-top transition hover:bg-slate-50">
                                    <td class="px-4 py-5">
                                        @if($profile?->photo)
                                            <img src="{{ asset('storage/'.$profile->photo) }}" alt="Foto {{ $profile->full_name }}" class="h-16 w-16 rounded-2xl object-cover ring-2 ring-orange-100">
                                        @else
                                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-50 text-lg font-black text-orange-600">{{ strtoupper(substr($profile?->full_name ?? 'K', 0, 1)) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-5 text-sm font-bold text-slate-700">
                                        {{ $application->submitted_at?->format('d M Y') ?? $application->created_at?->format('d M Y') }}
                                        <p class="mt-1 text-[10px] font-black uppercase tracking-wide text-slate-400">{{ $application->application_number }}</p>
                                    </td>
                                    <td class="px-4 py-5 text-sm font-black text-slate-900">{{ $application->job?->title ?? '-' }}<p class="mt-1 text-xs font-semibold text-slate-500">{{ $application->job?->department?->name ?? '-' }}</p></td>
                                    <td class="px-4 py-5 text-sm text-slate-700"><p class="font-black text-slate-950">{{ $profile?->full_name ?? '-' }}</p><p class="mt-1 text-xs font-semibold text-slate-500">{{ $profile?->marital_status ?? '-' }}</p></td>
                                    <td class="px-4 py-5 text-sm font-semibold text-slate-700">{{ $profile?->gender ?? '-' }}</td>
                                    <td class="px-4 py-5 text-sm font-semibold text-slate-700">{{ $profile?->nik ?? '-' }}</td>
                                    <td class="px-4 py-5 text-sm text-slate-700"><p>{{ $profile?->birth_place ?? '-' }}</p><p class="mt-1 font-semibold">{{ $profile?->birth_date?->format('d M Y') ?? '-' }}</p></td>
                                    <td class="px-4 py-5 text-sm leading-6 text-slate-700"><p class="min-w-64">{{ $profile?->address ?? '-' }}</p><p>RT/RW: {{ $profile?->rt_rw ?? '-' }}</p><p>{{ $profile?->village ?? '-' }}, {{ $profile?->district ?? '-' }}</p><p>{{ $profile?->city ?? '-' }}, {{ $profile?->province ?? '-' }} {{ $profile?->postal_code ?? '' }}</p></td>
                                    <td class="px-4 py-5 text-sm leading-6 text-slate-700"><p>Email: {{ $profile?->email ?? '-' }}</p><p>Telp: {{ $profile?->phone ?? '-' }}</p><p>WA: {{ $profile?->whatsapp ?? '-' }}</p></td>
                                    <td class="px-4 py-5 text-sm leading-6 text-slate-700"><p class="font-black text-slate-900">{{ $education?->education_level ?? '-' }}</p><p>{{ $education?->institution ?? '-' }}</p><p>{{ $education?->major ?? '-' }} | IPK: {{ $education?->ipk ?? '-' }}</p><p>Skill/Sertifikasi: {{ $education?->certification ?? '-' }}</p><p>SIM: {{ $profile?->driving_license ?? '-' }}</p></td>
                                    <td class="px-4 py-5 text-sm leading-6 text-slate-700"><p class="font-black text-slate-900">{{ $experience?->company_name ?? '-' }}</p><p>{{ $experience?->position ?? '-' }}</p><p>{{ $experience?->work_years ?? '-' }}</p></td>
                                    <td class="px-4 py-5">
                                        <form method="POST" action="{{ route('admin.applications.status', $application) }}" class="min-w-56 space-y-3">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="w-full rounded-xl border-slate-200 text-xs font-bold text-slate-700 focus:border-orange-500 focus:ring-orange-500">
                                                @foreach($statusOptions as $status => $label)
                                                    <option value="{{ $status }}" @selected($application->status === $status)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" name="note" placeholder="Catatan opsional" class="w-full rounded-xl border-slate-200 text-xs font-semibold focus:border-orange-500 focus:ring-orange-500">
                                            <button type="submit" class="w-full rounded-xl bg-orange-500 px-3 py-2 text-xs font-black text-white transition hover:bg-orange-600">Update Status</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="px-5 py-12 text-center">
                                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-50 text-xl">👥</div>
                                        <p class="mt-4 text-lg font-black text-slate-950">Belum ada pelamar masuk.</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-600">Pelamar akan muncul otomatis setelah kandidat mengirim lamaran.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
