<x-layouts.app title="Daftar Pelamar - Career Indodata">

    <section class="min-h-screen bg-slate-50 py-10 md:py-14">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- ========================================================= --}}
            {{-- HERO --}}
            {{-- ========================================================= --}}
            <div
                class="relative overflow-hidden rounded-[2rem] bg-cover bg-center text-white shadow-2xl shadow-slate-300/30"
                style="background-image: url('https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1800&q=85');"
            >

                {{-- Overlay --}}
                <div class="absolute inset-0 bg-slate-950/70"></div>

                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/75 to-orange-950/30"></div>


                {{-- Decoration --}}
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-orange-500/20 blur-3xl"></div>

                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>


                {{-- Hero Content --}}
                <div class="relative z-10 p-8 md:p-10 lg:p-12">

                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-[0.22em] text-orange-300 backdrop-blur">

                        <span class="h-2 w-2 rounded-full bg-orange-400"></span>

                        HRD Applicant Tracking

                    </span>


                    <h1 class="mt-5 max-w-4xl text-4xl font-black leading-tight tracking-tight md:text-5xl">

                        Kelola seluruh

                        <span class="text-orange-400">
                            pelamar dan proses seleksi.
                        </span>

                    </h1>


                    <p class="mt-5 max-w-3xl text-sm font-semibold leading-7 text-slate-200 md:text-base">

                        Lihat kandidat, posisi yang dilamar, divisi tujuan,
                        status proses, jumlah dokumen, dan detail lamaran
                        dalam satu tampilan terpusat.

                    </p>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- FILTER --}}
            {{-- ========================================================= --}}
            <div class="relative z-20 mt-6 rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-xl shadow-slate-300/20 md:p-6">

                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">

                    <div>

                        <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">
                            Filter Pelamar
                        </p>

                        <h2 class="mt-2 text-xl font-black text-slate-950 md:text-2xl">
                            Cari kandidat dengan cepat
                        </h2>

                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            Gunakan keyword, status, dan rentang tanggal untuk mempersempit hasil pencarian.
                        </p>

                    </div>


                    @if(request()->hasAny(['keyword', 'status', 'date_from', 'date_to']))

                        <a
                            href="{{ route('admin.applications.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-black text-orange-500 transition hover:text-orange-600"
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
                            class="text-xs font-black uppercase tracking-[0.16em] text-slate-500"
                        >
                            Keyword
                        </label>


                        <div class="relative mt-2">

                            {{-- Search Icon --}}
                            <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center">

                                <svg
                                    class="h-5 w-5 text-slate-400"
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
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-sm font-semibold text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-100"
                            >

                        </div>

                    </div>


                    {{-- Status --}}
                    <div>

                        <label
                            for="status"
                            class="text-xs font-black uppercase tracking-[0.16em] text-slate-500"
                        >
                            Status
                        </label>


                        <select
                            id="status"
                            name="status"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-100"
                        >

                            <option value="">
                                Semua Status
                            </option>

                            @foreach($statuses as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(request('status') === $status)
                                >
                                    {{ str($status)->headline() }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Date From --}}
                    <div>

                        <label
                            for="date_from"
                            class="text-xs font-black uppercase tracking-[0.16em] text-slate-500"
                        >
                            Dari Tanggal
                        </label>


                        <input
                            id="date_from"
                            type="date"
                            name="date_from"
                            value="{{ request('date_from') }}"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-100"
                        >

                    </div>


                    {{-- Date To --}}
                    <div>

                        <label
                            for="date_to"
                            class="text-xs font-black uppercase tracking-[0.16em] text-slate-500"
                        >
                            Sampai Tanggal
                        </label>


                        <input
                            id="date_to"
                            type="date"
                            name="date_to"
                            value="{{ request('date_to') }}"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-100"
                        >

                    </div>


                    {{-- Filter Button --}}
                    <div class="flex items-end">

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-orange-500 px-6 py-3 text-sm font-black text-white shadow-lg shadow-orange-500/20 transition duration-300 hover:-translate-y-0.5 hover:bg-orange-600 lg:w-auto"
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

                    <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">
                        Applicant List
                    </p>

                    <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950 md:text-3xl">
                        Daftar Pelamar
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">

                        Menampilkan

                        <span class="font-black text-slate-700">
                            {{ $applications->count() }}
                        </span>

                        dari

                        <span class="font-black text-slate-700">
                            {{ $applications->total() }}
                        </span>

                        pelamar.

                    </p>

                    <p class="mt-1 text-xs font-bold text-slate-400">
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
                        class="inline-flex w-fit items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-emerald-700"
                    >
                        Export Excel
                        <span>↓</span>
                    </a>

                @if($applications->total() > 0)

                    <div class="inline-flex w-fit items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-black text-slate-600 shadow-sm">

                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                        Halaman {{ $applications->currentPage() }}
                        dari {{ $applications->lastPage() }}

                    </div>

                @endif
                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- DESKTOP TABLE --}}
            {{-- ========================================================= --}}
            <div class="mt-6 hidden overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm lg:block">

                {{-- Header --}}
                <div class="grid grid-cols-[1.25fr_1.1fr_0.9fr_0.8fr_0.65fr_auto] gap-4 bg-slate-950 px-6 py-4 text-xs font-black uppercase tracking-[0.14em] text-white">

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
                <div class="divide-y divide-slate-200">

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


                        <div class="grid grid-cols-[1.25fr_1.1fr_0.9fr_0.8fr_0.65fr_auto] items-center gap-4 px-6 py-5 transition duration-200 hover:bg-slate-50">

                            {{-- Candidate --}}
                            <div class="flex min-w-0 items-start gap-3">

                                @if($application->candidateProfile?->photo)
                                    <img src="{{ asset('storage/'.$application->candidateProfile->photo) }}" alt="Foto {{ $candidateName }}" class="h-14 w-14 shrink-0 rounded-xl object-cover ring-1 ring-orange-100">
                                @else
                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-sm font-black text-orange-600 ring-1 ring-orange-100">

                                        {{ strtoupper(substr($candidateName, 0, 1)) }}

                                    </div>
                                @endif


                                <div class="min-w-0">

                                    <p class="truncate text-sm font-black text-slate-950">
                                        {{ $candidateName }}
                                    </p>

                                    <p class="mt-1 truncate text-xs font-semibold text-slate-500">
                                        {{ $candidateEmail }}
                                    </p>

                                </div>

                            </div>


                            {{-- Job --}}
                            <div class="min-w-0">

                                <p class="text-sm font-black text-slate-800">
                                    {{ $application->job?->title ?? '-' }}
                                </p>

                                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400">
                                    {{ $application->application_number }}
                                </p>

                            </div>


                            {{-- Division --}}
                            <div>

                                <p class="text-sm font-black text-slate-800">
                                    {{ $application->job?->division?->name ?? '-' }}
                                </p>

                                <p class="mt-1 text-xs font-semibold text-slate-500">
                                    {{ $application->job?->department?->name ?? 'General' }}
                                </p>

                            </div>


                            {{-- Status --}}
                            <div>

                                @php
                                    $status = strtolower($application->status);
                                @endphp


                                @if(in_array($status, ['hired', 'accepted']))

                                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-black text-emerald-700 ring-1 ring-emerald-100">

                                        {{ str($application->status)->headline() }}

                                    </span>


                                @elseif(in_array($status, ['rejected', 'declined']))

                                    <span class="inline-flex rounded-full bg-red-50 px-3 py-1.5 text-xs font-black text-red-600 ring-1 ring-red-100">

                                        {{ str($application->status)->headline() }}

                                    </span>


                                @elseif(in_array($status, ['interview', 'technical_test', 'test']))

                                    <span class="inline-flex rounded-full bg-violet-50 px-3 py-1.5 text-xs font-black text-violet-700 ring-1 ring-violet-100">

                                        {{ str($application->status)->headline() }}

                                    </span>


                                @elseif(in_array($status, ['offering', 'offer']))

                                    <span class="inline-flex rounded-full bg-blue-50 px-3 py-1.5 text-xs font-black text-blue-700 ring-1 ring-blue-100">

                                        {{ str($application->status)->headline() }}

                                    </span>


                                @else

                                    <span class="inline-flex rounded-full bg-orange-50 px-3 py-1.5 text-xs font-black text-orange-600 ring-1 ring-orange-100">

                                        {{ str($application->status)->headline() }}

                                    </span>

                                @endif

                            </div>


                            <!-- {{-- Documents --}}
                            <div>

                                @if($documentCount > 0)

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-black text-emerald-700 ring-1 ring-emerald-100">

                                        {{ $documentCount }}

                                        PDF

                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1.5 text-xs font-black text-slate-500">

                                        0 PDF

                                    </span>

                                @endif

                            </div> -->


                            {{-- Action --}}
                            <div>

                                <a
                                    href="{{ route('admin.applications.show', $application) }}"
                                    class="group inline-flex items-center gap-2 rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-black text-white transition duration-300 hover:bg-orange-500"
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

                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-50 text-orange-500">

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


                            <h3 class="mt-5 text-xl font-black text-slate-950">
                                Belum ada pelamar
                            </h3>


                            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">

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


                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm">

                        {{-- Candidate Header --}}
                        <div class="flex items-start justify-between gap-4">

                            <div class="flex min-w-0 gap-3">

                                @if($application->candidateProfile?->photo)
                                    <img src="{{ asset('storage/'.$application->candidateProfile->photo) }}" alt="Foto {{ $candidateName }}" class="h-12 w-12 shrink-0 rounded-xl object-cover ring-1 ring-orange-100">
                                @else
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-orange-500 text-sm font-black text-white">

                                        {{ strtoupper(substr($candidateName, 0, 1)) }}

                                    </div>
                                @endif


                                <div class="min-w-0">

                                    <p class="truncate font-black text-slate-950">
                                        {{ $candidateName }}
                                    </p>

                                    <p class="mt-1 truncate text-xs font-semibold text-slate-500">
                                        {{ $candidateEmail }}
                                    </p>

                                </div>

                            </div>


                            {{-- Mobile Status --}}
                            @if(in_array($status, ['hired', 'accepted']))

                                <span class="shrink-0 rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-black text-emerald-700">
                                    {{ str($application->status)->headline() }}
                                </span>

                            @elseif(in_array($status, ['rejected', 'declined']))

                                <span class="shrink-0 rounded-full bg-red-50 px-3 py-1 text-[10px] font-black text-red-600">
                                    {{ str($application->status)->headline() }}
                                </span>

                            @else

                                <span class="shrink-0 rounded-full bg-orange-50 px-3 py-1 text-[10px] font-black text-orange-600">
                                    {{ str($application->status)->headline() }}
                                </span>

                            @endif

                        </div>


                        {{-- Information --}}
                        <div class="mt-5 grid gap-4 border-t border-slate-100 pt-5 sm:grid-cols-2">

                            {{-- Job --}}
                            <div>

                                <p class="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400">
                                    Lowongan
                                </p>

                                <p class="mt-1 text-sm font-black text-slate-800">
                                    {{ $application->job?->title ?? '-' }}
                                </p>

                                <p class="mt-1 text-xs font-semibold text-slate-500">
                                    {{ $application->application_number }}
                                </p>

                            </div>


                            {{-- Division --}}
                            <div>

                                <p class="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400">
                                    Divisi
                                </p>

                                <p class="mt-1 text-sm font-black text-slate-800">
                                    {{ $application->job?->division?->name ?? '-' }}
                                </p>

                                <p class="mt-1 text-xs font-semibold text-slate-500">
                                    {{ $application->job?->department?->name ?? 'General' }}
                                </p>

                            </div>

                        </div>


                        {{-- Card Footer --}}
                        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5">

                            @if($documentCount > 0)

                                <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-black text-emerald-700 ring-1 ring-emerald-100">

                                    {{ $documentCount }} PDF

                                </span>

                            @else

                                <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-black text-slate-500">

                                    0 PDF

                                </span>

                            @endif


                            <a
                                href="{{ route('admin.applications.show', $application) }}"
                                class="group inline-flex items-center gap-2 rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-black text-white transition hover:bg-orange-500"
                            >

                                Detail Pelamar

                                <span class="transition-transform group-hover:translate-x-1">
                                    →
                                </span>

                            </a>

                        </div>

                    </div>


                @empty

                    <div class="rounded-[1.5rem] border border-dashed border-slate-300 bg-white px-6 py-12 text-center">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-50 text-orange-500">

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

                        <h3 class="mt-4 text-lg font-black text-slate-950">
                            Belum ada pelamar
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Data pelamar akan muncul setelah kandidat mengirim lamaran.
                        </p>

                    </div>

                @endforelse

            </div>


            {{-- ========================================================= --}}
            {{-- PAGINATION --}}
            {{-- ========================================================= --}}
            @if($applications->hasPages())

                <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

                    {{ $applications->withQueryString()->links() }}

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- BOTTOM INFO --}}
            {{-- ========================================================= --}}
            <div class="mt-8 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">

                <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

                    <div>

                        <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">
                            Recruitment Management
                        </p>

                        <h3 class="mt-2 text-xl font-black text-slate-950">
                            Kelola proses kandidat secara terpusat
                        </h3>

                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                            Buka detail pelamar untuk melihat informasi kandidat,
                            dokumen yang diunggah, dan progres proses rekrutmen.
                        </p>

                    </div>


                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex w-fit items-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-black text-slate-700 transition hover:border-orange-200 hover:bg-orange-50 hover:text-orange-600"
                    >

                        ←

                        Kembali ke Dashboard

                    </a>

                </div>

            </div>

        </div>

    </section>

</x-layouts.app>