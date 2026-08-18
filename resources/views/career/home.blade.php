<x-layouts.app title="Career Indodata">

    {{-- HERO --}}
    <section class="relative isolate overflow-hidden">
        <div
            class="relative min-h-[720px] bg-cover bg-center bg-no-repeat"
            style="background-image: url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=2000&q=85');"
        >
            {{-- Overlay --}}
            <div class="absolute inset-0 bg-slate-950/60"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/65 to-slate-950/20"></div>

            {{-- Decorative glow --}}
            <div class="absolute -left-24 top-24 h-72 w-72 rounded-full bg-orange-500/20 blur-3xl"></div>
            <div class="absolute right-10 top-20 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>

            <div class="relative z-10 mx-auto flex min-h-[720px] max-w-7xl items-center px-4 py-20 sm:px-6 lg:px-8">
                <div class="grid w-full gap-12 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">

                    {{-- LEFT CONTENT --}}
                    <div class="max-w-3xl">

                        <span class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-[0.22em] text-orange-300 backdrop-blur-md">
                            Career Portal PT Indodata
                        </span>

                        <h1 class="mt-6 text-4xl font-black leading-tight tracking-tight text-white sm:text-5xl md:text-6xl lg:text-7xl">
                            Temukan peluang.
                            <span class="block text-orange-400">
                                Bangun masa depan.
                            </span>
                        </h1>

                        <p class="mt-6 max-w-2xl text-base leading-8 text-slate-200 sm:text-lg">
                            Bergabung bersama PT INDODATA SOLUSI PERSADA dan temukan peluang karier
                            yang sesuai dengan kemampuan, pengalaman, dan tujuan profesional Anda.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <a
                                href="{{ route('jobs.index') }}"
                                class="inline-flex items-center gap-3 rounded-2xl bg-orange-500 px-7 py-4 text-sm font-black text-white shadow-xl shadow-orange-500/30 transition duration-300 hover:-translate-y-1 hover:bg-orange-600"
                            >
                                Lihat Lowongan
                                <span class="text-lg">→</span>
                            </a>

                            <a
                                href="{{ route('register') }}"
                                class="inline-flex items-center rounded-2xl border border-white/30 bg-white/10 px-7 py-4 text-sm font-black text-white backdrop-blur-md transition duration-300 hover:bg-white/20"
                            >
                                Daftar Kandidat
                            </a>
                        </div>

                        {{-- Stats --}}
                        <div class="mt-12 grid max-w-2xl gap-3 sm:grid-cols-3">

                            <div class="rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur-md">
                                <div class="text-3xl font-black text-white">
                                    {{ $jobs->count() }}+
                                </div>
                                <p class="mt-1 text-xs font-bold uppercase tracking-wide text-slate-300">
                                    Lowongan Aktif
                                </p>
                            </div>

                            <div class="rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur-md">
                                <div class="text-3xl font-black text-white">
                                    {{ $departments->count() }}
                                </div>
                                <p class="mt-1 text-xs font-bold uppercase tracking-wide text-slate-300">
                                    Departemen
                                </p>
                            </div>

                            <div class="rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur-md">
                                <div class="text-3xl font-black text-white">
                                    Online
                                </div>
                                <p class="mt-1 text-xs font-bold uppercase tracking-wide text-slate-300">
                                    Proses Lamaran
                                </p>
                            </div>

                        </div>
                    </div>

                    {{-- RIGHT CARD --}}
                    <div class="hidden lg:block">
                        <div class="rounded-[2rem] border border-white/20 bg-white/10 p-7 shadow-2xl backdrop-blur-xl">

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-300">
                                        Proses Rekrutmen
                                    </p>

                                    <h2 class="mt-2 text-2xl font-black text-white">
                                        Mudah & Terstruktur
                                    </h2>
                                </div>

                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-500 text-xl font-black text-white shadow-lg shadow-orange-500/30">
                                    ✓
                                </div>
                            </div>

                            <div class="mt-7 space-y-3">
                                @foreach([
                                    'Buat akun kandidat',
                                    'Lengkapi biodata',
                                    'Upload CV dan dokumen',
                                    'Pilih posisi yang tersedia',
                                    'Pantau proses seleksi'
                                ] as $item)

                                    <div class="group flex items-center gap-4 rounded-2xl border border-white/10 bg-slate-950/30 p-4 transition hover:bg-slate-950/45">

                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-500 text-sm font-black text-white">
                                            {{ $loop->iteration }}
                                        </div>

                                        <div>
                                            <p class="text-sm font-black text-white">
                                                {{ $item }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-300">
                                                Proses cepat, jelas, dan mudah dipantau.
                                            </p>
                                        </div>

                                    </div>

                                @endforeach
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>


    {{-- LOWONGAN AKTIF --}}
    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">

                <div>
                    <p class="text-sm font-black uppercase tracking-[0.22em] text-orange-500">
                        Lowongan Aktif
                    </p>

                    <h2 class="mt-3 max-w-2xl text-3xl font-black tracking-tight text-slate-950 md:text-4xl">
                        Temukan posisi yang sesuai dengan pengalaman Anda.
                    </h2>

                    <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">
                        Pilih posisi berdasarkan departemen, lokasi, tipe pekerjaan,
                        dan sistem kerja yang sesuai dengan kebutuhan Anda.
                    </p>
                </div>

                <a
                    href="{{ route('jobs.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-black text-orange-500 transition hover:gap-3 hover:text-orange-600"
                >
                    Semua Lowongan
                    <span>→</span>
                </a>

            </div>


            <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">

                @forelse($jobs as $job)

                    <a
                        href="{{ route('jobs.show', $job) }}"
                        class="group overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-slate-300/40"
                    >
                        <div class="h-1.5 w-full bg-gradient-to-r from-orange-500 via-amber-400 to-orange-500"></div>

                        <div class="p-6">

                            <div class="flex flex-wrap items-center gap-2">

                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-black uppercase tracking-wide text-emerald-700">
                                    {{ $job->status }}
                                </span>

                                <span class="rounded-full bg-orange-50 px-3 py-1 text-[11px] font-black uppercase tracking-wide text-orange-600">
                                    {{ $job->department?->name ?? 'General' }}
                                </span>

                            </div>

                            <h3 class="mt-5 text-xl font-black text-slate-950 transition group-hover:text-orange-500">
                                {{ $job->title }}
                            </h3>

                            <p class="mt-3 text-sm font-semibold text-slate-500">
                                {{ $job->location }}
                                <span class="mx-1">•</span>
                                {{ str_replace('_', ' ', $job->employment_type) }}
                                <span class="mx-1">•</span>
                                {{ str_replace('_', ' ', $job->work_arrangement) }}
                            </p>

                            <p class="mt-4 line-clamp-3 text-sm leading-7 text-slate-600">
                                {{ $job->description }}
                            </p>

                            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5 text-sm font-black">

                                <span class="text-slate-700">
                                    Lihat Detail
                                </span>

                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-orange-50 text-orange-500 transition group-hover:translate-x-1 group-hover:bg-orange-500 group-hover:text-white">
                                    →
                                </span>

                            </div>

                        </div>
                    </a>

                @empty

                    <div class="rounded-[1.75rem] border border-dashed border-slate-300 bg-white p-12 text-center text-slate-500 md:col-span-2 xl:col-span-3">
                        Belum ada lowongan aktif saat ini.
                    </div>

                @endforelse

            </div>
        </div>
    </section>


    {{-- KEUNGGULAN --}}
    <section class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="mx-auto max-w-3xl text-center">

                <p class="text-sm font-black uppercase tracking-[0.22em] text-orange-500">
                    Proses Digital
                </p>

                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 md:text-4xl">
                    Proses lamaran lebih praktis dan transparan.
                </h2>

                <p class="mt-4 leading-7 text-slate-600">
                    Seluruh data kandidat dan dokumen tersimpan dalam satu sistem sehingga
                    proses seleksi menjadi lebih rapi bagi kandidat maupun HRD.
                </p>

            </div>


            <div class="mt-12 grid gap-6 md:grid-cols-3">

                <div class="group rounded-[1.75rem] border border-slate-200 bg-slate-50 p-7 transition hover:-translate-y-1 hover:bg-white hover:shadow-xl">

                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-500 text-xl font-black text-white shadow-lg shadow-orange-500/20">
                        01
                    </div>

                    <h3 class="mt-6 text-xl font-black text-slate-950">
                        Biodata Lengkap
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Kandidat dapat melengkapi seluruh informasi pribadi,
                        pendidikan, pengalaman, dan kemampuan secara terstruktur.
                    </p>

                </div>


                <div class="group rounded-[1.75rem] border border-slate-200 bg-slate-50 p-7 transition hover:-translate-y-1 hover:bg-white hover:shadow-xl">

                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-500 text-xl font-black text-white shadow-lg shadow-orange-500/20">
                        02
                    </div>

                    <h3 class="mt-6 text-xl font-black text-slate-950">
                        Upload Dokumen
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        CV, sertifikat, dan dokumen pendukung lainnya dapat
                        diunggah dalam format PDF dengan mudah.
                    </p>

                </div>


                <div class="group rounded-[1.75rem] border border-slate-200 bg-slate-50 p-7 transition hover:-translate-y-1 hover:bg-white hover:shadow-xl">

                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-500 text-xl font-black text-white shadow-lg shadow-orange-500/20">
                        03
                    </div>

                    <h3 class="mt-6 text-xl font-black text-slate-950">
                        Review HRD
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        HRD dapat memeriksa data dan dokumen kandidat serta
                        memantau setiap tahapan proses rekrutmen.
                    </p>

                </div>

            </div>

        </div>
    </section>


    {{-- CTA BOTTOM --}}
    <section class="bg-slate-950 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-gradient-to-br from-orange-500 via-orange-500 to-amber-400 px-8 py-12 text-white shadow-2xl md:px-12 lg:flex lg:items-center lg:justify-between">

                <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>

                <div class="relative z-10 max-w-2xl">

                    <p class="text-xs font-black uppercase tracking-[0.22em] text-white/80">
                        Mulai Karier Anda
                    </p>

                    <h2 class="mt-3 text-3xl font-black tracking-tight md:text-4xl">
                        Siap menjadi bagian dari PT INDODATA SOLUSI PERSADA?
                    </h2>

                    <p class="mt-4 text-sm leading-7 text-white/90">
                        Buat akun kandidat sekarang dan temukan posisi yang sesuai dengan kemampuan Anda.
                    </p>

                </div>


                <div class="relative z-10 mt-8 flex flex-wrap gap-3 lg:mt-0">

                    <a
                        href="{{ route('register') }}"
                        class="rounded-2xl bg-white px-6 py-3.5 text-sm font-black text-orange-600 shadow-lg transition hover:-translate-y-0.5"
                    >
                        Daftar Sekarang
                    </a>

                    <a
                        href="{{ route('jobs.index') }}"
                        class="rounded-2xl border border-white/30 bg-white/10 px-6 py-3.5 text-sm font-black text-white backdrop-blur transition hover:bg-white/20"
                    >
                        Lihat Lowongan
                    </a>

                </div>

            </div>

        </div>
    </section>

</x-layouts.app>