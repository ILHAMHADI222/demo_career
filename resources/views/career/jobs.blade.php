<x-layouts.app title="Lowongan - Career Indodata">

    {{-- HERO --}}
    <section class="relative overflow-hidden">
        <div
            class="relative bg-cover bg-center bg-no-repeat"
            style="background-image: url('https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=2000&q=85');"
        >
            <div class="absolute inset-0 bg-slate-950/65"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/70 to-slate-950/30"></div>

            <div class="absolute -left-24 top-20 h-72 w-72 rounded-full bg-orange-500/20 blur-3xl"></div>
            <div class="absolute right-10 top-12 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>

            <div class="relative z-10 mx-auto max-w-7xl px-4 py-20 sm:px-6 md:py-24 lg:px-8">
                <div class="max-w-3xl">

                    <span class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-[0.22em] text-orange-300 backdrop-blur">
                        Career Opportunities
                    </span>

                    <h1 class="mt-6 text-4xl font-black leading-tight tracking-tight text-white sm:text-5xl md:text-6xl">
                        Temukan posisi yang
                        <span class="text-orange-400">tepat untuk Anda.</span>
                    </h1>

                    <p class="mt-6 max-w-2xl text-base leading-8 text-slate-200 md:text-lg">
                        Jelajahi lowongan aktif di PT INDODATA SOLUSI PERSADA berdasarkan
                        divisi, lokasi, tipe pekerjaan, dan sistem kerja yang sesuai dengan
                        pengalaman serta tujuan karier Anda.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3 text-sm font-bold text-slate-200">
                        <span class="rounded-full border border-white/15 bg-white/10 px-4 py-2 backdrop-blur">
                            {{ $jobs->total() }} Lowongan
                        </span>

                        <span class="rounded-full border border-white/15 bg-white/10 px-4 py-2 backdrop-blur">
                            {{ $departments->count() }} Departemen
                        </span>

                        <span class="rounded-full border border-white/15 bg-white/10 px-4 py-2 backdrop-blur">
                            Proses Online
                        </span>
                    </div>

                </div>
            </div>
        </div>
    </section>


    {{-- CONTENT --}}
    <section class="bg-slate-50 py-12 md:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- FILTER --}}
            <div class="-mt-24 relative z-20 rounded-[2rem] border border-slate-200 bg-white p-6 shadow-2xl shadow-slate-300/30 md:p-8">

                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">

                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">
                            Cari Lowongan
                        </p>

                        <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950">
                            Filter Pencarian
                        </h2>

                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            Sesuaikan hasil lowongan berdasarkan preferensi Anda.
                        </p>
                    </div>

                    @if(request()->hasAny(['keyword', 'department', 'location', 'work_arrangement']))
                        <a
                            href="{{ route('jobs.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-black text-orange-500 transition hover:text-orange-600"
                        >
                            Hapus semua filter
                            <span>×</span>
                        </a>
                    @endif

                </div>


                <form method="GET" action="{{ route('jobs.index') }}" class="mt-7 grid gap-4 lg:grid-cols-12">

                    {{-- Keyword --}}
                    <div class="lg:col-span-4">
                        <label class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">
                            Keyword
                        </label>

                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                                ⌕
                            </span>

                            <input
                                type="text"
                                name="keyword"
                                value="{{ request('keyword') }}"
                                placeholder="Posisi, skill, atau kata kunci"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-semibold text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-100"
                            >
                        </div>
                    </div>


                    {{-- Department --}}
                    <div class="lg:col-span-3">
                        <label class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">
                            Departemen
                        </label>

                        <select
                            name="department"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-100"
                        >
                            <option value="">
                                Semua departemen
                            </option>

                            @foreach($departments as $department)
                                <option
                                    value="{{ $department->code }}"
                                    @selected(request('department') === $department->code)
                                >
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    {{-- Location --}}
                    <div class="lg:col-span-3">
                        <label class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">
                            Lokasi
                        </label>

                        <input
                            type="text"
                            name="location"
                            value="{{ request('location') }}"
                            placeholder="Jakarta, Bandung, Remote"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-100"
                        >
                    </div>


                    {{-- Work Arrangement --}}
                    <div class="lg:col-span-2">
                        <label class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">
                            Mode Kerja
                        </label>

                        <select
                            name="work_arrangement"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-100"
                        >
                            <option value="">
                                Semua mode
                            </option>

                            @foreach(['ONSITE', 'HYBRID', 'REMOTE'] as $v)
                                <option
                                    value="{{ $v }}"
                                    @selected(request('work_arrangement') === $v)
                                >
                                    {{ ucfirst(strtolower($v)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    {{-- Buttons --}}
                    <div class="flex flex-wrap gap-3 lg:col-span-12">

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-orange-500 px-6 py-3 text-sm font-black text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-orange-600"
                        >
                            Terapkan Filter
                            <span>→</span>
                        </button>

                        <a
                            href="{{ route('jobs.index') }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-3 text-sm font-black text-slate-700 transition hover:border-orange-200 hover:bg-orange-50 hover:text-orange-500"
                        >
                            Reset
                        </a>

                    </div>

                </form>

            </div>


            {{-- RESULT HEADER --}}
            <div class="mt-12 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">

                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">
                        Lowongan Tersedia
                    </p>

                    <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-950">
                        Peluang karier untuk Anda
                    </h2>

                    <p class="mt-2 text-sm text-slate-500">
                        Menampilkan {{ $jobs->count() }} dari {{ $jobs->total() }} lowongan.
                    </p>
                </div>

                <div class="text-sm font-bold text-slate-500">
                    Halaman {{ $jobs->currentPage() }} dari {{ $jobs->lastPage() }}
                </div>

            </div>


            {{-- JOB GRID --}}
            <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">

                @forelse($jobs as $job)

                    <article
                        class="group relative overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-orange-200 hover:shadow-2xl hover:shadow-slate-300/40"
                    >

                        {{-- Top bar --}}
                        <div class="h-1.5 w-full bg-gradient-to-r from-orange-500 via-amber-400 to-orange-500"></div>

                        <div class="p-6">

                            {{-- Badges --}}
                            <div class="flex flex-wrap items-start justify-between gap-3">

                                <span class="rounded-full bg-orange-50 px-3 py-1 text-[11px] font-black uppercase tracking-wide text-orange-600">
                                    {{ $job->department?->name ?? 'General' }}
                                </span>

                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-black uppercase tracking-wide text-emerald-700">
                                    Open
                                </span>

                            </div>


                            {{-- Title --}}
                            <h2 class="mt-5 text-xl font-black leading-snug text-slate-950 transition group-hover:text-orange-500">
                                {{ $job->title }}
                            </h2>


                            {{-- Meta --}}
                            <div class="mt-4 flex flex-wrap gap-2 text-xs font-bold text-slate-500">

                                <span class="rounded-full bg-slate-100 px-3 py-1.5">
                                    {{ $job->location }}
                                </span>

                                <span class="rounded-full bg-slate-100 px-3 py-1.5">
                                    {{ ucwords(str_replace('_', ' ', $job->employment_type)) }}
                                </span>

                                <span class="rounded-full bg-slate-100 px-3 py-1.5">
                                    {{ ucwords(str_replace('_', ' ', $job->work_arrangement)) }}
                                </span>

                            </div>


                            {{-- Description --}}
                            <p class="mt-5 line-clamp-3 text-sm leading-7 text-slate-600">
                                {{ $job->description }}
                            </p>


                            {{-- Deadline --}}
                            <div class="mt-6 rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-100">

                                <div class="flex items-center justify-between gap-3">

                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">
                                            Deadline
                                        </p>

                                        <p class="mt-1 text-sm font-black text-slate-700">
                                            {{ optional($job->deadline_at)->format('d M Y') ?? 'Flexible' }}
                                        </p>
                                    </div>

                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 text-orange-500">
                                        ◷
                                    </div>

                                </div>

                            </div>


                            {{-- Footer card --}}
                            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5">

                                <span class="text-xs font-black uppercase tracking-[0.18em] text-emerald-700">
                                    Open Application
                                </span>

                                <a
                                    href="{{ route('jobs.show', $job) }}"
                                    class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-black text-white transition duration-300 hover:bg-orange-500"
                                >
                                    Detail
                                    <span class="transition group-hover:translate-x-1">→</span>
                                </a>

                            </div>

                        </div>
                    </article>

                @empty

                    <div class="md:col-span-2 xl:col-span-3">

                        <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white px-6 py-16 text-center">

                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-50 text-2xl">
                                🔎
                            </div>

                            <h3 class="mt-5 text-xl font-black text-slate-950">
                                Lowongan tidak ditemukan
                            </h3>

                            <p class="mx-auto mt-3 max-w-lg text-sm leading-7 text-slate-500">
                                Tidak ada lowongan yang cocok dengan filter pencarian Anda.
                                Coba gunakan kata kunci atau filter yang berbeda.
                            </p>

                            <a
                                href="{{ route('jobs.index') }}"
                                class="mt-6 inline-flex rounded-2xl bg-orange-500 px-5 py-3 text-sm font-black text-white transition hover:bg-orange-600"
                            >
                                Reset Pencarian
                            </a>

                        </div>

                    </div>

                @endforelse

            </div>


            {{-- PAGINATION --}}
            @if($jobs->hasPages())
                <div class="mt-12 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    {{ $jobs->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </section>


    {{-- BOTTOM CTA --}}
    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="relative overflow-hidden rounded-[2rem] bg-slate-950 px-8 py-12 text-white shadow-2xl md:px-12 lg:flex lg:items-center lg:justify-between">

                <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-orange-500/20 blur-3xl"></div>
                <div class="absolute -bottom-24 left-20 h-56 w-56 rounded-full bg-amber-400/10 blur-3xl"></div>

                <div class="relative z-10 max-w-2xl">

                    <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-400">
                        Career Indodata
                    </p>

                    <h2 class="mt-3 text-3xl font-black tracking-tight md:text-4xl">
                        Belum menemukan posisi yang sesuai?
                    </h2>

                    <p class="mt-4 max-w-xl text-sm leading-7 text-slate-300">
                        Buat akun kandidat terlebih dahulu agar proses pendaftaran
                        lowongan berikutnya menjadi lebih cepat dan praktis.
                    </p>

                </div>

                <div class="relative z-10 mt-8 flex flex-wrap gap-3 lg:mt-0">

                    <a
                        href="{{ route('register') }}"
                        class="rounded-2xl bg-orange-500 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-orange-600"
                    >
                        Daftar Kandidat
                    </a>

                    <a
                        href="{{ route('career.home') }}"
                        class="rounded-2xl border border-white/15 bg-white/10 px-6 py-3.5 text-sm font-black text-white transition hover:bg-white/15"
                    >
                        Kembali ke Beranda
                    </a>

                </div>

            </div>

        </div>
    </section>

</x-layouts.app>