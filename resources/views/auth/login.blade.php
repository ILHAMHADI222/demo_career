<x-layouts.app title="Login - Career Indodata">
    <section class="min-h-[calc(100vh-80px)] bg-[#f0efe9] px-4 py-12 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto grid max-w-[1200px] gap-12 lg:grid-cols-[1.05fr_0.95fr] lg:items-stretch">
            <div
                class="relative min-h-[560px] overflow-hidden rounded-[80px] bg-cover bg-center"
                style="background-image: url('https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1400&q=85');"
            >
                <div class="absolute inset-x-8 top-8 flex items-center justify-between rounded-2xl border border-white/20 bg-[#322d2a]/75 px-6 py-5 text-white backdrop-blur-md sm:inset-x-12 sm:top-12">
                    <div class="text-sm font-normal tracking-[0.14px]">Career Indodata</div>
                    <div class="hidden items-center gap-6 text-sm font-normal tracking-[0.14px] sm:flex">
                        <span>Portal</span>
                        <span>ATS</span>
                        <span>Talent</span>
                    </div>
                </div>

                <div class="absolute inset-x-8 bottom-8 sm:inset-x-12 sm:bottom-12">
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-white">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        Secure Career Portal
                    </div>
                    <h1 class="mt-6 max-w-2xl text-[44px] font-light leading-none tracking-[0.52px] text-white md:text-[52px]">
                        Selamat datang di Career Indodata.
                    </h1>
                    <p class="mt-6 max-w-xl text-sm font-normal leading-[1.3] tracking-[0.14px] text-white/90">
                        Masuk untuk mengelola proses rekrutmen, memantau lamaran, melengkapi profil kandidat, dan melanjutkan proses seleksi dalam satu portal terintegrasi.
                    </p>
                </div>
            </div>

            <div class="flex items-center rounded-[56px] border border-white bg-white px-6 py-10 sm:px-10 lg:px-12">
                <div class="w-full">
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        Account Access
                    </div>
                    <h2 class="mt-6 text-[40px] font-light leading-none tracking-[0.52px] text-[#322d2a] md:text-[52px]">
                        Masuk ke akun Anda
                    </h2>
                    <p class="mt-5 text-sm font-normal leading-[1.3] tracking-[0.14px] text-[#8b8b8b]">
                        Gunakan akun kandidat, admin, atau superadmin yang telah terdaftar.
                    </p>

                    @if (session('status'))
                        <div class="mt-6 rounded-xl border border-[#322d2a] bg-[#f0efe9] px-4 py-3 text-sm font-normal leading-[1.3] text-[#322d2a]">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-6 rounded-xl border border-[#322d2a] bg-[#f0efe9] p-4 text-sm font-normal leading-[1.3] text-[#322d2a]">
                            <span class="font-normal">Login gagal.</span> Periksa kembali email dan password yang Anda masukkan.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="nama@email.com" class="mt-2 w-full rounded-[18px] border @error('email') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/20 bg-[#f0efe9] @enderror px-5 py-4 text-sm font-normal tracking-[0.14px] text-[#322d2a] outline-none transition placeholder:text-[#8b8b8b] focus:border-[#f97316] focus:bg-white">
                            @error('email')
                                <p class="mt-2 text-xs font-normal leading-[1.3] tracking-[0.12px] text-[#322d2a]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-4">
                                <label for="password" class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a] underline-offset-4 hover:underline">Lupa password?</a>
                                @endif
                            </div>
                            <div class="relative mt-2">
                                <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Masukkan password" class="w-full rounded-[18px] border @error('password') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/20 bg-[#f0efe9] @enderror px-5 py-4 pr-14 text-sm font-normal tracking-[0.14px] text-[#322d2a] outline-none transition placeholder:text-[#8b8b8b] focus:border-[#f97316] focus:bg-white">
                                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-5 text-sm font-normal text-[#322d2a]" aria-label="Tampilkan password">Show</button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-xs font-normal leading-[1.3] tracking-[0.12px] text-[#322d2a]">{{ $message }}</p>
                            @enderror
                        </div>

                        <label class="flex cursor-pointer items-center gap-3 text-sm font-normal text-[#322d2a]">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-[#322d2a] text-[#322d2a] focus:ring-[#322d2a]">
                            <span>Ingat saya</span>
                        </label>

                        <button type="submit" class="flex w-full items-center justify-center rounded-full bg-[#f97316] px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#ea580c]">
                            Masuk ke Akun
                        </button>
                    </form>

                    <div class="my-7 flex items-center gap-4">
                        <div class="h-px flex-1 bg-[#322d2a]"></div>
                        <span class="text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">Kandidat Baru</span>
                        <div class="h-px flex-1 bg-[#322d2a]"></div>
                    </div>

                    <a href="{{ route('register') }}" class="flex w-full items-center justify-center rounded-full border border-[#f97316] bg-transparent px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-[#f97316] transition hover:bg-[#fff3e8]">
                        Buat Akun Kandidat
                    </a>

                    <p class="mt-6 text-center text-xs font-normal leading-[1.3] tracking-[0.12px] text-[#8b8b8b]">
                        Dengan masuk ke portal, Anda menggunakan layanan Career Indodata sesuai kebijakan perusahaan.
                    </p>
                    <div class="mt-4 text-center">
                        <a href="{{ route('career.home') }}" class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a] underline-offset-4 hover:underline">← Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            password.type = password.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-layouts.app>
