<x-layouts.app title="Login - Career Indodata">

    <section class="relative min-h-[calc(100vh-80px)] overflow-hidden bg-slate-50">

        {{-- Background decoration --}}
        <div class="absolute -left-32 top-20 h-96 w-96 rounded-full bg-orange-200/30 blur-3xl"></div>
        <div class="absolute -right-32 bottom-10 h-96 w-96 rounded-full bg-amber-200/30 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-16">

            <div class="grid overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl shadow-slate-300/30 lg:min-h-[650px] lg:grid-cols-[1.05fr_0.95fr]">

                {{-- LEFT SIDE --}}
                <div
                    class="relative min-h-[420px] overflow-hidden bg-cover bg-center lg:min-h-full"
                    style="background-image: url('https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1400&q=85');"
                >

                    {{-- Dark overlay --}}
                    <div class="absolute inset-0 bg-slate-950/60"></div>
                    <div class="absolute inset-0 bg-gradient-to-br from-slate-950/90 via-slate-950/65 to-orange-950/30"></div>

                    {{-- Orange glow --}}
                    <div class="absolute -left-20 top-20 h-72 w-72 rounded-full bg-orange-500/25 blur-3xl"></div>
                    <div class="absolute -bottom-24 right-0 h-72 w-72 rounded-full bg-amber-400/15 blur-3xl"></div>

                    {{-- Content --}}
                    <div class="relative z-10 flex h-full min-h-[420px] flex-col justify-between p-8 text-white md:p-10 lg:min-h-full lg:p-12">

                        <div>
                            {{-- Badge --}}
                            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-[0.22em] text-orange-300 backdrop-blur-md">
                                <span class="h-2 w-2 rounded-full bg-orange-400"></span>
                                Secure Career Portal
                            </span>

                            <h1 class="mt-7 max-w-xl text-4xl font-black leading-tight tracking-tight md:text-5xl">
                                Selamat datang di
                                <span class="text-orange-400">
                                    Career Indodata.
                                </span>
                            </h1>

                            <p class="mt-5 max-w-xl text-sm leading-7 text-slate-200 md:text-base">
                                Masuk untuk mengelola proses rekrutmen, memantau lamaran,
                                melengkapi profil kandidat, dan melanjutkan proses seleksi
                                dalam satu portal terintegrasi.
                            </p>
                        </div>


                        {{-- Features --}}
                        <div class="mt-10 grid gap-3 sm:grid-cols-3 lg:grid-cols-1 xl:grid-cols-3">

                            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-md">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-orange-500 text-sm font-black text-white">
                                    ✓
                                </div>

                                <p class="mt-3 text-sm font-black">
                                    Secure Access
                                </p>

                                <p class="mt-1 text-xs leading-5 text-slate-300">
                                    Akses sesuai role pengguna.
                                </p>
                            </div>


                            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-md">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-orange-500 text-sm font-black text-white">
                                    ✓
                                </div>

                                <p class="mt-3 text-sm font-black">
                                    ATS Workflow
                                </p>

                                <p class="mt-1 text-xs leading-5 text-slate-300">
                                    Rekrutmen lebih terstruktur.
                                </p>
                            </div>


                            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-md">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-orange-500 text-sm font-black text-white">
                                    ✓
                                </div>

                                <p class="mt-3 text-sm font-black">
                                    Tracking
                                </p>

                                <p class="mt-1 text-xs leading-5 text-slate-300">
                                    Pantau status proses seleksi.
                                </p>
                            </div>

                        </div>

                    </div>
                </div>


                {{-- RIGHT SIDE / LOGIN FORM --}}
                <div class="flex items-center">
                    <div class="w-full p-6 sm:p-8 md:p-10 lg:p-12 xl:px-16">

                        {{-- Header --}}
                        <div class="max-w-md">

                            <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">
                                Account Access
                            </p>

                            <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 md:text-4xl">
                                Masuk ke akun Anda
                            </h2>

                            <p class="mt-3 text-sm leading-6 text-slate-500">
                                Gunakan akun kandidat, admin, atau superadmin yang telah terdaftar.
                            </p>

                        </div>


                        {{-- Session Status --}}
                        @if (session('status'))
                            <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                                {{ session('status') }}
                            </div>
                        @endif


                        {{-- General Error --}}
                        @if ($errors->any())
                            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4">
                                <div class="flex gap-3">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-100 font-black text-red-600">
                                        !
                                    </div>

                                    <div>
                                        <p class="text-sm font-black text-red-700">
                                            Login gagal
                                        </p>

                                        <p class="mt-1 text-xs leading-5 text-red-600">
                                            Periksa kembali email dan password yang Anda masukkan.
                                        </p>
                                    </div>

                                </div>
                            </div>
                        @endif


                        <form
                            method="POST"
                            action="{{ route('login.store') }}"
                            class="mt-8 space-y-5"
                        >
                            @csrf

                            {{-- Email --}}
                            <div>
                                <label
                                    for="email"
                                    class="text-sm font-black text-slate-700"
                                >
                                    Email
                                </label>

                                <div class="relative mt-2">

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
                                                d="M21.75 6.75v10.5A2.25 2.25 0 0119.5 19.5h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0l-8.659 5.493a2.25 2.25 0 01-2.182 0L2.25 6.75"
                                            />
                                        </svg>
                                    </div>

                                    <input
                                        id="email"
                                        name="email"
                                        type="email"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus
                                        autocomplete="email"
                                        placeholder="nama@email.com"
                                        class="w-full rounded-2xl border
                                            @error('email')
                                                border-red-300 bg-red-50
                                            @else
                                                border-slate-200 bg-slate-50
                                            @enderror
                                            py-3.5 pl-12 pr-4 text-sm font-semibold text-slate-700 outline-none transition
                                            placeholder:text-slate-400
                                            focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-100"
                                    >

                                </div>

                                @error('email')
                                    <p class="mt-2 text-xs font-semibold text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>


                            {{-- Password --}}
                            <div>
                                <div class="flex items-center justify-between">

                                    <label
                                        for="password"
                                        class="text-sm font-black text-slate-700"
                                    >
                                        Password
                                    </label>

                                    @if (Route::has('password.request'))
                                        <a
                                            href="{{ route('password.request') }}"
                                            class="text-xs font-black text-orange-500 transition hover:text-orange-600"
                                        >
                                            Lupa password?
                                        </a>
                                    @endif

                                </div>

                                <div class="relative mt-2">

                                    {{-- Lock icon --}}
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
                                                d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75m-.75 9h10.5A2.25 2.25 0 0019.5 17.25v-6A2.25 2.25 0 0017.25 9H6.75A2.25 2.25 0 004.5 11.25v6a2.25 2.25 0 002.25 2.25z"
                                            />
                                        </svg>
                                    </div>

                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="Masukkan password"
                                        class="w-full rounded-2xl border
                                            @error('password')
                                                border-red-300 bg-red-50
                                            @else
                                                border-slate-200 bg-slate-50
                                            @enderror
                                            py-3.5 pl-12 pr-12 text-sm font-semibold text-slate-700 outline-none transition
                                            placeholder:text-slate-400
                                            focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-100"
                                    >

                                    {{-- Toggle password --}}
                                    <button
                                        type="button"
                                        onclick="togglePassword()"
                                        class="absolute inset-y-0 right-4 flex items-center text-slate-400 transition hover:text-orange-500"
                                        aria-label="Tampilkan password"
                                    >
                                        <svg
                                            id="eyeOpen"
                                            class="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>

                                        <svg
                                            id="eyeClosed"
                                            class="hidden h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M3.98 8.223A10.477 10.477 0 002.036 11.68a1.017 1.017 0 000 .639C3.423 16.49 7.36 19.5 12 19.5a9.72 9.72 0 004.602-1.09M6.228 6.228A9.718 9.718 0 0112 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639a10.504 10.504 0 01-2.293 3.95M6.228 6.228L3 3m3.228 3.228l3.65 3.65m9.792 6.39L21 21m-4.398-2.59l-3.65-3.65m0 0a3 3 0 01-4.243-4.243m4.243 4.243L9.88 9.88"
                                            />
                                        </svg>
                                    </button>

                                </div>

                                @error('password')
                                    <p class="mt-2 text-xs font-semibold text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>


                            {{-- Remember --}}
                            <div class="flex items-center justify-between">

                                <label class="flex cursor-pointer items-center gap-3 text-sm font-semibold text-slate-600">

                                    <input
                                        type="checkbox"
                                        name="remember"
                                        class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500"
                                    >

                                    <span>
                                        Ingat saya
                                    </span>

                                </label>

                            </div>


                            {{-- Login button --}}
                            <button
                                type="submit"
                                class="group flex w-full items-center justify-center gap-3 rounded-2xl bg-orange-500 px-5 py-3.5 font-black text-white shadow-lg shadow-orange-500/25 transition duration-300 hover:-translate-y-0.5 hover:bg-orange-600"
                            >
                                Masuk ke Akun

                                <span class="transition-transform duration-300 group-hover:translate-x-1">
                                    →
                                </span>
                            </button>

                        </form>


                        {{-- Divider --}}
                        <div class="my-7 flex items-center gap-4">
                            <div class="h-px flex-1 bg-slate-200"></div>

                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                Kandidat Baru
                            </span>

                            <div class="h-px flex-1 bg-slate-200"></div>
                        </div>


                        {{-- Register --}}
                        <a
                            href="{{ route('register') }}"
                            class="flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3.5 text-sm font-black text-slate-700 transition hover:border-orange-200 hover:bg-orange-50 hover:text-orange-600"
                        >
                            Buat Akun Kandidat
                        </a>


                        {{-- Back --}}
                        <p class="mt-6 text-center text-xs leading-6 text-slate-400">
                            Dengan masuk ke portal, Anda menggunakan layanan Career Indodata
                            sesuai kebijakan perusahaan.
                        </p>

                        <div class="mt-4 text-center">
                            <a
                                href="{{ route('career.home') }}"
                                class="inline-flex items-center gap-2 text-xs font-black text-slate-500 transition hover:text-orange-500"
                            >
                                <span>←</span>
                                Kembali ke Beranda
                            </a>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </section>


    {{-- Show / Hide Password --}}
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            if (password.type === 'password') {
                password.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                password.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>

</x-layouts.app>