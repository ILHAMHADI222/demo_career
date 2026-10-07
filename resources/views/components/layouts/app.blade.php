<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Career Indodata' }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @endif
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
    <div class="fixed inset-x-0 top-0 -z-10 h-96 bg-[radial-gradient(circle_at_top_left,_rgba(249,115,22,0.24),_transparent_35%),radial-gradient(circle_at_top_right,_rgba(251,191,36,0.18),_transparent_30%)]"></div>

<header class="sticky top-0 z-40 border-b border-white/70 bg-white/85 shadow-sm shadow-slate-200/50 backdrop-blur-xl">
    <div class="mx-auto max-w-7xl 2xl:max-w-[1400px] px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between gap-2 xl:gap-4">

            {{-- Logo & Brand --}}
            <a href="{{ route('career.home') }}" class="group flex shrink-0 items-center gap-2.5 sm:gap-3">
                <div class="flex h-12 w-12 sm:h-14 sm:w-14 shrink-0 items-center justify-center">
                    <img
                        src="{{ asset('assets/images/logo.png') }}"
                        alt="Logo PT Indodata Solusi Persada"
                        class="h-full w-full object-contain transition duration-300 group-hover:scale-105"
                    >
                </div>

                <div class="hidden sm:block leading-tight whitespace-nowrap">
                    <span class="block text-sm sm:text-base font-black tracking-tight text-slate-950">
                        Career Hallonet
                    </span>
                    <span class="block text-[10px] sm:text-xs font-semibold uppercase tracking-[0.18em] text-orange-500">
                        PT INDODATA SOLUSI PERSADA
                    </span>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden items-center gap-1 rounded-full border border-slate-200 bg-white/90 p-1 text-xs xl:text-[13px] font-bold text-slate-600 shadow-sm lg:flex">
                <a href="{{ route('career.home') }}"
                   class="whitespace-nowrap rounded-full px-3 py-2 transition {{ request()->routeIs('career.home') ? 'bg-orange-500 text-white shadow-sm shadow-orange-500/20' : 'hover:bg-orange-50 hover:text-orange-500' }}">
                    Home
                </a>

                <a href="{{ route('jobs.index') }}"
                   class="whitespace-nowrap rounded-full px-3 py-2 transition {{ request()->routeIs('jobs.*') ? 'bg-orange-500 text-white shadow-sm shadow-orange-500/20' : 'hover:bg-orange-50 hover:text-orange-500' }}">
                    Lowongan
                </a>

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="whitespace-nowrap rounded-full px-3 py-2 transition {{ request()->routeIs('dashboard') ? 'bg-orange-500 text-white shadow-sm shadow-orange-500/20' : 'hover:bg-orange-50 hover:text-orange-500' }}">
                        Dashboard
                    </a>

                    @if (auth()->user()->hasAnyRole(['SUPERADMIN', 'ADMIN']))
                        <a href="{{ route('admin.applications.index') }}"
                           class="whitespace-nowrap rounded-full px-3 py-2 transition {{ request()->routeIs('admin.applications.*') ? 'bg-orange-500 text-white shadow-sm shadow-orange-500/20' : 'hover:bg-orange-50 hover:text-orange-500' }}">
                            Pelamar
                        </a>
                        <a href="{{ route('admin.jobs.index') }}"
                           class="whitespace-nowrap rounded-full px-3 py-2 transition {{ request()->routeIs('admin.jobs.*') ? 'bg-orange-500 text-white shadow-sm shadow-orange-500/20' : 'hover:bg-orange-50 hover:text-orange-500' }}">
                            Kelola Lowongan
                        </a>
                        <a href="{{ route('admin.disc.assessments.index') }}"
                           class="whitespace-nowrap rounded-full px-3 py-2 transition {{ request()->routeIs('admin.disc.assessments.*') ? 'bg-orange-500 text-white shadow-sm shadow-orange-500/20' : 'hover:bg-orange-50 hover:text-orange-500' }}">
                            Asesmen
                        </a>
                        <a href="{{ route('admin.disc.banks.index') }}"
                           class="whitespace-nowrap rounded-full px-3 py-2 transition {{ request()->routeIs('admin.disc.banks.*') ? 'bg-orange-500 text-white shadow-sm shadow-orange-500/20' : 'hover:bg-orange-50 hover:text-orange-500' }}">
                            Bank Soal
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="whitespace-nowrap rounded-full px-3 py-2 transition {{ request()->routeIs('admin.users.*') ? 'bg-orange-500 text-white shadow-sm shadow-orange-500/20' : 'hover:bg-orange-50 hover:text-orange-500' }}">
                            User
                        </a>
                    @else
                        <a href="{{ route('candidate.profile') }}"
                           class="whitespace-nowrap rounded-full px-3 py-2 transition {{ request()->routeIs('candidate.profile') ? 'bg-orange-500 text-white shadow-sm shadow-orange-500/20' : 'hover:bg-orange-50 hover:text-orange-500' }}">
                            Profil
                        </a>
                    @endif
                @endauth
            </nav>

            {{-- Auth --}}
            <div class="flex shrink-0 items-center gap-2">
                @auth
                    @php
                        $navProfile = auth()->user()->candidateProfile;
                    @endphp
                    <a href="{{ route('dashboard') }}"
                       class="hidden items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-1.5 text-xs xl:text-sm font-bold text-slate-700 shadow-sm transition hover:border-orange-200 hover:text-orange-500 sm:inline-flex"
                       title="{{ auth()->user()->name }}">
                        @if($navProfile?->photo_url)
                            <img src="{{ $navProfile->photo_url }}" alt="Avatar" class="h-6 w-6 rounded-full object-cover ring-1 ring-orange-200" onerror="this.onerror=null; this.src='{{ $navProfile->avatar_url }}';">
                        @else
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-orange-100 text-[10px] font-bold text-orange-600">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        @endif
                        <span>{{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                        @csrf

                        <button
                            type="submit"
                            class="whitespace-nowrap rounded-2xl bg-slate-950 px-3.5 py-2 text-xs xl:text-sm font-bold text-white shadow-lg shadow-slate-950/15 transition hover:-translate-y-0.5 hover:bg-orange-500">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="hidden whitespace-nowrap rounded-2xl px-4 py-2 text-sm font-bold text-slate-700 transition hover:text-orange-500 sm:inline-flex">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                       class="whitespace-nowrap rounded-2xl bg-orange-500 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-orange-500/25 transition hover:-translate-y-0.5 hover:bg-orange-600">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>

        {{-- Mobile Navigation (Centered when few, Scrollable when many) --}}
        <div class="overflow-x-auto border-t border-slate-100 py-2.5 lg:hidden [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
            <nav class="flex min-w-full w-max items-center justify-center gap-2 px-4 text-xs font-bold text-slate-700">
                <a href="{{ route('career.home') }}"
                   class="shrink-0 rounded-full px-4 py-2 transition duration-200 {{ request()->routeIs('career.home') ? 'bg-[#f97316] text-white' : 'bg-slate-100/90 text-slate-700 hover:bg-[#f97316] hover:text-white' }}">
                    Home
                </a>

                <a href="{{ route('jobs.index') }}"
                   class="shrink-0 rounded-full px-4 py-2 transition duration-200 {{ request()->routeIs('jobs.*') ? 'bg-[#f97316] text-white' : 'bg-slate-100/90 text-slate-700 hover:bg-[#f97316] hover:text-white' }}">
                    Lowongan
                </a>

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="shrink-0 rounded-full px-4 py-2 transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-[#f97316] text-white' : 'bg-slate-100/90 text-slate-700 hover:bg-[#f97316] hover:text-white' }}">
                        Dashboard
                    </a>

                    @if (auth()->user()->hasAnyRole(['SUPERADMIN', 'ADMIN']))
                        <a href="{{ route('admin.applications.index') }}"
                           class="shrink-0 rounded-full px-4 py-2 transition duration-200 {{ request()->routeIs('admin.applications.*') ? 'bg-[#f97316] text-white' : 'bg-slate-100/90 text-slate-700 hover:bg-[#f97316] hover:text-white' }}">
                            Pelamar
                        </a>

                        <a href="{{ route('admin.jobs.index') }}"
                           class="shrink-0 rounded-full px-4 py-2 transition duration-200 {{ request()->routeIs('admin.jobs.*') ? 'bg-[#f97316] text-white' : 'bg-slate-100/90 text-slate-700 hover:bg-[#f97316] hover:text-white' }}">
                            Kelola Lowongan
                        </a>

                        <a href="{{ route('admin.disc.assessments.index') }}"
                           class="shrink-0 rounded-full px-4 py-2 transition duration-200 {{ request()->routeIs('admin.disc.assessments.*') ? 'bg-[#f97316] text-white' : 'bg-slate-100/90 text-slate-700 hover:bg-[#f97316] hover:text-white' }}">
                            Asesmen DISC
                        </a>

                        <a href="{{ route('admin.disc.banks.index') }}"
                           class="shrink-0 rounded-full px-4 py-2 transition duration-200 {{ request()->routeIs('admin.disc.banks.*') ? 'bg-[#f97316] text-white' : 'bg-slate-100/90 text-slate-700 hover:bg-[#f97316] hover:text-white' }}">
                            Bank Soal
                        </a>

                        <a href="{{ route('admin.users.index') }}"
                           class="shrink-0 rounded-full px-4 py-2 transition duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-[#f97316] text-white' : 'bg-slate-100/90 text-slate-700 hover:bg-[#f97316] hover:text-white' }}">
                            User
                        </a>
                    @else
                        <a href="{{ route('candidate.profile') }}"
                           class="shrink-0 rounded-full px-4 py-2 transition duration-200 {{ request()->routeIs('candidate.profile') ? 'bg-[#f97316] text-white' : 'bg-slate-100/90 text-slate-700 hover:bg-[#f97316] hover:text-white' }}">
                            Profil
                        </a>
                    @endif
                @endauth
            </nav>
        </div>
    </div>
</header>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-800 shadow-sm">
                <p class="font-black">Periksa kembali input Anda.</p>
                <ul class="mt-2 list-inside list-disc space-y-1 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <main class="min-h-[calc(100vh-18rem)]">{{ $slot }}</main>

<footer class="mt-14 sm:mt-16 border-t border-slate-200/80 bg-white/90 text-slate-800 shadow-sm backdrop-blur-md">
    <div class="mx-auto max-w-7xl 2xl:max-w-[1400px] px-4 py-8 sm:px-6 lg:px-8">

        <div class="grid gap-8 rounded-[2rem] border border-slate-200 bg-white/90 p-6 sm:p-8 shadow-sm md:grid-cols-4">

            {{-- Brand (Matching Navbar Exactly) --}}
            <div class="md:col-span-2">
                <a href="{{ route('career.home') }}" class="group inline-flex items-center gap-3">
                    <div class="flex h-12 w-12 sm:h-14 sm:w-14 shrink-0 items-center justify-center">
                        <img
                            src="{{ asset('assets/images/logo.png') }}"
                            alt="Logo PT Indodata Solusi Persada"
                            class="h-full w-full object-contain transition duration-300 group-hover:scale-105"
                        >
                    </div>

                    <div class="leading-tight">
                        <span class="block text-base sm:text-lg font-black tracking-tight text-slate-950">
                            Career Hallonet
                        </span>
                        <span class="block text-xs font-semibold uppercase tracking-[0.18em] text-orange-500">
                            PT INDODATA SOLUSI PERSADA
                        </span>
                    </div>
                </a>

                <p class="mt-3.5 max-w-lg text-sm leading-relaxed text-slate-600">
                    Platform rekrutmen terpadu PT INDODATA SOLUSI PERSADA untuk pendaftaran calon karyawan, seleksi berkas administrasi, asesmen DISC, jadwal interview, dan offering.
                </p>
            </div>

            {{-- Portal --}}
            <div>
                <h3 class="text-sm font-black uppercase tracking-wider text-slate-950">
                    Portal Karir
                </h3>

                <div class="mt-3.5 grid gap-2 text-sm font-semibold text-slate-600">
                    <a
                        href="{{ route('career.home') }}"
                        class="transition hover:text-orange-500"
                    >
                        Beranda
                    </a>

                    <a
                        href="{{ route('jobs.index') }}"
                        class="transition hover:text-orange-500"
                    >
                        Lowongan Pekerjaan
                    </a>

                    @guest
                        <a
                            href="{{ route('register') }}"
                            class="transition hover:text-orange-500"
                        >
                            Daftar Kandidat
                        </a>
                    @else
                        <a
                            href="{{ route('dashboard') }}"
                            class="transition hover:text-orange-500"
                        >
                            Dashboard
                        </a>
                    @endguest
                </div>
            </div>

            {{-- Kontak --}}
            <div>
                <h3 class="text-sm font-black uppercase tracking-wider text-slate-950">
                    Kontak HRD
                </h3>

                <div class="mt-3.5 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm leading-relaxed text-slate-700">
                    <p class="font-bold text-slate-900">HR Recruitment Team</p>
                    <p class="mt-0.5 text-xs text-slate-500">PT Indodata Solusi Persada</p>
                    <a
                        href="mailto:career@indodata.test"
                        class="mt-2 inline-block text-xs font-semibold text-orange-600 hover:underline"
                    >
                        career@indodata.test
                    </a>
                </div>
            </div>

        </div>

        {{-- Copyright --}}
        <div class="mt-5 flex flex-col items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/90 px-5 py-3.5 text-xs font-semibold text-slate-600 sm:flex-row">
            <span>
                © {{ date('Y') }} Career Hallonet • PT INDODATA SOLUSI PERSADA. All rights reserved.
            </span>

            <a
                href="{{ route('career.home') }}"
                class="font-bold text-slate-700 transition hover:text-orange-500"
            >
                Career Indodata
            </a>
        </div>

    </div>
</footer>
</body>
</html>
