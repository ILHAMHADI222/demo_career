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
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between gap-4">

            {{-- Logo & Brand --}}
            <a href="{{ route('career.home') }}" class="group flex min-w-0 items-center gap-3">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center">
                    <img
                        src="{{ asset('assets/images/logo.png') }}"
                        alt="Logo PT Indodata Solusi Persada"
                        class="h-full w-full object-contain transition duration-300 group-hover:scale-105"
                    >
                </div>

                <div class="hidden leading-tight sm:block">
                    <span class="block text-base font-black tracking-tight text-slate-950">
                        Career Hallonet
                    </span>
                    <span class="block text-xs font-semibold uppercase tracking-[0.20em] text-orange-500">
                        PT INDODATA SOLUSI PERSADA
                    </span>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden items-center gap-2 rounded-full border border-slate-200 bg-white/80 p-1 text-sm font-bold text-slate-600 shadow-sm lg:flex">
                <a href="{{ route('career.home') }}"
                   class="rounded-full px-4 py-2 transition hover:bg-orange-50 hover:text-orange-500">
                    Home
                </a>

                <a href="{{ route('jobs.index') }}"
                   class="rounded-full px-4 py-2 transition hover:bg-orange-50 hover:text-orange-500">
                    Lowongan
                </a>

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="rounded-full px-4 py-2 transition hover:bg-orange-50 hover:text-orange-500">
                        Dashboard
                    </a>

                    @if (auth()->user()->hasAnyRole(['SUPERADMIN', 'ADMIN']))
                        <a href="{{ route('admin.applications.index') }}"
                           class="rounded-full px-4 py-2 transition hover:bg-orange-50 hover:text-orange-500">
                            Pelamar
                        </a>
                        <a href="{{ route('admin.jobs.index') }}"
                           class="rounded-full px-4 py-2 transition hover:bg-orange-50 hover:text-orange-500">
                            Kelola Lowongan
                        </a>
                        <a href="{{ route('admin.disc.assessments.index') }}"
                           class="rounded-full px-4 py-2 transition hover:bg-orange-50 hover:text-orange-500">
                            Asesmen DISC
                        </a>
                        <a href="{{ route('admin.disc.banks.index') }}"
                           class="rounded-full px-4 py-2 transition hover:bg-orange-50 hover:text-orange-500">
                            Bank Soal
                        </a>
                        @if (auth()->user()->canDo('manage_users'))
                            <a href="{{ route('admin.users.index') }}"
                               class="rounded-full px-4 py-2 transition hover:bg-orange-50 hover:text-orange-500">
                                User
                            </a>
                        @endif
                    @else
                        <a href="{{ route('candidate.profile') }}"
                           class="rounded-full px-4 py-2 transition hover:bg-orange-50 hover:text-orange-500">
                            Profil
                        </a>
                    @endif
                @endauth
            </nav>

            {{-- Auth --}}
            <div class="flex shrink-0 items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="hidden rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm transition hover:border-orange-200 hover:text-orange-500 sm:inline-flex">
                        {{ auth()->user()->name }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="rounded-2xl bg-slate-950 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-slate-950/15 transition hover:-translate-y-0.5 hover:bg-orange-500">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="hidden rounded-2xl px-4 py-2 text-sm font-bold text-slate-700 transition hover:text-orange-500 sm:inline-flex">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                       class="rounded-2xl bg-orange-500 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-orange-500/25 transition hover:-translate-y-0.5 hover:bg-orange-600">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>

        {{-- Mobile Navigation --}}
        <nav class="grid grid-cols-3 gap-2 border-t border-slate-100 py-3 text-center text-xs font-bold text-slate-600 lg:hidden">
            <a href="{{ route('career.home') }}"
               class="rounded-xl bg-slate-50 px-3 py-2 transition hover:bg-orange-50 hover:text-orange-500">
                Home
            </a>

            <a href="{{ route('jobs.index') }}"
               class="rounded-xl bg-slate-50 px-3 py-2 transition hover:bg-orange-50 hover:text-orange-500">
                Lowongan
            </a>

            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
               class="rounded-xl bg-slate-50 px-3 py-2 transition hover:bg-orange-50 hover:text-orange-500">
                Dashboard
            </a>
        </nav>
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

    <main class="min-h-[calc(100vh-20rem)]">{{ $slot }}</main>

<footer class="mt-20 border-t border-white/70 bg-white/85 text-slate-900 shadow-[0_-8px_30px_rgba(15,23,42,0.04)] backdrop-blur-xl">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        <div class="grid gap-8 rounded-[2rem] border border-slate-200 bg-white/80 p-6 shadow-sm md:grid-cols-4 md:p-8">

            {{-- Brand --}}
            <div class="md:col-span-2">
                <a href="{{ route('career.home') }}" class="group inline-flex items-center gap-3">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center">
                        <img
                            src="{{ asset('assets/images/logo2.png') }}"
                            alt="Logo PT Indodata Solusi Persada"
                            class="h-full w-full object-contain transition duration-300 group-hover:scale-105"
                        >
                    </div>

                    <div class="leading-tight">
                        <span class="block text-base font-black tracking-tight text-slate-950">
                            Career Indodata
                        </span>
                        <span class="block text-xs font-semibold uppercase tracking-[0.18em] text-orange-500">
                            PT INDODATA SOLUSI PERSADA
                        </span>
                    </div>
                </a>

                <p class="mt-4 max-w-xl text-sm leading-6 text-slate-500">
                    Platform rekrutmen terpadu PT INDODATA SOLUSI PERSADA untuk
                    pendaftaran calon karyawan, unggah berkas PDF, review HRD,
                    dan tracking progres seleksi.
                </p>
            </div>

            {{-- Portal --}}
            <div>
                <h3 class="font-black text-slate-950">
                    Portal
                </h3>

                <div class="mt-4 grid gap-2 text-sm font-semibold text-slate-600">
                    <a
                        href="{{ route('career.home') }}"
                        class="rounded-xl px-3 py-2 transition hover:bg-orange-50 hover:text-orange-500"
                    >
                        Beranda
                    </a>

                    <a
                        href="{{ route('jobs.index') }}"
                        class="rounded-xl px-3 py-2 transition hover:bg-orange-50 hover:text-orange-500"
                    >
                        Lowongan
                    </a>

                    <a
                        href="{{ route('register') }}"
                        class="rounded-xl px-3 py-2 transition hover:bg-orange-50 hover:text-orange-500"
                    >
                        Daftar Kandidat
                    </a>
                </div>
            </div>

            {{-- Kontak --}}
            <div>
                <h3 class="font-black text-slate-950">
                    Kontak
                </h3>

                <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm leading-6 text-slate-600">
                        HR Recruitment<br>
                        PT INDODATA SOLUSI PERSADA
                    </p>

                    <a
                        href="mailto:career@indodata.test"
                        class="mt-2 inline-block text-sm font-semibold text-slate-700 transition hover:text-orange-500"
                    >
                        career@indodata.test
                    </a>
                </div>
            </div>

        </div>

        {{-- Copyright --}}
        <div class="mt-5 flex flex-col items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/80 px-5 py-4 text-xs font-semibold text-slate-500 shadow-sm sm:flex-row">
            <span>
                © {{ date('Y') }} Career Indodata. By Hallolabs Team.
            </span>

            <a
                href="{{ route('career.home') }}"
                class="rounded-full border border-slate-200 bg-white px-4 py-2 font-bold text-slate-600 transition hover:border-orange-200 hover:bg-orange-50 hover:text-orange-500"
            >
                Career Indodata
            </a>
        </div>

    </div>
</footer>
</body>
</html>
