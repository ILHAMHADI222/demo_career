<x-layouts.app title="Dashboard HRD - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px] space-y-6">
            <div class="grid gap-6 lg:grid-cols-[1.08fr_0.92fr] lg:items-stretch">
                <div class="rounded-[36px] border border-white bg-white p-6 sm:p-7 lg:p-8">
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        HRD Dashboard
                    </div>

                    <h1 class="mt-4 max-w-3xl text-[32px] font-light leading-tight tracking-[0.32px] text-[#322d2a] md:text-[42px]">
                        Ringkasan pelamar yang rapi dan mudah dipantau.
                    </h1>

                    <p class="mt-3 max-w-2xl text-xs sm:text-sm font-normal leading-[1.6] text-[#6f6b67]">
                        Fokus pada angka penting, progress rekrutmen, dan kandidat terbaru tanpa tampilan yang terlalu padat.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-2.5">
                        <a href="{{ route('admin.applications.index') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#f97316] px-5 py-3 text-xs font-normal text-white shadow-sm transition hover:bg-[#ea580c]">
                            Kelola Pelamar
                            <span>→</span>
                        </a>
                        <a href="{{ route('admin.disc.assessments.index') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-[#322d2a]/30 bg-transparent px-5 py-3 text-xs font-normal text-[#322d2a] transition hover:border-[#f97316] hover:bg-[#fff3e8] hover:text-[#f97316]">
                            Asesmen Psikotes
                            <span>→</span>
                        </a>
                        <a href="{{ route('admin.disc.banks.index') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-[#322d2a]/30 bg-transparent px-5 py-3 text-xs font-normal text-[#322d2a] transition hover:border-[#f97316] hover:bg-[#fff3e8] hover:text-[#f97316]">
                            Bank Soal
                            <span>→</span>
                        </a>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-[36px] bg-[#322d2a] p-6 text-white sm:p-8">
                    <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-[#f97316]/25 blur-3xl"></div>
                    <div class="absolute -bottom-24 left-8 h-56 w-56 rounded-full bg-[#f97316]/10 blur-3xl"></div>

                    <div class="relative z-10 flex h-full min-h-[280px] flex-col justify-between">
                        <div class="inline-flex w-fit items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3.5 py-1.5 text-xs font-normal uppercase tracking-[0.12em] text-white backdrop-blur-md">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                            Total Pelamar
                        </div>

                        <div>
                            <p class="text-[48px] font-light leading-none tracking-[0.48px] text-white md:text-[56px]">{{ $totalApplicants }}</p>
                            <p class="mt-2 text-xs sm:text-sm font-normal text-white/70">
                                {{ $newApplicants }} pelamar baru hari ini.
                            </p>

                            <div class="mt-5 grid grid-cols-2 gap-3">
                                <div class="rounded-[20px] border border-white/15 bg-white/10 p-3.5 backdrop-blur-md">
                                    <p class="text-[11px] font-normal uppercase tracking-[0.12em] text-white/60">Interview</p>
                                    <p class="mt-1.5 text-xl font-light text-white">{{ $interview }}</p>
                                </div>
                                <div class="rounded-[20px] border border-white/15 bg-white/10 p-3.5 backdrop-blur-md">
                                    <p class="text-[11px] font-normal uppercase tracking-[0.12em] text-white/60">Lowongan</p>
                                    <p class="mt-1.5 text-xl font-light text-white">{{ $activeJobs }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-3.5 sm:grid-cols-2 xl:grid-cols-5">
                @foreach([
                    ['label' => 'Lowongan Aktif', 'value' => $activeJobs, 'icon' => 'briefcase'],
                    ['label' => 'Total Kandidat', 'value' => $totalApplicants, 'icon' => 'users'],
                    ['label' => 'Baru Hari Ini', 'value' => $newApplicants, 'icon' => 'sparkles'],
                    ['label' => 'Interview', 'value' => $interview, 'icon' => 'chat'],
                    ['label' => 'Admin HRD', 'value' => $totalAdmin, 'icon' => 'shield'],
                ] as $item)
                    <div class="rounded-[24px] border border-white bg-white p-4 transition hover:border-[#f97316]/30 hover:bg-[#fff3e8]">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[11px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">{{ $item['label'] }}</p>
                                <p class="mt-2 text-2xl font-light tracking-[0.24px] text-[#322d2a]">{{ $item['value'] }}</p>
                            </div>
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[16px] bg-[#fff3e8] text-[#f97316] ring-1 ring-[#f97316]/20">
                                @if($item['icon'] === 'briefcase')
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25A2.25 2.25 0 0118 20.65H6A2.25 2.25 0 013.75 18.4v-4.25m16.5 0A2.25 2.25 0 0018 11.9H6a2.25 2.25 0 00-2.25 2.25m16.5 0v-3.9A2.25 2.25 0 0018 8H6a2.25 2.25 0 00-2.25 2.25v3.9M9 8V6.75A2.25 2.25 0 0111.25 4.5h1.5A2.25 2.25 0 0115 6.75V8" /></svg>
                                @elseif($item['icon'] === 'users')
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21C6.432 21 4.35 20.445 2.546 19.447a4.125 4.125 0 017.533-2.493M12 11.25a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm6.75 0a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                                @elseif($item['icon'] === 'sparkles')
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.25 7.5L18 8.25l-.25-.75a2.25 2.25 0 00-1.5-1.5L15.5 5.75l.75-.25a2.25 2.25 0 001.5-1.5L18 3.25l.25.75a2.25 2.25 0 001.5 1.5l.75.25-.75.25a2.25 2.25 0 00-1.5 1.5z" /></svg>
                                @elseif($item['icon'] === 'chat')
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.17 48.17 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" /></svg>
                                @else
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.75A11.959 11.959 0 0112 2.714z" /></svg>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
                <div class="rounded-[36px] border border-white bg-white p-5 sm:p-6">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]"><span class="h-1.5 w-1.5 bg-[#f97316]"></span>Pipeline</p>
                            <h2 class="mt-1 text-[24px] font-light leading-none tracking-[0.24px] text-[#322d2a]">Progress Rekrutmen</h2>
                        </div>
                        <span class="rounded-full bg-[#f0efe9] px-3.5 py-1 text-xs font-normal text-[#6f6b67] ring-1 ring-[#322d2a]/10">Ringkas</span>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
                        @foreach([
                            ['label' => 'Review', 'value' => $documentReview, 'icon' => 'document'],
                            ['label' => 'Psikotes / Test', 'value' => $test, 'icon' => 'clipboard'],
                            ['label' => 'Offering', 'value' => $offering, 'icon' => 'mail'],
                            ['label' => 'Hired', 'value' => $hired, 'icon' => 'check'],
                            ['label' => 'Rejected', 'value' => $rejected, 'icon' => 'x'],
                        ] as $stage)
                            <div class="rounded-[20px] bg-[#f0efe9] p-3.5 ring-1 ring-[#322d2a]/10">
                                <div class="flex h-8 w-8 items-center justify-center rounded-[12px] bg-white text-[#f97316] ring-1 ring-[#f97316]/20">
                                    @if($stage['icon'] === 'document')
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-.988-2.386l-4.751-4.751A3.375 3.375 0 0011.375 3.5H7.5A2.25 2.25 0 005.25 5.75v12.5A2.25 2.25 0 007.5 20.5h9.75a2.25 2.25 0 002.25-2.25v-4z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75v4.5A2.25 2.25 0 0014.25 10.5h4.5" /></svg>
                                    @elseif($stage['icon'] === 'clipboard')
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5.25h6m-6 4.5h6m-6 4.5h3M9.75 3.75h4.5A1.5 1.5 0 0115.75 5.25v.75h.75A2.25 2.25 0 0118.75 8.25v9A2.25 2.25 0 0116.5 19.5h-9a2.25 2.25 0 01-2.25-2.25v-9A2.25 2.25 0 017.5 6h.75v-.75a1.5 1.5 0 011.5-1.5z" /></svg>
                                    @elseif($stage['icon'] === 'mail')
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 7.5A2.25 2.25 0 016 5.25h12a2.25 2.25 0 012.25 2.25v9A2.25 2.25 0 0118 18.75H6a2.25 2.25 0 01-2.25-2.25v-9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.875l6.4 4.267a2 2 0 002.2 0l6.4-4.267" /></svg>
                                    @elseif($stage['icon'] === 'check')
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l5 5 10-11" /></svg>
                                    @else
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    @endif
                                </div>
                                <p class="mt-2.5 text-[11px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">{{ $stage['label'] }}</p>
                                <p class="mt-1 text-xl font-light text-[#322d2a]">{{ $stage['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-[36px] border border-white bg-white p-5 sm:p-6">
                    <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]"><span class="h-1.5 w-1.5 bg-[#f97316]"></span>Tahapan HRD</p>
                    <h2 class="mt-1 text-[24px] font-light leading-none tracking-[0.24px] text-[#322d2a]">Pilihan Status</h2>
                    <p class="mt-2 text-xs text-[#6f6b67]">Alur tahapan recruitment resmi.</p>

                    <div class="mt-4 flex flex-wrap gap-1.5">
                        @foreach($statusOptions as $label)
                            <div class="inline-flex items-center gap-1.5 rounded-full bg-[#fff3e8] px-3 py-1 text-xs font-normal text-[#322d2a] ring-1 ring-[#f97316]/20">
                                <span class="h-1.5 w-1.5 rounded-full bg-[#f97316]"></span>
                                {{ $label }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- PELAMAR TERBARU & DAFTAR KANDIDAT WITH FILTER & PAGINATION --}}
            <div class="overflow-hidden rounded-[36px] border border-white bg-white">
                <div class="flex flex-col gap-4 border-b border-[#322d2a]/10 p-5 sm:p-6 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>Pelamar Terbaru
                        </p>
                        <h2 class="mt-1 text-[26px] font-light leading-none tracking-[0.26px] text-[#322d2a]">Daftar Kandidat</h2>
                        <p class="mt-1 text-xs text-[#6f6b67]">
                            Menampilkan {{ $applications->count() }} dari total {{ $applications->total() }} kandidat pelamar.
                        </p>
                    </div>

                    <a href="{{ route('admin.applications.index') }}" class="inline-flex w-fit items-center gap-2 rounded-full bg-[#322d2a] px-4 py-2 text-xs font-normal text-white transition hover:bg-[#0f0e12]">
                        Halaman Kelola Lengkap
                        <span>→</span>
                    </a>
                </div>

                {{-- FILTER SECTION --}}
                <div class="border-b border-[#322d2a]/10 bg-[#f0efe9]/50 p-4 sm:p-5">
                    <form method="GET" action="{{ route('dashboard') }}" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-12 items-end">
                        <div class="lg:col-span-4">
                            <label class="text-[10px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Cari Pelamar / No. Lamaran</label>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Nama, No. Lamaran, Email, WA..."
                                class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white px-3.5 py-2 text-xs text-[#322d2a] outline-none transition placeholder:text-[#8b8b8b] focus:border-[#f97316]"
                            >
                        </div>

                        <div class="lg:col-span-3">
                            <label class="text-[10px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Posisi Lowongan</label>
                            <select
                                name="job_id"
                                class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white px-3.5 py-2 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316]"
                            >
                                <option value="">Semua Posisi</option>
                                @foreach($jobsList as $j)
                                    <option value="{{ $j->id }}" @selected(request('job_id') == $j->id)>{{ $j->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="lg:col-span-3">
                            <label class="text-[10px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Status Lamaran</label>
                            <select
                                name="status"
                                class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white px-3.5 py-2 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316]"
                            >
                                <option value="">Semua Status</option>
                                @foreach($statusOptions as $st => $lbl)
                                    <option value="{{ $st }}" @selected(request('status') === $st)>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-2 lg:col-span-2">
                            <button
                                type="submit"
                                class="inline-flex flex-1 items-center justify-center rounded-full bg-[#f97316] px-4 py-2 text-xs font-normal text-white shadow-sm transition hover:bg-[#ea580c]"
                            >
                                Filter
                            </button>
                            @if(request()->hasAny(['search', 'job_id', 'status']))
                                <a
                                    href="{{ route('dashboard') }}"
                                    class="inline-flex items-center justify-center rounded-full border border-[#322d2a]/20 bg-white px-3 py-2 text-xs font-normal text-[#322d2a] transition hover:bg-[#f0efe9]"
                                    title="Reset filter"
                                >
                                    ✕
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- DESKTOP COMPACT TABLE --}}
                <div class="hidden overflow-x-auto lg:block">
                    <table class="w-full min-w-[980px] text-left">
                        <thead class="bg-[#f0efe9] text-[11px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">
                            <tr>
                                <th class="px-5 py-3.5">Kandidat</th>
                                <th class="px-5 py-3.5">Posisi Dilamar</th>
                                <th class="px-5 py-3.5">Tgl Daftar</th>
                                <th class="px-5 py-3.5">Kontak</th>
                                <th class="px-5 py-3.5">Status</th>
                                <th class="px-5 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#322d2a]/10 text-xs">
                            @forelse ($applications as $application)
                                @php
                                    $profile = $application->candidateProfile;
                                @endphp
                                <tr class="transition hover:bg-[#fff8f3]">
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            @if($profile?->photo_url)
                                                <img src="{{ $profile->photo_url }}" alt="Foto {{ $profile->full_name }}" class="h-9 w-9 rounded-[12px] object-cover ring-1 ring-[#322d2a]/10 shrink-0" onerror="this.onerror=null; this.src='{{ $profile->avatar_url }}';">
                                            @elseif($profile?->avatar_url)
                                                <img src="{{ $profile->avatar_url }}" alt="Foto {{ $profile->full_name }}" class="h-9 w-9 rounded-[12px] object-cover ring-1 ring-[#322d2a]/10 shrink-0">
                                            @else
                                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[12px] bg-[#fff3e8] text-xs font-bold text-[#f97316] ring-1 ring-[#f97316]/20">
                                                    {{ strtoupper(substr($profile?->full_name ?? 'K', 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-[#322d2a]">{{ $profile?->full_name ?? '-' }}</p>
                                                <p class="text-[10px] text-[#6f6b67] tracking-wider">{{ $application->application_number }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <p class="font-medium text-[#322d2a]">{{ $application->job?->title ?? '-' }}</p>
                                        <p class="text-[10px] text-[#6f6b67]">{{ $application->job?->department?->name ?? 'General' }}</p>
                                    </td>
                                    <td class="px-5 py-3.5 text-[#6f6b67]">
                                        {{ $application->submitted_at?->format('d M Y') ?? $application->created_at?->format('d M Y') }}
                                    </td>
                                    <td class="px-5 py-3.5 text-[#6f6b67]">
                                        <p class="font-medium text-[#322d2a]">{{ $profile?->email ?? '-' }}</p>
                                        <p class="text-[10px]">{{ $profile?->whatsapp ?? $profile?->phone ?? '-' }}</p>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <form method="POST" action="{{ route('admin.applications.status', $application) }}" class="flex items-center gap-1.5">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="rounded-[12px] border border-[#322d2a]/15 bg-white px-2 py-1 text-[11px] font-normal text-[#322d2a] focus:border-[#f97316] focus:outline-none">
                                                @foreach($statusOptions as $stCode => $stLabel)
                                                    <option value="{{ $stCode }}" @selected($application->status === $stCode)>{{ $stLabel }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="rounded-[12px] bg-[#f97316] px-2.5 py-1 text-[10px] font-semibold text-white shadow-xs transition hover:bg-[#ea580c]">Update</button>
                                        </form>
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <a href="{{ route('admin.applications.show', $application) }}" class="inline-flex items-center rounded-full bg-[#f0efe9] px-3 py-1 text-[11px] font-medium text-[#322d2a] ring-1 ring-[#322d2a]/15 transition hover:bg-[#f97316] hover:text-white hover:ring-[#f97316]">
                                            Detail →
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center text-[#6f6b67]">
                                        <p class="text-base font-medium text-[#322d2a]">Tidak ada kandidat pelamar yang cocok.</p>
                                        <p class="mt-1 text-xs">Coba sesuaikan filter atau kata kunci pencarian.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE VIEW --}}
                <div class="grid gap-3 p-4 lg:hidden">
                    @forelse ($applications as $application)
                        @php
                            $profile = $application->candidateProfile;
                        @endphp
                        <div class="rounded-[22px] border border-[#322d2a]/10 bg-[#f0efe9] p-4 text-xs">
                            <div class="flex items-start gap-3">
                                @if($profile?->photo_url)
                                    <img src="{{ $profile->photo_url }}" alt="Foto {{ $profile->full_name }}" class="h-10 w-10 rounded-[14px] object-cover ring-1 ring-[#322d2a]/10 shrink-0" onerror="this.onerror=null; this.src='{{ $profile->avatar_url }}';">
                                @elseif($profile?->avatar_url)
                                    <img src="{{ $profile->avatar_url }}" alt="Foto {{ $profile->full_name }}" class="h-10 w-10 rounded-[14px] object-cover ring-1 ring-[#322d2a]/10 shrink-0">
                                @else
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[14px] bg-[#fff3e8] text-xs font-bold text-[#f97316] ring-1 ring-[#f97316]/20">
                                        {{ strtoupper(substr($profile?->full_name ?? 'K', 0, 1)) }}
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-[#322d2a]">{{ $profile?->full_name ?? '-' }}</p>
                                    <p class="text-[10px] text-[#6f6b67]">{{ $application->job?->title ?? '-' }} • {{ $application->submitted_at?->format('d M Y') ?? $application->created_at?->format('d M Y') }}</p>
                                    <p class="mt-0.5 text-[10px] text-[#6f6b67]">{{ $profile?->whatsapp ?? $profile?->phone ?? '-' }}</p>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('admin.applications.status', $application) }}" class="mt-3 grid gap-2">
                                @csrf
                                @method('PUT')
                                <div class="flex items-center gap-2">
                                    <select name="status" class="flex-1 rounded-[12px] border border-[#322d2a]/15 bg-white px-2.5 py-1.5 text-xs text-[#322d2a] focus:border-[#f97316]">
                                        @foreach($statusOptions as $stCode => $stLabel)
                                            <option value="{{ $stCode }}" @selected($application->status === $stCode)>{{ $stLabel }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="rounded-[12px] bg-[#f97316] px-3 py-1.5 text-xs font-medium text-white shadow-xs transition hover:bg-[#ea580c]">Update</button>
                                    <a href="{{ route('admin.applications.show', $application) }}" class="rounded-[12px] border border-[#322d2a]/20 bg-white px-3 py-1.5 text-center text-xs font-medium text-[#322d2a] transition hover:bg-[#f0efe9]">Detail</a>
                                </div>
                            </form>
                        </div>
                    @empty
                        <div class="rounded-[22px] border border-dashed border-[#322d2a]/25 p-6 text-center text-xs text-[#6f6b67]">
                            <p class="text-sm font-medium text-[#322d2a]">Tidak ada kandidat pelamar yang cocok.</p>
                        </div>
                    @endforelse
                </div>

                {{-- PAGINATION --}}
                @if($applications->hasPages())
                    <div class="border-t border-[#322d2a]/10 bg-white p-4">
                        {{ $applications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layouts.app>
