<x-layouts.app title="Verifikasi OTP - Career Indodata">
    <section class="relative min-h-[calc(100vh-80px)] overflow-hidden bg-slate-50">
        <div class="absolute -left-32 top-20 h-96 w-96 rounded-full bg-orange-200/30 blur-3xl"></div>
        <div class="absolute -right-32 bottom-10 h-96 w-96 rounded-full bg-amber-200/30 blur-3xl"></div>

        <div class="relative mx-auto flex min-h-[calc(100vh-80px)] max-w-3xl items-center px-4 py-12 sm:px-6 lg:px-8">
            <div class="w-full overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-6 shadow-2xl shadow-slate-300/30 sm:p-8 md:p-10">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-100 text-2xl font-black text-orange-600">
                    ✉
                </div>

                <div class="mt-6 text-center">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">
                        Email Verification
                    </p>
                    <h1 class="mt-3 text-3xl font-black tracking-tight text-slate-950 md:text-4xl">
                        Masukkan Kode OTP
                    </h1>
                    <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-500">
                        Kami sudah mengirimkan kode OTP 6 digit ke email
                        <span class="font-black text-slate-700">{{ $user->email }}</span>.
                        Kode berlaku selama 10 menit.
                    </p>
                </div>

                @if (session('success'))
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold leading-6 text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold leading-6 text-red-700">
                        Silakan periksa kode OTP yang Anda masukkan.
                    </div>
                @endif

                <form method="POST" action="{{ route('register.otp.verify') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="otp" class="text-sm font-black text-slate-700">
                            Kode OTP
                        </label>
                        <input
                            id="otp"
                            name="otp"
                            type="text"
                            inputmode="numeric"
                            pattern="[0-9]{6}"
                            maxlength="6"
                            value="{{ old('otp') }}"
                            required
                            autofocus
                            placeholder="123456"
                            class="mt-2 w-full rounded-2xl border @error('otp') border-red-300 bg-red-50 @else border-slate-200 bg-slate-50 @enderror px-5 py-4 text-center text-2xl font-black tracking-[0.35em] text-slate-800 outline-none transition placeholder:text-slate-300 focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-100"
                        >

                        @error('otp')
                            <p class="mt-2 text-xs font-semibold text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-3 rounded-2xl bg-orange-500 px-5 py-3.5 font-black text-white shadow-lg shadow-orange-500/25 transition duration-300 hover:-translate-y-0.5 hover:bg-orange-600"
                    >
                        Verifikasi & Masuk
                        <span>→</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('register.otp.resend') }}" class="mt-4">
                    @csrf
                    <button
                        type="submit"
                        class="flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3.5 text-sm font-black text-slate-700 transition hover:border-orange-200 hover:bg-orange-50 hover:text-orange-600"
                    >
                        Kirim Ulang Kode OTP
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-xs font-black text-slate-500 transition hover:text-orange-500">
                        <span>←</span>
                        Kembali ke Form Registrasi
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
