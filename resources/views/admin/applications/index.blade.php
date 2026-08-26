<x-layouts.app title="Daftar Pelamar - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">
            {{-- HERO --}}
            <div
                class="relative overflow-hidden rounded-[32px] sm:rounded-[36px] bg-cover bg-center text-white"
                style="background-image: url('https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1800&q=85');"
            >
                {{-- Overlay --}}
                <div class="absolute inset-0 bg-[#322d2a]/75"></div>
                <div class="absolute inset-0 bg-[#0f0e12]/55"></div>

                {{-- Decoration --}}
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#f97316]/20 blur-3xl"></div>
                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>

                {{-- Hero Content --}}
                <div class="relative z-10 p-6 sm:p-7 md:p-8">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3.5 py-1.5 text-xs font-normal uppercase tracking-[0.16em] text-white backdrop-blur">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#f97316]"></span>
                        HRD Applicant Tracking
                    </span>

                    <h1 class="mt-3.5 max-w-4xl text-2xl font-light leading-tight tracking-tight sm:text-3xl md:text-4xl text-white">
                        Kelola seluruh <span class="text-[#f97316]">pelamar dan proses seleksi.</span>
                    </h1>

                    <p class="mt-2.5 max-w-3xl text-xs sm:text-sm font-normal leading-relaxed text-white/75">
                        Lihat kandidat, posisi yang dilamar, divisi tujuan, status proses, dan detail lamaran dalam satu tampilan terpusat.
                    </p>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="relative z-20 mt-4 rounded-[28px] sm:rounded-[32px] border border-white bg-white p-4 sm:p-5">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-[#f97316]">
                            Filter Pelamar
                        </p>
                        <h2 class="mt-0.5 text-lg font-medium text-[#322d2a]">
                            Cari kandidat dengan cepat
                        </h2>
                    </div>

                    @if(request()->hasAny(['keyword', 'status', 'date_from', 'date_to']))
                        <a
                            href="{{ route('admin.applications.index') }}"
                            class="inline-flex items-center gap-1.5 rounded-full bg-[#fff3e8] px-3 py-1.5 text-xs font-normal text-[#f97316] transition hover:bg-[#ea580c] hover:text-white"
                        >
                            <span>Reset Filter ✕</span>
                        </a>
                    @endif
                </div>

                {{-- FORM FILTER --}}
                <form
                    method="GET"
                    action="{{ route('admin.applications.index') }}"
                    class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-[1fr_220px_160px_160px_auto] items-end"
                >
                    {{-- Keyword --}}
                    <div>
                        <label for="keyword" class="text-[10px] font-normal uppercase tracking-[0.14em] text-[#6f6b67]">
                            Keyword
                        </label>
                        <div class="relative mt-1">
                            <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                                <svg class="h-4 w-4 text-[#6f6b67]/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z" />
                                </svg>
                            </div>
                            <input
                                id="keyword"
                                type="text"
                                name="keyword"
                                value="{{ request('keyword') }}"
                                placeholder="Nama, email, lowongan, no. lamaran"
                                class="w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] py-2 pl-9 pr-3.5 text-xs font-normal text-[#322d2a] outline-none transition placeholder:text-[#6f6b67]/70 focus:border-[#f97316] focus:bg-white"
                            >
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="text-[10px] font-normal uppercase tracking-[0.14em] text-[#6f6b67]">
                            Status
                        </label>
                        <select
                            id="status"
                            name="status"
                            class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                        >
                            <option value="">Semua Status</option>
                            @foreach($statuses as $key => $status)
                                <option value="{{ $key }}" @selected(request('status') === $key)>
                                    {{ $status['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div>
                        <label for="date_from" class="text-[10px] font-normal uppercase tracking-[0.14em] text-[#6f6b67]">
                            Dari Tanggal
                        </label>
                        <input
                            id="date_from"
                            type="date"
                            name="date_from"
                            value="{{ request('date_from') }}"
                            class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                        >
                    </div>

                    {{-- Date To --}}
                    <div>
                        <label for="date_to" class="text-[10px] font-normal uppercase tracking-[0.14em] text-[#6f6b67]">
                            Sampai Tanggal
                        </label>
                        <input
                            id="date_to"
                            type="date"
                            name="date_to"
                            value="{{ request('date_to') }}"
                            class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                        >
                    </div>

                    {{-- Filter Button --}}
                    <div class="flex items-end">
                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center gap-1.5 rounded-full bg-[#f97316] px-5 py-2 text-xs font-normal text-white shadow-sm transition hover:bg-[#ea580c] lg:w-auto"
                        >
                            Filter <span>→</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- RESULT HEADER --}}
            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-[#f97316]">
                        Applicant List
                    </p>
                    <h2 class="mt-0.5 text-xl font-light text-[#322d2a] sm:text-2xl">
                        Daftar Pelamar
                    </h2>
                    <p class="mt-0.5 text-xs text-[#6f6b67]">
                        Menampilkan <span class="font-medium text-[#322d2a]">{{ $applications->count() }}</span> dari <span class="font-medium text-[#322d2a]">{{ $applications->total() }}</span> pelamar.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <a
                        href="{{ route('admin.applications.export.excel', request()->query()) }}"
                        class="inline-flex items-center gap-1.5 rounded-full bg-[#f97316] px-4 py-2 text-xs font-normal text-white shadow-sm transition hover:bg-[#ea580c]"
                    >
                        Export Excel <span>↓</span>
                    </a>

                    @if($applications->total() > 0)
                        <div class="inline-flex items-center gap-2 rounded-full border border-[#322d2a]/15 bg-white px-3.5 py-1.5 text-xs font-normal text-[#6f6b67]">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#f97316]"></span>
                            Hal {{ $applications->currentPage() }} / {{ $applications->lastPage() }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- DESKTOP TABLE --}}
            <div class="mt-4 hidden overflow-hidden rounded-[28px] sm:rounded-[32px] border border-white bg-white lg:block">
                {{-- Header --}}
                <div class="grid grid-cols-[1.25fr_1.1fr_0.9fr_0.8fr_auto] gap-4 bg-[#322d2a] px-5 py-3 text-[11px] font-normal uppercase tracking-[0.14em] text-white">
                    <div>Pelamar</div>
                    <div>Lowongan</div>
                    <div>Divisi</div>
                    <div>Status</div>
                    <div class="text-right">Aksi</div>
                </div>

                {{-- Rows --}}
                <div class="divide-y divide-[#322d2a]/10 text-xs">
                    @forelse($applications as $application)
                        @php
                            $candidateName = $application->candidateProfile?->full_name ?? 'Nama belum tersedia';
                            $candidateEmail = $application->candidateProfile?->email ?? '-';
                        @endphp
                        <div class="grid grid-cols-[1.25fr_1.1fr_0.9fr_0.8fr_auto] items-center gap-4 px-5 py-3.5 transition duration-150 hover:bg-[#fff8f3]">
                            {{-- Candidate --}}
                            <div class="flex min-w-0 items-center gap-3">
                                @if($application->candidateProfile?->photo)
                                    <img src="{{ asset('storage/'.$application->candidateProfile->photo) }}" alt="Foto {{ $candidateName }}" class="h-9 w-9 shrink-0 rounded-[12px] object-cover ring-1 ring-[#322d2a]/10">
                                @else
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[12px] bg-[#fff3e8] text-xs font-bold text-[#f97316] ring-1 ring-[#f97316]/20">
                                        {{ strtoupper(substr($candidateName, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="truncate font-medium text-[#322d2a]">{{ $candidateName }}</p>
                                    <p class="truncate text-[10px] text-[#6f6b67]">{{ $candidateEmail }}</p>
                                </div>
                            </div>

                            {{-- Job --}}
                            <div class="min-w-0">
                                <p class="truncate font-medium text-[#322d2a]">{{ $application->job?->title ?? '-' }}</p>
                                <p class="mt-0.5 text-[10px] uppercase tracking-wider text-[#6f6b67]">{{ $application->application_number }}</p>
                            </div>

                            {{-- Division --}}
                            <div>
                                <p class="font-medium text-[#322d2a]">{{ $application->job?->division?->name ?? '-' }}</p>
                                <p class="text-[10px] text-[#6f6b67]">{{ $application->job?->department?->name ?? 'General' }}</p>
                            </div>

                            {{-- Status --}}
                            <div>
                                @php $status = strtolower($application->status); @endphp
                                @if(in_array($status, ['hired', 'accepted']))
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-normal text-emerald-700 ring-1 ring-emerald-200">
                                        {{ str($application->status)->headline() }}
                                    </span>
                                @elseif(in_array($status, ['rejected', 'declined']))
                                    <span class="inline-flex rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-normal text-red-600 ring-1 ring-red-200">
                                        {{ str($application->status)->headline() }}
                                    </span>
                                @elseif(in_array($status, ['interview', 'technical_test', 'test']))
                                    <span class="inline-flex rounded-full bg-violet-50 px-2.5 py-1 text-[11px] font-normal text-violet-700 ring-1 ring-violet-200">
                                        {{ str($application->status)->headline() }}
                                    </span>
                                @elseif(in_array($status, ['offering', 'offer']))
                                    <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-normal text-blue-700 ring-1 ring-blue-200">
                                        {{ str($application->status)->headline() }}
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-[#fff3e8] px-2.5 py-1 text-[11px] font-normal text-[#f97316] ring-1 ring-[#f97316]/20">
                                        {{ str($application->status)->headline() }}
                                    </span>
                                @endif
                            </div>

                            {{-- Action --}}
                            <div class="text-right">
                                <a
                                    href="{{ route('admin.applications.show', $application) }}"
                                    class="inline-flex items-center rounded-full bg-[#f0efe9] px-3.5 py-1.5 text-[11px] font-medium text-[#322d2a] ring-1 ring-[#322d2a]/15 transition hover:bg-[#f97316] hover:text-white hover:ring-[#f97316]"
                                >
                                    Detail →
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-[#6f6b67]">
                            <p class="text-base font-medium text-[#322d2a]">Belum ada pelamar.</p>
                            <p class="mt-1 text-xs">Data pelamar akan muncul otomatis setelah kandidat mengirimkan lamaran.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- MOBILE / TABLET CARDS --}}
            <div class="mt-4 space-y-3 lg:hidden">
                @forelse($applications as $application)
                    @php
                        $candidateName = $application->candidateProfile?->full_name ?? 'Nama belum tersedia';
                        $candidateEmail = $application->candidateProfile?->email ?? '-';
                        $status = strtolower($application->status);
                    @endphp
                    <div class="rounded-[22px] border border-white bg-white p-4 text-xs">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex min-w-0 items-center gap-3">
                                @if($application->candidateProfile?->photo)
                                    <img src="{{ asset('storage/'.$application->candidateProfile->photo) }}" alt="Foto {{ $candidateName }}" class="h-9 w-9 shrink-0 rounded-[12px] object-cover ring-1 ring-[#322d2a]/10">
                                @else
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[12px] bg-[#fff3e8] text-xs font-bold text-[#f97316] ring-1 ring-[#f97316]/20">
                                        {{ strtoupper(substr($candidateName, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="truncate font-medium text-[#322d2a]">{{ $candidateName }}</p>
                                    <p class="truncate text-[10px] text-[#6f6b67]">{{ $candidateEmail }}</p>
                                </div>
                            </div>

                            @if(in_array($status, ['hired', 'accepted']))
                                <span class="shrink-0 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-normal text-emerald-700">
                                    {{ str($application->status)->headline() }}
                                </span>
                            @elseif(in_array($status, ['rejected', 'declined']))
                                <span class="shrink-0 rounded-full bg-red-50 px-2.5 py-0.5 text-[10px] font-normal text-red-600">
                                    {{ str($application->status)->headline() }}
                                </span>
                            @else
                                <span class="shrink-0 rounded-full bg-[#fff3e8] px-2.5 py-0.5 text-[10px] font-normal text-[#f97316]">
                                    {{ str($application->status)->headline() }}
                                </span>
                            @endif
                        </div>

                        <div class="mt-3 flex items-center justify-between border-t border-[#322d2a]/10 pt-3">
                            <div>
                                <p class="font-medium text-[#322d2a]">{{ $application->job?->title ?? '-' }}</p>
                                <p class="text-[10px] text-[#6f6b67]">{{ $application->application_number }} • {{ $application->job?->department?->name ?? 'General' }}</p>
                            </div>
                            <a
                                href="{{ route('admin.applications.show', $application) }}"
                                class="inline-flex items-center rounded-full bg-[#322d2a] px-3 py-1.5 text-[11px] font-medium text-white transition hover:bg-[#f97316]"
                            >
                                Detail →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[22px] border border-dashed border-[#322d2a]/20 bg-white p-6 text-center text-xs text-[#6f6b67]">
                        <p class="font-medium text-[#322d2a]">Belum ada pelamar</p>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            @if($applications->hasPages())
                <div class="mt-5 rounded-[20px] border border-white bg-white p-3.5">
                    {{ $applications->withQueryString()->links() }}
                </div>
            @endif

            {{-- BOTTOM INFO --}}
            <div class="mt-6 rounded-[28px] sm:rounded-[32px] border border-white bg-white p-5 sm:p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-[#f97316]">
                            Recruitment Management
                        </p>
                        <h3 class="mt-0.5 text-lg font-light text-[#322d2a]">
                            Kelola proses kandidat secara terpusat
                        </h3>
                        <p class="mt-1 text-xs text-[#6f6b67]">
                            Buka detail pelamar untuk melihat informasi lengkap kandidat, riwayat, dan proses seleksi.
                        </p>
                    </div>

                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex w-fit items-center gap-1.5 rounded-full border border-[#322d2a]/15 bg-[#f0efe9] px-4 py-2 text-xs font-normal text-[#322d2a] transition hover:bg-[#fff3e8] hover:text-[#f97316]"
                    >
                        ← Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
