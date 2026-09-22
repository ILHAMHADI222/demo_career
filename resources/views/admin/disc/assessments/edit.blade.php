<x-layouts.app title="Edit Asesmen Psikotes - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">

            <div class="mb-5 flex items-center justify-between">
                <a href="{{ route('admin.disc.assessments.index') }}"
                   class="inline-flex items-center gap-1.5 rounded-full border border-[#322d2a]/15 bg-white px-4 py-1.5 text-xs font-medium text-[#322d2a] shadow-sm transition hover:bg-[#f0efe9]">
                    ← Kembali ke Daftar Asesmen
                </a>
                <a href="{{ route('admin.disc.assessments.show', $assessment) }}"
                   class="inline-flex items-center gap-1.5 rounded-full bg-[#322d2a] px-4 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-[#f97316]">
                    Detail Peserta →
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 flex items-center gap-2.5 rounded-[20px] border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-medium text-emerald-800 shadow-xs">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-[10px]">✓</span>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 flex items-center gap-2.5 rounded-[20px] border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-medium text-rose-800 shadow-xs">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-rose-600 text-white font-bold text-[10px]">✕</span>
                    <p>{{ $errors->first() }}</p>
                </div>
            @endif

            <div class="overflow-hidden rounded-[28px] sm:rounded-[32px] border border-white bg-white p-5 sm:p-7 shadow-sm">
                <div class="border-b border-[#322d2a]/10 pb-4">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3.5 py-1 text-xs font-semibold text-blue-700">
                        ✏️ Edit Psychometric Assessment
                    </span>
                    <h1 class="mt-2 text-xl sm:text-2xl font-light text-[#322d2a]">
                        Edit Jadwal <span class="text-[#f97316]">Asesmen Psikotes</span>
                    </h1>
                    <p class="mt-1 text-xs text-[#6f6b67]">
                        Perbarui konfigurasi jadwal, durasi pengerjaan Sub-Tes 1 dan 2, bank soal, serta visibilitas hasil.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.disc.assessments.update', $assessment) }}" class="mt-5 space-y-4 text-xs">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Nama / Judul Asesmen <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', $assessment->title) }}"
                            required
                            class="mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                        >
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Kode Unik Asesmen <span class="text-rose-500">*</span></label>
                            <input
                                type="text"
                                name="code"
                                value="{{ old('code', $assessment->code) }}"
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
                                    <option value="{{ $bank->id }}" {{ old('question_bank_id', $assessment->question_bank_id) == $bank->id ? 'selected' : '' }}>
                                        {{ $bank->name }} (v{{ $bank->version }})
                                    </option>
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
                                        value="{{ old('duration_stage_1', $assessment->duration_stage_1 ?: 10) }}"
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
                                        value="{{ old('duration_stage_2', $assessment->duration_stage_2 ?: 10) }}"
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
                                value="{{ old('start_at', $assessment->start_at ? $assessment->start_at->format('Y-m-d\TH:i') : '') }}"
                                class="mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                            >
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Waktu Berakhir (Opsional)</label>
                            <input
                                type="datetime-local"
                                name="end_at"
                                value="{{ old('end_at', $assessment->end_at ? $assessment->end_at->format('Y-m-d\TH:i') : '') }}"
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
                                <option value="HRD_ONLY" {{ old('result_visibility', $assessment->result_visibility) === 'HRD_ONLY' ? 'selected' : '' }}>Hanya HRD (Peserta tidak melihat grafik nilai)</option>
                                <option value="PARTICIPANT_AFTER_COMPLETION" {{ old('result_visibility', $assessment->result_visibility) === 'PARTICIPANT_AFTER_COMPLETION' ? 'selected' : '' }}>Peserta melihat profil setelah selesai</option>
                                <option value="PARTICIPANT_AFTER_HRD_APPROVAL" {{ old('result_visibility', $assessment->result_visibility) === 'PARTICIPANT_AFTER_HRD_APPROVAL' ? 'selected' : '' }}>Peserta melihat hasil setelah disetujui HRD</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Status Publikasi <span class="text-rose-500">*</span></label>
                            <select
                                name="status"
                                class="mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"
                            >
                                <option value="DRAFT" {{ old('status', $assessment->status) === 'DRAFT' ? 'selected' : '' }}>DRAFT (Belum dapat dikerjakan)</option>
                                <option value="PUBLISHED" {{ old('status', $assessment->status) === 'PUBLISHED' ? 'selected' : '' }}>PUBLISHED (Aktif & Siap Dikerjakan)</option>
                                <option value="CLOSED" {{ old('status', $assessment->status) === 'CLOSED' ? 'selected' : '' }}>CLOSED (Ditutup / Selesai)</option>
                                <option value="ARCHIVED" {{ old('status', $assessment->status) === 'ARCHIVED' ? 'selected' : '' }}>ARCHIVED (Diarsipkan)</option>
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
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </section>
</x-layouts.app>
