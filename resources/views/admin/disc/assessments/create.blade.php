<x-layouts.app title="Buat Asesmen Psikotes - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">

            <div class="mb-5 flex items-center justify-between">
                <a href="{{ route('admin.disc.assessments.index') }}"
                   class="inline-flex items-center gap-1.5 rounded-full border border-[#322d2a]/15 bg-white px-4 py-1.5 text-xs font-medium text-[#322d2a] shadow-sm transition hover:bg-[#f0efe9]">
                    ← Kembali ke Daftar Asesmen
                </a>
            </div>

            <div class="overflow-hidden rounded-[28px] sm:rounded-[32px] border border-white bg-white p-5 sm:p-7 shadow-sm">
                <div class="border-b border-[#322d2a]/10 pb-4">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-orange-50 px-3.5 py-1 text-xs font-semibold text-[#f97316]">
                        Psychometric Evaluation Scheduler
                    </span>
                    <h1 class="mt-2 text-xl sm:text-2xl font-light text-[#322d2a]">
                        Jadwalkan <span class="text-[#f97316]">Asesmen Psikotes Baru</span>
                    </h1>
                    <p class="mt-1 text-xs text-[#6f6b67]">
                        Pilih bank soal psikotes, atur durasi pengerjaan Sub-Tes 1 dan 2, serta undang kandidat pelamar.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.disc.assessments.store') }}" class="mt-5 space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Nama / Judul Asesmen <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', 'Psikotes Recruitment ISP ' . date('Y')) }}"
                            required
                            placeholder="Contoh: Seleksi Psikotes Calon Karyawan 2026"
                            class="mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                        >
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Kode Unik Asesmen <span class="text-rose-500">*</span></label>
                            <input
                                type="text"
                                name="code"
                                value="{{ old('code', 'PSI-' . date('Ymd') . '-' . strtoupper(Str::random(3))) }}"
                                required
                                class="mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 font-mono text-xs uppercase text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                            >
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Pilih Bank Soal Psikotes <span class="text-rose-500">*</span></label>
                            <select
                                name="question_bank_id"
                                required
                                class="mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                            >
                                @foreach ($questionBanks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }} (v{{ $bank->version }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- DURASI SUB-TES SETTINGS --}}
                    <div class="rounded-[20px] border border-[#322d2a]/10 bg-[#fbfaf8] p-4">
                        <p class="text-[11px] font-bold text-[#322d2a]">⏱ Pengaturan Waktu Tiap Sub-Tes Psikotes:</p>
                        <div class="mt-3 grid gap-3 sm:grid-cols-3">
                            <div>
                                <label class="block text-[10px] font-medium text-blue-900">Durasi Sub-Tes 1: Deret Hitung *</label>
                                <div class="mt-1 flex items-center gap-1.5">
                                    <input
                                        type="number"
                                        name="duration_stage_1"
                                        value="{{ old('duration_stage_1', 10) }}"
                                        min="1"
                                        max="120"
                                        required
                                        class="w-full rounded-[10px] border border-blue-200 bg-white px-3 py-1.5 font-mono text-xs font-bold text-blue-950 focus:border-blue-500 outline-none"
                                    >
                                    <span class="text-[11px] text-[#6f6b67]">Menit</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-medium text-emerald-900">Durasi Sub-Tes 2: Analogi Verbal *</label>
                                <div class="mt-1 flex items-center gap-1.5">
                                    <input
                                        type="number"
                                        name="duration_stage_2"
                                        value="{{ old('duration_stage_2', 10) }}"
                                        min="1"
                                        max="120"
                                        required
                                        class="w-full rounded-[10px] border border-emerald-200 bg-white px-3 py-1.5 font-mono text-xs font-bold text-emerald-950 focus:border-emerald-500 outline-none"
                                    >
                                    <span class="text-[11px] text-[#6f6b67]">Menit</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-medium text-purple-900">Sub-Tes 3: Tes Kepribadian</label>
                                <div class="mt-1 rounded-[10px] border border-purple-200 bg-purple-50 px-3 py-1.5 text-xs font-semibold text-purple-800">
                                    ✨ Tanpa Batas Waktu
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Waktu Mulai (Opsional)</label>
                            <input
                                type="datetime-local"
                                name="start_at"
                                value="{{ old('start_at') }}"
                                class="mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                            >
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Waktu Berakhir (Opsional)</label>
                            <input
                                type="datetime-local"
                                name="end_at"
                                value="{{ old('end_at') }}"
                                class="mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                            >
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Visibilitas Hasil Peserta <span class="text-rose-500">*</span></label>
                            <select
                                name="result_visibility"
                                class="mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                            >
                                <option value="HRD_ONLY">Hanya HRD (Peserta tidak melihat grafik nilai)</option>
                                <option value="PARTICIPANT_AFTER_COMPLETION">Peserta melihat profil setelah selesai</option>
                                <option value="PARTICIPANT_AFTER_HRD_APPROVAL">Peserta melihat hasil setelah disetujui HRD</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Status Publikasi <span class="text-rose-500">*</span></label>
                            <select
                                name="status"
                                class="mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                            >
                                <option value="PUBLISHED">PUBLISHED (Langsung Aktif)</option>
                                <option value="DRAFT">DRAFT (Disimpan Dulu)</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3">
                        <a href="{{ route('admin.disc.assessments.index') }}"
                           class="rounded-full bg-[#f0efe9] px-5 py-2 text-xs font-medium text-[#6f6b67] hover:text-[#322d2a]">
                            Batal
                        </a>
                        <button type="submit"
                                class="rounded-full bg-[#f97316] px-6 py-2 text-xs font-medium text-white shadow-sm transition hover:bg-[#ea580c]">
                            Simpan & Terbitkan Asesmen
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </section>
</x-layouts.app>
