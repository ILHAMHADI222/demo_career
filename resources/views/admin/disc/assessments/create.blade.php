<x-layouts.app title="Buat Asesmen DISC - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-16 pt-28 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">

            <div class="mb-6 flex items-center justify-between">
                <a href="{{ route('admin.disc.assessments.index') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    ← Kembali ke Daftar Asesmen
                </a>
            </div>

            <div class="overflow-hidden rounded-[40px] border border-white/80 bg-white p-8 shadow-xl shadow-slate-900/5 md:p-10">
                <div class="border-b border-slate-100 pb-6">
                    <span class="inline-flex items-center gap-2 rounded-full bg-orange-50 px-3.5 py-1 text-xs font-semibold text-[#f97316]">
                        Assessment Scheduler
                    </span>
                    <h1 class="mt-3 text-2xl font-light text-[#322d2a] md:text-3xl">
                        Jadwalkan <span class="font-normal text-[#f97316]">Asesmen DISC Baru</span>
                    </h1>
                    <p class="mt-2 text-xs text-slate-500">
                        Pilih bank soal yang sudah dipublikasikan, tentukan batasan waktu, dan undang peserta pelamar.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.disc.assessments.store') }}" class="mt-6 space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Nama / Judul Asesmen <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', 'Psikotes DISC Recruitment Batch ' . date('Y')) }}"
                            required
                            placeholder="Contoh: Seleksi DISC Calon Software Engineer 2026"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-[#f97316] focus:bg-white focus:outline-none"
                        >
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Kode Unik Asesmen <span class="text-rose-500">*</span></label>
                            <input
                                type="text"
                                name="code"
                                value="{{ old('code', 'ASSESS-DISC-' . date('Ymd') . '-' . strtoupper(Str::random(3))) }}"
                                required
                                class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 font-mono text-sm uppercase text-slate-800 focus:border-[#f97316] focus:bg-white focus:outline-none"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Pilih Bank Soal <span class="text-rose-500">*</span></label>
                            <select
                                name="question_bank_id"
                                required
                                class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-[#f97316] focus:bg-white focus:outline-none"
                            >
                                @foreach ($questionBanks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }} (v{{ $bank->version }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Durasi Pengerjaan (Menit) <span class="text-rose-500">*</span></label>
                            <input
                                type="number"
                                name="duration_minutes"
                                value="{{ old('duration_minutes', 30) }}"
                                min="5"
                                max="180"
                                required
                                class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-[#f97316] focus:bg-white focus:outline-none"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Waktu Mulai (Opsional)</label>
                            <input
                                type="datetime-local"
                                name="start_at"
                                value="{{ old('start_at') }}"
                                class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-800 focus:border-[#f97316] focus:bg-white focus:outline-none"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Waktu Berakhir (Opsional)</label>
                            <input
                                type="datetime-local"
                                name="end_at"
                                value="{{ old('end_at') }}"
                                class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-800 focus:border-[#f97316] focus:bg-white focus:outline-none"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Visibilitas Hasil Peserta <span class="text-rose-500">*</span></label>
                            <select
                                name="result_visibility"
                                class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-[#f97316] focus:bg-white focus:outline-none"
                            >
                                <option value="HRD_ONLY">Hanya HRD (Peserta tidak melihat grafik/profil)</option>
                                <option value="PARTICIPANT_AFTER_COMPLETION">Peserta langsung melihat profil setelah submit</option>
                                <option value="PARTICIPANT_AFTER_HRD_APPROVAL">Peserta melihat hasil setelah disetujui HRD</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Status Publikasi <span class="text-rose-500">*</span></label>
                            <select
                                name="status"
                                class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-[#f97316] focus:bg-white focus:outline-none"
                            >
                                <option value="PUBLISHED">PUBLISHED (Langsung Aktif)</option>
                                <option value="DRAFT">DRAFT (Disimpan Dulu)</option>
                            </select>
                        </div>
                    </div>

                    {{-- SECURITY & RANDOMIZATION OPTIONS --}}
                    <div class="rounded-3xl border border-slate-200 bg-slate-50/70 p-5">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-700">Pengaturan Acak (Randomization)</p>
                        <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <label class="flex items-center gap-3 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="randomize_questions" value="1" class="h-4 w-4 rounded border-slate-300 text-[#f97316] focus:ring-[#f97316]">
                                <span>Acak Urutan Soal (Tiap peserta mendapat urutan berbeda namun konsisten per sesi)</span>
                            </label>
                            <label class="flex items-center gap-3 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="randomize_options" value="1" class="h-4 w-4 rounded border-slate-300 text-[#f97316] focus:ring-[#f97316]">
                                <span>Acak Urutan 4 Pilihan Jawaban per Soal</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('admin.disc.assessments.index') }}"
                           class="rounded-full px-6 py-3 text-xs font-semibold text-slate-600 hover:text-slate-900">
                            Batal
                        </a>
                        <button type="submit"
                                class="rounded-full bg-[#f97316] px-7 py-3 text-xs font-semibold text-white shadow-lg shadow-[#f97316]/25 transition hover:bg-[#ea580c]">
                            Simpan & Terbitkan Asesmen
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </section>
</x-layouts.app>
