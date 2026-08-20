<x-layouts.app title="Daftar Pelamar - Career Indodata">

    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-28 sm:px-6 lg:px-8">

        <div class="mx-auto max-w-[1240px]">

            {{-- ========================================================= --}}
            {{-- HERO --}}
            {{-- ========================================================= --}}
            <div
                class="relative overflow-hidden rounded-[56px] bg-cover bg-center text-white "
                style="background-image: url('https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1800&q=85');"
            >

                {{-- Overlay --}}
                <div class="absolute inset-0 bg-[#322d2a]/70"></div>

                <div class="absolute inset-0 bg-[#0f0e12]/55"></div>


                {{-- Decoration --}}
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#f97316]/20 blur-3xl"></div>

                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>


                {{-- Hero Content --}}
                <div class="relative z-10 p-8 md:p-10 lg:p-12">

                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-normal uppercase tracking-[0.22em] text-white backdrop-blur">

                        <span class="h-2 w-2 rounded-full bg-[#f97316]"></span>

                        HRD Applicant Tracking

                    </span>


                    <h1 class="mt-5 max-w-4xl text-4xl font-normal leading-tight tracking-tight md:text-5xl">

                        Kelola seluruh

                        <span class="text-[#f97316]">
                            pelamar dan proses seleksi.
                        </span>

                    </h1>


                    <p class="mt-5 max-w-3xl text-sm font-normal leading-7 text-white/75 md:text-base">

                        Lihat kandidat, posisi yang dilamar, divisi tujuan,
                        status proses, jumlah dokumen, dan detail lamaran
                        dalam satu tampilan terpusat.

                    </p>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- FILTER --}}
            {{-- ========================================================= --}}
            <div class="relative z-20 mt-6 rounded-[40px] border border-white bg-white p-5  md:p-6">

                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">

                    <div>

                        <p class="text-xs font-normal uppercase tracking-[0.22em] text-[#f97316]">
                            Filter Pelamar
                        </p>

                        <h2 class="mt-2 text-xl font-normal text-[#322d2a] md:text-2xl">
                            Cari kandidat dengan cepat
                        </h2>

                        <p class="mt-1 text-sm leading-6 text-[#6f6b67]">
                            Gunakan keyword, status, dan rentang tanggal untuk mempersempit hasil pencarian.
                        </p>

                    </div>


                    @if(request()->hasAny(['keyword', 'status', 'date_from', 'date_to']))

                        <a
                            href="{{ route('admin.applications.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-normal text-[#f97316] transition hover:text-[#f97316]"
                        >
                            Reset Filter

                            <span>
                                ×
                            </span>
                        </a>

                    @endif

                </div>


                {{-- FORM FILTER --}}
                <form
                    method="GET"
                    action="{{ route('admin.applications.index') }}"
                    class="mt-6 grid gap-4 xl:grid-cols-[1fr_260px_190px_190px_auto]"
                >

                    {{-- Keyword --}}
                    <div>

                        <label
                            for="keyword"
                            class="text-xs font-normal uppercase tracking-[0.16em] text-[#6f6b67]"
                        >
                            Keyword
                        </label>


                        <div class="relative mt-2">

                            {{-- Search Icon --}}
                            <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center">

                                <svg
                                    class="h-5 w-5 text-[#6f6b67]/70"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                                    />
                                </svg>

                            </div>


                            <input
                                id="keyword"
                                type="text"
                                name="keyword"
                                value="{{ request('keyword') }}"
                                placeholder="Nama, email, lowongan, nomor lamaran"
                                class="w-full rounded-[24px] border border-white bg-[#f0efe9] py-3 pl-12 pr-4 text-sm font-normal text-[#322d2a] outline-none transition placeholder:text-[#6f6b67]/70 focus:border-[#f97316] focus:bg-white focus:ring-4 focus:ring-[#f97316]/20"
                            >

                        </div>

                    </div>


                    {{-- Status --}}
                    <div>

                        <label
                            for="status"
                            class="text-xs font-normal uppercase tracking-[0.16em] text-[#6f6b67]"
                        >
                            Status
                        </label>


                        <select
                            id="status"
                            name="status"
                            class="mt-2 w-full rounded-[24px] border border-white bg-[#f0efe9] px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white focus:ring-4 focus:ring-[#f97316]/20"
                        >

                            <option value="">
                                Semua Status
                            </option>

                            @foreach($statuses as $key => $status)

                                <option
                                    value="{{ $key }}"
                                    @selected(request('status') === $key)
                                >
                                    {{ $status['label'] }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Date From --}}
                    <div>

                        <label
                            for="date_from"
                            class="text-xs font-normal uppercase tracking-[0.16em] text-[#6f6b67]"
                        >
                            Dari Tanggal
                        </label>


                        <input
                            id="date_from"
                            type="date"
                            name="date_from"
                            value="{{ request('date_from') }}"
                            class="mt-2 w-full rounded-[24px] border border-white bg-[#f0efe9] px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white focus:ring-4 focus:ring-[#f97316]/20"
                        >

                    </div>


                    {{-- Date To --}}
                    <div>

                        <label
                            for="date_to"
                            class="text-xs font-normal uppercase tracking-[0.16em] text-[#6f6b67]"
                        >
                            Sampai Tanggal
                        </label>


                        <input
                            id="date_to"
                            type="date"
                            name="date_to"
                            value="{{ request('date_to') }}"
                            class="mt-2 w-full rounded-[24px] border border-white bg-[#f0efe9] px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white focus:ring-4 focus:ring-[#f97316]/20"
                        >

                    </div>


                    {{-- Filter Button --}}
                    <div class="flex items-end">

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-[24px] bg-[#f97316] px-6 py-3 text-sm font-normal text-white  transition duration-300  hover:bg-[#ea580c] lg:w-auto"
                        >

                            Filter

                            <span>
                                →
                            </span>

                        </button>

                    </div>

                </form>

            </div>


            {{-- ========================================================= --}}
            {{-- RESULT HEADER --}}
            {{-- ========================================================= --}}
            <div class="mt-12 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">

                <div>

                    <p class="text-xs font-normal uppercase tracking-[0.22em] text-[#f97316]">
                        Applicant List
                    </p>

                    <h2 class="mt-2 text-2xl font-normal tracking-tight text-[#322d2a] md:text-3xl">
                        Daftar Pelamar
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-[#6f6b67]">

                        Menampilkan

                        <span class="font-normal text-[#322d2a]">
                            {{ $applications->count() }}
                        </span>

                        dari

                        <span class="font-normal text-[#322d2a]">
                            {{ $applications->total() }}
                        </span>

                        pelamar.

                    </p>

                    <p class="mt-1 text-xs font-normal text-[#6f6b67]/70">
                        @if(request('date_from') || request('date_to'))
                            Filter tanggal:
                            {{ request('date_from') ? \Illuminate\Support\Carbon::parse(request('date_from'))->format('d M Y') : 'awal data' }}
                            -
                            {{ request('date_to') ? \Illuminate\Support\Carbon::parse(request('date_to'))->format('d M Y') : 'akhir data' }}.
                        @else
                            Export default akan mengambil semua data pelamar.
                        @endif
                    </p>

                </div>


                <div class="flex flex-wrap gap-3">
                    <a
                        href="{{ route('admin.applications.export.excel', request()->query()) }}"
                        class="inline-flex w-fit items-center gap-2 rounded-[24px] bg-[#f97316] px-5 py-3 text-sm font-normal text-white transition hover:bg-[#ea580c]"
                    >
                        Export Excel
                        <span>↓</span>
                    </a>

                @if($applications->total() > 0)

                    <div class="inline-flex w-fit items-center gap-2 rounded-[24px] border border-white bg-white px-4 py-2.5 text-sm font-normal text-[#6f6b67]">

                        <span class="h-2 w-2 rounded-full bg-[#f97316]"></span>

                        Halaman {{ $applications->currentPage() }}
                        dari {{ $applications->lastPage() }}

                    </div>

                @endif
                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- DESKTOP TABLE --}}
            {{-- ========================================================= --}}
            <div class="mt-6 hidden overflow-hidden rounded-[40px] border border-white bg-white  lg:block">

                {{-- Header --}}
                <div class="grid grid-cols-[1.25fr_1.1fr_0.9fr_0.8fr_auto] gap-4 bg-[#322d2a] px-6 py-4 text-xs font-normal uppercase tracking-[0.14em] text-white">

                    <div>
                        Pelamar
                    </div>

                    <div>
                        Lowongan
                    </div>

                    <div>
                        Divisi
                    </div>

                    <div>
                        Status
                    </div>

                    <!-- <div>
                        Berkas
                    </div> -->

                    <div>
                        Aksi
                    </div>

                </div>


                {{-- Rows --}}
                <div class="divide-y divide-[#322d2a]/10">

                    @forelse($applications as $application)

                        @php
                            $candidateName =
                                $application->candidateProfile?->full_name
                                ?? 'Nama belum tersedia';

                            $candidateEmail =
                                $application->candidateProfile?->email
                                ?? '-';

                            $documentCount =
                                $application->candidateProfile?->documents?->count()
                                ?? 0;
                        @endphp


                        <div class="grid grid-cols-[1.25fr_1.1fr_0.9fr_0.8fr_auto] items-center gap-4 px-6 py-5 transition duration-200 hover:bg-[#f0efe9]">

                            {{-- Candidate --}}
                            <div class="flex min-w-0 items-start gap-3">

                                @if($application->candidateProfile?->photo)
                                    <img src="{{ asset('storage/'.$application->candidateProfile->photo) }}" alt="Foto {{ $candidateName }}" class="h-14 w-14 shrink-0 rounded-[16px] object-cover ring-1 ring-[#f97316]/20">
                                @else
                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-[16px] bg-[#fff3e8] text-sm font-normal text-[#f97316] ring-1 ring-[#f97316]/20">

                                        {{ strtoupper(substr($candidateName, 0, 1)) }}

                                    </div>
                                @endif


                                <div class="min-w-0">

                                    <p class="truncate text-sm font-normal text-[#322d2a]">
                                        {{ $candidateName }}
                                    </p>

                                    <p class="mt-1 truncate text-xs font-normal text-[#6f6b67]">
                                        {{ $candidateEmail }}
                                    </p>

                                </div>

                            </div>


                            {{-- Job --}}
                            <div class="min-w-0">

                                <p class="text-sm font-normal text-[#322d2a]">
                                    {{ $application->job?->title ?? '-' }}
                                </p>

                                <p class="mt-1 text-[10px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]/70">
                                    {{ $application->application_number }}
                                </p>

                            </div>


                            {{-- Division --}}
                            <div>

                                <p class="text-sm font-normal text-[#322d2a]">
                                    {{ $application->job?->division?->name ?? '-' }}
                                </p>

                                <p class="mt-1 text-xs font-normal text-[#6f6b67]">
                                    {{ $application->job?->department?->name ?? 'General' }}
                                </p>

                            </div>


                            {{-- Status --}}
                            <div>

                                @php
                                    $status = strtolower($application->status);
                                @endphp


                                @if(in_array($status, ['hired', 'accepted']))

                                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-normal text-emerald-700 ring-1 ring-emerald-100">

                                        {{ str($application->status)->headline() }}

                                    </span>


                                @elseif(in_array($status, ['rejected', 'declined']))

                                    <span class="inline-flex rounded-full bg-red-50 px-3 py-1.5 text-xs font-normal text-red-600 ring-1 ring-red-100">

                                        {{ str($application->status)->headline() }}

                                    </span>


                                @elseif(in_array($status, ['interview', 'technical_test', 'test']))

                                    <span class="inline-flex rounded-full bg-violet-50 px-3 py-1.5 text-xs font-normal text-violet-700 ring-1 ring-violet-100">

                                        {{ str($application->status)->headline() }}

                                    </span>


                                @elseif(in_array($status, ['offering', 'offer']))

                                    <span class="inline-flex rounded-full bg-blue-50 px-3 py-1.5 text-xs font-normal text-blue-700 ring-1 ring-blue-100">

                                        {{ str($application->status)->headline() }}

                                    </span>


                                @else

                                    <span class="inline-flex rounded-full bg-[#fff3e8] px-3 py-1.5 text-xs font-normal text-[#f97316] ring-1 ring-[#f97316]/20">

                                        {{ str($application->status)->headline() }}

                                    </span>

                                @endif

                            </div>


                            <!-- {{-- Documents --}}
                            <div>

                                @if($documentCount > 0)

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-normal text-emerald-700 ring-1 ring-emerald-100">

                                        {{ $documentCount }}

                                        PDF

                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-[#f0efe9] px-3 py-1.5 text-xs font-normal text-[#6f6b67]">

                                        0 PDF

                                    </span>

                                @endif

                            </div> -->


                            {{-- Action --}}
                            <div>

                                <a
                                    href="{{ route('admin.applications.show', $application) }}"
                                    class="group inline-flex items-center gap-2 rounded-[16px] bg-[#322d2a] px-4 py-2.5 text-sm font-normal text-white transition duration-300 hover:bg-[#f97316]"
                                >

                                    Detail

                                    <span class="transition-transform group-hover:translate-x-1">
                                        →
                                    </span>

                                </a>

                            </div>

                        </div>


                    @empty

                        <div class="px-6 py-16 text-center">

                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-[24px] bg-[#fff3e8] text-[#f97316]">

                                <svg
                                    class="h-7 w-7"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"
                                    />
                                </svg>

                            </div>


                            <h3 class="mt-5 text-xl font-normal text-[#322d2a]">
                                Belum ada pelamar
                            </h3>


                            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#6f6b67]">

                                Data pelamar akan muncul secara otomatis setelah
                                kandidat mengirimkan lamaran.

                            </p>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- MOBILE / TABLET CARDS --}}
            {{-- ========================================================= --}}
            <div class="mt-6 space-y-4 lg:hidden">

                @forelse($applications as $application)

                    @php
                        $candidateName =
                            $application->candidateProfile?->full_name
                            ?? 'Nama belum tersedia';

                        $candidateEmail =
                            $application->candidateProfile?->email
                            ?? '-';

                        $documentCount =
                            $application->candidateProfile?->documents?->count()
                            ?? 0;

                        $status = strtolower($application->status);
                    @endphp


                    <div class="rounded-[28px] border border-white bg-white p-5 ">

                        {{-- Candidate Header --}}
                        <div class="flex items-start justify-between gap-4">

                            <div class="flex min-w-0 gap-3">

                                @if($application->candidateProfile?->photo)
                                    <img src="{{ asset('storage/'.$application->candidateProfile->photo) }}" alt="Foto {{ $candidateName }}" class="h-12 w-12 shrink-0 rounded-[16px] object-cover ring-1 ring-[#f97316]/20">
                                @else
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-[16px] bg-[#f97316] text-sm font-normal text-white">

                                        {{ strtoupper(substr($candidateName, 0, 1)) }}

                                    </div>
                                @endif


                                <div class="min-w-0">

                                    <p class="truncate font-normal text-[#322d2a]">
                                        {{ $candidateName }}
                                    </p>

                                    <p class="mt-1 truncate text-xs font-normal text-[#6f6b67]">
                                        {{ $candidateEmail }}
                                    </p>

                                </div>

                            </div>


                            {{-- Mobile Status --}}
                            @if(in_array($status, ['hired', 'accepted']))

                                <span class="shrink-0 rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-normal text-emerald-700">
                                    {{ str($application->status)->headline() }}
                                </span>

                            @elseif(in_array($status, ['rejected', 'declined']))

                                <span class="shrink-0 rounded-full bg-red-50 px-3 py-1 text-[10px] font-normal text-red-600">
                                    {{ str($application->status)->headline() }}
                                </span>

                            @else

                                <span class="shrink-0 rounded-full bg-[#fff3e8] px-3 py-1 text-[10px] font-normal text-[#f97316]">
                                    {{ str($application->status)->headline() }}
                                </span>

                            @endif

                        </div>


                        {{-- Information --}}
                        <div class="mt-5 grid gap-4 border-t border-[#322d2a]/10 pt-5 sm:grid-cols-2">

                            {{-- Job --}}
                            <div>

                                <p class="text-[10px] font-normal uppercase tracking-[0.15em] text-[#6f6b67]/70">
                                    Lowongan
                                </p>

                                <p class="mt-1 text-sm font-normal text-[#322d2a]">
                                    {{ $application->job?->title ?? '-' }}
                                </p>

                                <p class="mt-1 text-xs font-normal text-[#6f6b67]">
                                    {{ $application->application_number }}
                                </p>

                            </div>


                            {{-- Division --}}
                            <div>

                                <p class="text-[10px] font-normal uppercase tracking-[0.15em] text-[#6f6b67]/70">
                                    Divisi
                                </p>

                                <p class="mt-1 text-sm font-normal text-[#322d2a]">
                                    {{ $application->job?->division?->name ?? '-' }}
                                </p>

                                <p class="mt-1 text-xs font-normal text-[#6f6b67]">
                                    {{ $application->job?->department?->name ?? 'General' }}
                                </p>

                            </div>

                        </div>


                        {{-- Card Footer --}}
                        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-[#322d2a]/10 pt-5">

                            @if($documentCount > 0)

                                <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-normal text-emerald-700 ring-1 ring-emerald-100">

                                    {{ $documentCount }} PDF

                                </span>

                            @else

                                <span class="rounded-full bg-[#f0efe9] px-3 py-1.5 text-xs font-normal text-[#6f6b67]">

                                    0 PDF

                                </span>

                            @endif


                            <a
                                href="{{ route('admin.applications.show', $application) }}"
                                class="group inline-flex items-center gap-2 rounded-[16px] bg-[#322d2a] px-4 py-2.5 text-sm font-normal text-white transition hover:bg-[#f97316]"
                            >

                                Detail Pelamar

                                <span class="transition-transform group-hover:translate-x-1">
                                    →
                                </span>

                            </a>

                        </div>

                    </div>


                @empty

                    <div class="rounded-[28px] border border-dashed border-[#322d2a]/20 bg-white px-6 py-12 text-center">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-[24px] bg-[#fff3e8] text-[#f97316]">

                            <svg
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"
                                />
                            </svg>

                        </div>

                        <h3 class="mt-4 text-lg font-normal text-[#322d2a]">
                            Belum ada pelamar
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-[#6f6b67]">
                            Data pelamar akan muncul setelah kandidat mengirim lamaran.
                        </p>

                    </div>

                @endforelse

            </div>


            {{-- ========================================================= --}}
            {{-- PAGINATION --}}
            {{-- ========================================================= --}}
            @if($applications->hasPages())

                <div class="mt-8 rounded-[24px] border border-white bg-white p-4 ">

                    {{ $applications->withQueryString()->links() }}

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- BOTTOM INFO --}}
            {{-- ========================================================= --}}
            <div class="mt-8 rounded-[40px] border border-white bg-white p-6  md:p-8">

                <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

                    <div>

                        <p class="text-xs font-normal uppercase tracking-[0.22em] text-[#f97316]">
                            Recruitment Management
                        </p>

                        <h3 class="mt-2 text-xl font-normal text-[#322d2a]">
                            Kelola proses kandidat secara terpusat
                        </h3>

                        <p class="mt-2 max-w-2xl text-sm leading-6 text-[#6f6b67]">
                            Buka detail pelamar untuk melihat informasi kandidat,
                            dokumen yang diunggah, dan progres proses rekrutmen.
                        </p>

                    </div>


                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex w-fit items-center gap-2 rounded-[24px] border border-[#322d2a]/10 bg-[#f0efe9] px-5 py-3 text-sm font-normal text-[#322d2a] transition hover:border-[#f97316]/30 hover:bg-[#fff3e8] hover:text-[#f97316]"
                    >

                        ←

                        Kembali ke Dashboard

                    </a>

                </div>

            </div>

        </div>

    </section>

</x-layouts.app>
