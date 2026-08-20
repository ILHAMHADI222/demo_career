<x-layouts.app title="Verifikasi OTP - Career Indodata">
    <section class="min-h-[calc(100vh-80px)] bg-[#f0efe9] px-4 pb-12 pt-28 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto grid max-w-[1200px] gap-8 lg:grid-cols-[0.92fr_1.08fr] lg:items-stretch">
            <div class="relative min-h-[520px] overflow-hidden rounded-[56px] bg-[#322d2a] p-8 text-white sm:p-10 lg:p-12">
                <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-[#f97316]/25 blur-3xl"></div>
                <div class="absolute -bottom-24 left-10 h-56 w-56 rounded-full bg-[#f97316]/10 blur-3xl"></div>

                <div class="relative z-10 flex h-full flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-white">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                            Email Verification
                        </div>
                        <h1 class="mt-8 text-[44px] font-light leading-none tracking-[0.52px] md:text-[52px]">
                            Satu langkah sebelum akses portal.
                        </h1>
                        <p class="mt-6 max-w-md text-sm font-normal leading-[1.5] tracking-[0.14px] text-white/75">
                            Kode OTP memastikan akun kandidat terhubung ke email yang benar dan aman digunakan untuk proses rekrutmen Hallonet.
                        </p>
                    </div>

                    <div class="mt-10 rounded-[32px] border border-white/15 bg-white/10 p-6 backdrop-blur-md">
                        <p class="text-xs font-normal uppercase tracking-[0.12em] text-white/60">Kode dikirim ke</p>
                        <p class="mt-3 break-all text-base font-normal tracking-[0.16px] text-white">{{ $user->email }}</p>
                        <div class="mt-5 flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#f97316]">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                            Berlaku 10 menit
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-[56px] border border-white bg-white px-6 py-10 sm:px-10 md:px-12">
                <div class="mx-auto max-w-xl">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-[24px] bg-[#fff3e8] text-[28px] font-light text-[#f97316]">
                        ✉
                    </div>

                    <div class="mt-8 text-center">
                        <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                            OTP Code
                        </div>
                        <h2 class="mt-6 text-[40px] font-light leading-none tracking-[0.52px] text-[#322d2a] md:text-[52px]">
                            Masukkan Kode OTP
                        </h2>
                        <p class="mx-auto mt-5 max-w-lg text-sm font-normal leading-[1.5] tracking-[0.14px] text-[#6f6b67]">
                            Kami sudah mengirimkan kode OTP 6 digit ke email <span class="text-[#f97316]">{{ $user->email }}</span>.
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="mt-6 rounded-[24px] border border-[#f97316]/30 bg-[#fff3e8] p-4 text-sm font-normal leading-[1.5] text-[#322d2a]">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-6 rounded-[24px] border border-[#f97316]/40 bg-[#fff3e8] p-4 text-sm font-normal leading-[1.5] text-[#322d2a]">
                            Silakan periksa kode OTP yang Anda masukkan.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.otp.verify') }}" class="mt-8 space-y-5">
                        @csrf

                        <div>
                            <label for="otp" class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Kode OTP</label>
                            <input id="otp" name="otp" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" value="{{ old('otp') }}" required autofocus placeholder="123456" class="mt-2 w-full rounded-[28px] border @error('otp') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/20 bg-[#f0efe9] @enderror px-5 py-5 text-center text-[34px] font-light leading-none tracking-[0.42em] text-[#322d2a] outline-none transition placeholder:text-[#8b8b8b]/45 focus:border-[#f97316] focus:bg-white">
                            @error('otp')
                                <p class="mt-2 text-xs font-normal leading-[1.4] tracking-[0.12px] text-[#f97316]">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="flex w-full items-center justify-center rounded-full bg-[#f97316] px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#ea580c]">
                            Verifikasi & Masuk
                        </button>
                    </form>

                    <form method="POST" action="{{ route('register.otp.resend') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="flex w-full items-center justify-center rounded-full border border-[#322d2a]/40 bg-transparent px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-[#322d2a] transition hover:border-[#f97316] hover:bg-[#fff3e8] hover:text-[#f97316]">
                            Kirim Ulang Kode OTP
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a] underline-offset-4 transition hover:text-[#f97316] hover:underline">← Kembali ke Form Registrasi</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
