<x-layouts.app title="Daftar Kandidat - Career Indodata">
    <section class="min-h-[calc(100vh-80px)] bg-[#f0efe9] px-4 py-12 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto grid max-w-[1200px] gap-12 lg:grid-cols-[0.95fr_1.05fr] lg:items-stretch">
            <div
                class="relative min-h-[640px] overflow-hidden rounded-[80px] bg-cover bg-center"
                style="background-image: url('https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1400&q=85');"
            >
                <div class="absolute inset-x-8 top-8 flex items-center justify-between rounded-2xl border border-white/20 bg-[#322d2a]/75 px-6 py-5 text-white backdrop-blur-md sm:inset-x-12 sm:top-12">
                    <div class="text-sm font-normal tracking-[0.14px]">Career Indodata</div>
                    <div class="hidden items-center gap-6 text-sm font-normal tracking-[0.14px] sm:flex">
                        <span>Profile</span>
                        <span>Apply</span>
                        <span>Track</span>
                    </div>
                </div>

                <div class="absolute inset-x-8 bottom-8 sm:inset-x-12 sm:bottom-12">
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-white">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        Candidate Registration
                    </div>
                    <h1 class="mt-6 max-w-2xl text-[44px] font-light leading-none tracking-[0.52px] text-white md:text-[52px]">
                        Mulai perjalanan karier Anda bersama Indodata.
                    </h1>
                    <p class="mt-6 max-w-xl text-sm font-normal leading-[1.3] tracking-[0.14px] text-white/90">
                        Buat akun kandidat untuk menemukan peluang kerja, melengkapi profil, mengunggah dokumen, dan mengikuti proses seleksi secara online.
                    </p>
                </div>
            </div>

            <div class="flex items-center rounded-[56px] border border-white bg-white px-6 py-10 sm:px-10 lg:px-12">
                <div class="w-full">
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        Create Account
                    </div>
                    <h2 class="mt-6 text-[40px] font-light leading-none tracking-[0.52px] text-[#322d2a] md:text-[52px]">
                        Daftar Kandidat
                    </h2>
                    <p class="mt-5 max-w-lg text-sm font-normal leading-[1.3] tracking-[0.14px] text-[#8b8b8b]">
                        Lengkapi data berikut untuk membuat akun Career Indodata. Setelah submit, kode OTP akan dikirim ke email Anda untuk verifikasi.
                    </p>

                    @if ($errors->any())
                        <div class="mt-6 rounded-xl border border-[#322d2a] bg-[#f0efe9] p-4 text-sm font-normal leading-[1.3] text-[#322d2a]">
                            Registrasi belum berhasil. Silakan periksa kembali data yang Anda masukkan.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.store') }}" class="mt-8 space-y-5">
                        @csrf

                        <div>
                            <label for="name" class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Nama Lengkap</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama sesuai identitas" class="mt-2 w-full rounded-[18px] border @error('name') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/20 bg-[#f0efe9] @enderror px-5 py-4 text-sm font-normal tracking-[0.14px] text-[#322d2a] outline-none transition placeholder:text-[#8b8b8b] focus:border-[#f97316] focus:bg-white">
                            @error('name')<p class="mt-2 text-xs font-normal leading-[1.3] tracking-[0.12px] text-[#322d2a]">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="email" class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@email.com" class="mt-2 w-full rounded-[18px] border @error('email') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/20 bg-[#f0efe9] @enderror px-5 py-4 text-sm font-normal tracking-[0.14px] text-[#322d2a] outline-none transition placeholder:text-[#8b8b8b] focus:border-[#f97316] focus:bg-white">
                            @error('email')<p class="mt-2 text-xs font-normal leading-[1.3] tracking-[0.12px] text-[#322d2a]">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="password" class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Password</label>
                            <div class="relative mt-2">
                                <input id="password" name="password" type="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" class="w-full rounded-[18px] border @error('password') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/20 bg-[#f0efe9] @enderror px-5 py-4 pr-14 text-sm font-normal tracking-[0.14px] text-[#322d2a] outline-none transition placeholder:text-[#8b8b8b] focus:border-[#f97316] focus:bg-white">
                                <button type="button" onclick="toggleRegisterPassword('password')" class="absolute inset-y-0 right-5 text-sm font-normal text-[#322d2a]" aria-label="Tampilkan password">Show</button>
                            </div>
                            @error('password')<p class="mt-2 text-xs font-normal leading-[1.3] tracking-[0.12px] text-[#322d2a]">{{ $message }}</p>@enderror
                            <p class="mt-2 text-xs font-normal leading-[1.3] tracking-[0.12px] text-[#8b8b8b]">Gunakan minimal 8 karakter dan kombinasi yang aman.</p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Konfirmasi Password</label>
                            <div class="relative mt-2">
                                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Ulangi password" class="w-full rounded-[18px] border border-[#322d2a]/20 bg-[#f0efe9] px-5 py-4 pr-14 text-sm font-normal tracking-[0.14px] text-[#322d2a] outline-none transition placeholder:text-[#8b8b8b] focus:border-[#f97316] focus:bg-white">
                                <button type="button" onclick="toggleRegisterPassword('password_confirmation')" class="absolute inset-y-0 right-5 text-sm font-normal text-[#322d2a]" aria-label="Tampilkan konfirmasi password">Show</button>
                            </div>
                        </div>

                        <button type="submit" class="flex w-full items-center justify-center rounded-full bg-[#f97316] px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#ea580c]">
                            Buat Akun & Kirim OTP
                        </button>
                    </form>

                    <div class="my-7 flex items-center gap-4">
                        <div class="h-px flex-1 bg-[#322d2a]"></div>
                        <span class="text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">Sudah Terdaftar?</span>
                        <div class="h-px flex-1 bg-[#322d2a]"></div>
                    </div>

                    <a href="{{ route('login') }}" class="flex w-full items-center justify-center rounded-full border border-[#f97316] bg-transparent px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-[#f97316] transition hover:bg-[#fff3e8]">
                        Login ke Akun
                    </a>

                    <p class="mt-6 text-center text-xs font-normal leading-[1.3] tracking-[0.12px] text-[#8b8b8b]">
                        Verifikasi OTP email diperlukan sebelum akun dapat digunakan.
                    </p>
                    <div class="mt-4 text-center">
                        <a href="{{ route('career.home') }}" class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a] underline-offset-4 hover:underline">← Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function toggleRegisterPassword(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-layouts.app>
