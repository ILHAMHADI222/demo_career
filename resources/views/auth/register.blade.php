<x-layouts.app title="Daftar Kandidat - Career Indodata">

    <section class="relative min-h-[calc(100vh-80px)] overflow-hidden bg-slate-50">

        {{-- Background Decoration --}}
        <div class="absolute -left-32 top-20 h-96 w-96 rounded-full bg-orange-200/30 blur-3xl"></div>
        <div class="absolute -right-32 bottom-10 h-96 w-96 rounded-full bg-amber-200/30 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-16">

            <div class="grid overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl shadow-slate-300/30 lg:min-h-[700px] lg:grid-cols-[0.95fr_1.05fr]">

                {{-- LEFT SIDE --}}
                <div
                    class="relative min-h-[460px] overflow-hidden bg-cover bg-center lg:min-h-full"
                    style="background-image: url('https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1400&q=85');"
                >

                    {{-- Overlay --}}
                    <div class="absolute inset-0 bg-slate-950/60"></div>

                    <div class="absolute inset-0 bg-gradient-to-br from-slate-950/90 via-slate-950/65 to-orange-950/30"></div>

                    {{-- Glow --}}
                    <div class="absolute -left-20 top-20 h-72 w-72 rounded-full bg-orange-500/25 blur-3xl"></div>

                    <div class="absolute -bottom-24 right-0 h-72 w-72 rounded-full bg-amber-400/15 blur-3xl"></div>


                    {{-- Content --}}
                    <div class="relative z-10 flex h-full min-h-[460px] flex-col justify-between p-8 text-white md:p-10 lg:min-h-full lg:p-12">

                        <div>

                            {{-- Badge --}}
                            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-[0.22em] text-orange-300 backdrop-blur-md">

                                <span class="h-2 w-2 rounded-full bg-orange-400"></span>

                                Candidate Registration

                            </span>


                            <h1 class="mt-7 max-w-xl text-4xl font-black leading-tight tracking-tight md:text-5xl">

                                Mulai perjalanan karier Anda

                                <span class="text-orange-400">
                                    bersama Indodata.
                                </span>

                            </h1>


                            <p class="mt-5 max-w-xl text-sm leading-7 text-slate-200 md:text-base">

                                Buat akun kandidat PT INDODATA SOLUSI PERSADA
                                untuk menemukan peluang kerja, melengkapi profil,
                                mengunggah dokumen, dan mengikuti proses seleksi
                                secara online.

                            </p>

                        </div>


                        {{-- Features --}}
                        <div class="mt-10 space-y-3">

                            @foreach([
                                [
                                    'title' => 'Profil Kandidat Terpusat',
                                    'description' => 'Kelola biodata dan informasi profesional dalam satu akun.'
                                ],
                                [
                                    'title' => 'Lamaran Lebih Praktis',
                                    'description' => 'Pilih posisi dan kirim lamaran secara online.'
                                ],
                                [
                                    'title' => 'Tracking Proses Seleksi',
                                    'description' => 'Pantau perkembangan lamaran melalui dashboard kandidat.'
                                ]
                            ] as $feature)

                                <div class="flex items-center gap-4 rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-md">

                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-orange-500 font-black text-white shadow-lg shadow-orange-500/20">
                                        ✓
                                    </div>

                                    <div>

                                        <p class="text-sm font-black text-white">
                                            {{ $feature['title'] }}
                                        </p>

                                        <p class="mt-1 text-xs leading-5 text-slate-300">
                                            {{ $feature['description'] }}
                                        </p>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                </div>


                {{-- RIGHT SIDE --}}
                <div class="flex items-center">

                    <div class="w-full p-6 sm:p-8 md:p-10 lg:p-12 xl:px-16">

                        {{-- Heading --}}
                        <div>

                            <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">
                                Create Account
                            </p>

                            <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 md:text-4xl">
                                Daftar Kandidat
                            </h2>

                            <p class="mt-3 max-w-lg text-sm leading-6 text-slate-500">
                                Lengkapi data berikut untuk membuat akun Career Indodata. Setelah submit, kode OTP akan dikirim ke email Anda untuk verifikasi.
                            </p>

                        </div>


                        {{-- General Validation --}}
                        @if ($errors->any())

                            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4">

                                <div class="flex gap-3">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-100 font-black text-red-600">
                                        !
                                    </div>

                                    <div>

                                        <p class="text-sm font-black text-red-700">
                                            Registrasi belum berhasil
                                        </p>

                                        <p class="mt-1 text-xs leading-5 text-red-600">
                                            Silakan periksa kembali data yang Anda masukkan.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- FORM --}}
                        <form
                            method="POST"
                            action="{{ route('register.store') }}"
                            class="mt-8 space-y-5"
                        >

                            @csrf


                            {{-- NAME --}}
                            <div>

                                <label
                                    for="name"
                                    class="text-sm font-black text-slate-700"
                                >
                                    Nama Lengkap
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
                                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"
                                            />

                                        </svg>

                                    </div>


                                    <input
                                        id="name"
                                        name="name"
                                        type="text"
                                        value="{{ old('name') }}"
                                        required
                                        autofocus
                                        autocomplete="name"
                                        placeholder="Nama sesuai identitas"
                                        class="w-full rounded-2xl border
                                            @error('name')
                                                border-red-300 bg-red-50
                                            @else
                                                border-slate-200 bg-slate-50
                                            @enderror
                                            py-3.5 pl-12 pr-4 text-sm font-semibold text-slate-700 outline-none transition
                                            placeholder:text-slate-400
                                            focus:border-orange-500
                                            focus:bg-white
                                            focus:ring-4
                                            focus:ring-orange-100"
                                    >

                                </div>


                                @error('name')

                                    <p class="mt-2 text-xs font-semibold text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- EMAIL --}}
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
                                            focus:border-orange-500
                                            focus:bg-white
                                            focus:ring-4
                                            focus:ring-orange-100"
                                    >

                                </div>


                                @error('email')

                                    <p class="mt-2 text-xs font-semibold text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- PASSWORD --}}
                            <div>

                                <label
                                    for="password"
                                    class="text-sm font-black text-slate-700"
                                >
                                    Password
                                </label>


                                <div class="relative mt-2">

                                    {{-- Lock --}}
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
                                        autocomplete="new-password"
                                        placeholder="Minimal 8 karakter"
                                        class="w-full rounded-2xl border
                                            @error('password')
                                                border-red-300 bg-red-50
                                            @else
                                                border-slate-200 bg-slate-50
                                            @enderror
                                            py-3.5 pl-12 pr-12 text-sm font-semibold text-slate-700 outline-none transition
                                            placeholder:text-slate-400
                                            focus:border-orange-500
                                            focus:bg-white
                                            focus:ring-4
                                            focus:ring-orange-100"
                                    >


                                    <button
                                        type="button"
                                        onclick="toggleRegisterPassword('password', 'eyePasswordOpen', 'eyePasswordClosed')"
                                        class="absolute inset-y-0 right-4 flex items-center text-slate-400 transition hover:text-orange-500"
                                        aria-label="Tampilkan password"
                                    >

                                        <svg
                                            id="eyePasswordOpen"
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
                                            id="eyePasswordClosed"
                                            class="hidden h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M3.98 8.223A10.477 10.477 0 002.036 11.68a1.017 1.017 0 000 .639C3.423 16.49 7.36 19.5 12 19.5a9.72 9.72 0 004.602-1.09M6.228 6.228A9.718 9.718 0 0112 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639a10.504 10.504 0 01-2.293 3.95M6.228 6.228L3 3m3.228 3.228l3.65 3.65m9.792 6.39L21 21m-4.398-2.59l-3.65-3.65"
                                            />

                                        </svg>

                                    </button>

                                </div>


                                @error('password')

                                    <p class="mt-2 text-xs font-semibold text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror


                                <p class="mt-2 text-xs leading-5 text-slate-400">
                                    Gunakan minimal 8 karakter dan kombinasikan huruf, angka, serta karakter khusus jika diperlukan.
                                </p>

                            </div>


                            {{-- CONFIRM PASSWORD --}}
                            <div>

                                <label
                                    for="password_confirmation"
                                    class="text-sm font-black text-slate-700"
                                >
                                    Konfirmasi Password
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
                                                d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75m-.75 9h10.5A2.25 2.25 0 0019.5 17.25v-6A2.25 2.25 0 0017.25 9H6.75A2.25 2.25 0 004.5 11.25v6a2.25 2.25 0 002.25 2.25z"
                                            />

                                        </svg>

                                    </div>


                                    <input
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        type="password"
                                        required
                                        autocomplete="new-password"
                                        placeholder="Ulangi password"
                                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-12 pr-12 text-sm font-semibold text-slate-700 outline-none transition
                                        placeholder:text-slate-400
                                        focus:border-orange-500
                                        focus:bg-white
                                        focus:ring-4
                                        focus:ring-orange-100"
                                    >


                                    <button
                                        type="button"
                                        onclick="toggleRegisterPassword(
                                            'password_confirmation',
                                            'eyeConfirmationOpen',
                                            'eyeConfirmationClosed'
                                        )"
                                        class="absolute inset-y-0 right-4 flex items-center text-slate-400 transition hover:text-orange-500"
                                    >

                                        <svg
                                            id="eyeConfirmationOpen"
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
                                            id="eyeConfirmationClosed"
                                            class="hidden h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M3.98 8.223A10.477 10.477 0 002.036 11.68a1.017 1.017 0 000 .639C3.423 16.49 7.36 19.5 12 19.5a9.72 9.72 0 004.602-1.09M6.228 6.228A9.718 9.718 0 0112 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639a10.504 10.504 0 01-2.293 3.95M6.228 6.228L3 3m3.228 3.228l3.65 3.65m9.792 6.39L21 21m-4.398-2.59l-3.65-3.65"
                                            />

                                        </svg>

                                    </button>

                                </div>

                            </div>


                            {{-- BUTTON --}}
                            <button
                                type="submit"
                                class="group flex w-full items-center justify-center gap-3 rounded-2xl bg-orange-500 px-5 py-3.5 font-black text-white shadow-lg shadow-orange-500/25 transition duration-300 hover:-translate-y-0.5 hover:bg-orange-600"
                            >

                                Buat Akun & Kirim OTP

                                <span class="transition-transform duration-300 group-hover:translate-x-1">
                                    →
                                </span>

                            </button>

                        </form>


                        {{-- Divider --}}
                        <div class="my-7 flex items-center gap-4">

                            <div class="h-px flex-1 bg-slate-200"></div>

                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                Sudah Terdaftar?
                            </span>

                            <div class="h-px flex-1 bg-slate-200"></div>

                        </div>


                        {{-- Login --}}
                        <a
                            href="{{ route('login') }}"
                            class="flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3.5 text-sm font-black text-slate-700 transition hover:border-orange-200 hover:bg-orange-50 hover:text-orange-600"
                        >
                            Login ke Akun
                        </a>


                        {{-- Bottom Info --}}
                        <p class="mt-6 text-center text-xs leading-6 text-slate-400">

                            Dengan membuat akun, Anda dapat menggunakan portal Career
                            Indodata untuk mengikuti proses rekrutmen PT INDODATA SOLUSI PERSADA.
                            Verifikasi OTP email diperlukan sebelum akun dapat digunakan.

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


    {{-- Toggle Password --}}
    <script>
        function toggleRegisterPassword(inputId, openId, closedId) {

            const input = document.getElementById(inputId);
            const eyeOpen = document.getElementById(openId);
            const eyeClosed = document.getElementById(closedId);

            if (input.type === 'password') {

                input.type = 'text';

                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');

            } else {

                input.type = 'password';

                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');

            }

        }
    </script>

</x-layouts.app>