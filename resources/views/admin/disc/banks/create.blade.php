<x-layouts.app title="Buat Bank Soal DISC - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-16 pt-28 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">

            <div class="mb-6 flex items-center justify-between">
                <a href="{{ route('admin.disc.banks.index') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    ← Kembali ke Daftar
                </a>
            </div>

            <div class="overflow-hidden rounded-[40px] border border-white/80 bg-white p-8 shadow-xl shadow-slate-900/5 md:p-10">
                <div class="border-b border-slate-100 pb-6">
                    <span class="inline-flex items-center gap-2 rounded-full bg-orange-50 px-3.5 py-1 text-xs font-semibold text-[#f97316]">
                        Formulir Master
                    </span>
                    <h1 class="mt-3 text-2xl font-light text-[#322d2a] md:text-3xl">
                        Buat <span class="font-normal text-[#f97316]">Bank Soal DISC Baru</span>
                    </h1>
                    <p class="mt-2 text-xs text-slate-500">
                        Inisialisasi wadah bank soal DISC. Setelah dibuat, Anda dapat mengedit 24 butir pertanyaan dan mengatur kunci scoring.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.disc.banks.store') }}" class="mt-6 space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Nama Bank Soal <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', 'DISC Standard Recruitment ISP') }}"
                            required
                            placeholder="Contoh: DISC Master ISP v2.0"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-[#f97316] focus:bg-white focus:outline-none"
                        >
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Kode Unik <span class="text-rose-500">*</span></label>
                            <input
                                type="text"
                                name="code"
                                value="{{ old('code', 'DISC-ISP-' . strtoupper(Str::random(4))) }}"
                                required
                                placeholder="Contoh: DISC-ISP-2026"
                                class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 font-mono text-sm uppercase text-slate-800 focus:border-[#f97316] focus:bg-white focus:outline-none"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Versi <span class="text-rose-500">*</span></label>
                            <input
                                type="text"
                                name="version"
                                value="{{ old('version', '1.0') }}"
                                required
                                placeholder="Contoh: 1.0"
                                class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-[#f97316] focus:bg-white focus:outline-none"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Deskripsi / Catatan Peruntukan</label>
                        <textarea
                            name="description"
                            rows="4"
                            placeholder="Catatan mengenai peruntukan bank soal ini..."
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-800 focus:border-[#f97316] focus:bg-white focus:outline-none"
                        >{{ old('description') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('admin.disc.banks.index') }}"
                           class="rounded-full px-6 py-3 text-xs font-semibold text-slate-600 hover:text-slate-900">
                            Batal
                        </a>
                        <button type="submit"
                                class="rounded-full bg-[#f97316] px-7 py-3 text-xs font-semibold text-white shadow-lg shadow-[#f97316]/25 transition hover:bg-[#ea580c]">
                            Simpan & Lanjutkan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </section>
</x-layouts.app>
