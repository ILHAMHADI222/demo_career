<x-layouts.app title="Buat Bank Soal - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[960px]">

            {{-- BACK BUTTON --}}
            <div class="mb-5">
                <a href="{{ route('admin.disc.banks.index') }}"
                   class="inline-flex items-center gap-1.5 rounded-full border border-[#322d2a]/15 bg-white px-4 py-1.5 text-xs font-medium text-[#322d2a] shadow-sm transition hover:bg-[#f0efe9]">
                    ← Kembali ke Bank Soal
                </a>
            </div>

            {{-- FORM CARD --}}
            <div class="rounded-[28px] sm:rounded-[32px] border border-white bg-white p-6 sm:p-8 shadow-sm">
                <div class="border-b border-[#322d2a]/10 pb-5">
                    <span class="rounded-full bg-[#f0efe9] px-3 py-1 text-xs font-semibold text-[#f97316]">
                        New Master Package
                    </span>
                    <h1 class="mt-2 text-2xl font-light text-[#322d2a]">
                        Buat <span class="text-[#f97316]">Bank Soal Baru</span>
                    </h1>
                    <p class="mt-1 text-xs text-[#6f6b67]">
                        Wadah bank soal terpadu untuk 3 sub-tes: Deret Hitung, Analogi Verbal, dan Tes Kepribadian.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.disc.banks.store') }}" class="mt-5 space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Nama Bank Soal <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', 'Bank Soal Standar Psikotes PT Indodata Solusi Persada') }}"
                            required
                            placeholder="Contoh: Paket Psikotes Recruitment ISP 2026"
                            class="mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                        >
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Kode Unik <span class="text-rose-500">*</span></label>
                            <input
                                type="text"
                                name="code"
                                value="{{ old('code', 'PSI-ISP-' . strtoupper(Str::random(4))) }}"
                                required
                                placeholder="Contoh: PSI-ISP-2026"
                                class="mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 font-mono text-xs uppercase text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                            >
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Versi <span class="text-rose-500">*</span></label>
                            <input
                                type="text"
                                name="version"
                                value="{{ old('version', '1.0') }}"
                                required
                                placeholder="Contoh: 1.0"
                                class="mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Deskripsi / Catatan Peruntukan</label>
                        <textarea
                            name="description"
                            rows="3"
                            placeholder="Catatan mengenai paket 3 sub-tes soal ini..."
                            class="mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] p-3 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                        >{{ old('description') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3">
                        <a href="{{ route('admin.disc.banks.index') }}"
                           class="rounded-full bg-[#f0efe9] px-5 py-2 text-xs font-medium text-[#6f6b67] hover:text-[#322d2a]">
                            Batal
                        </a>
                        <button type="submit"
                                class="rounded-full bg-[#f97316] px-6 py-2 text-xs font-medium text-white shadow-sm transition hover:bg-[#ea580c]">
                            Simpan & Kelola Butir Soal
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </section>
</x-layouts.app>
