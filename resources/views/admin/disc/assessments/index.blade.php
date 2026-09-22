<x-layouts.app title="Kelola Asesmen Psikotes - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">

            {{-- HERO HEADER --}}
            <div class="relative overflow-hidden rounded-[32px] sm:rounded-[36px] bg-[#322d2a] p-6 sm:p-7 md:p-8 text-white shadow-lg">
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#f97316]/20 blur-3xl"></div>
                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/15 blur-3xl"></div>

                <div class="relative z-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3.5 py-1 text-xs font-medium uppercase tracking-[0.16em] text-white backdrop-blur">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#f97316]"></span>
                            Psychometric Lifecycle
                        </span>
                        <h1 class="mt-2.5 text-2xl sm:text-3xl md:text-4xl font-light tracking-tight text-white">
                            Jadwal & Hasil <span class="text-[#f97316]">Asesmen</span>
                        </h1>
                        <p class="mt-1.5 max-w-2xl text-xs sm:text-sm font-normal text-white/75">
                            Kelola jadwal asesmen 3 sub-tes, undang kandidat, pantau pengerjaan secara real-time, dan evaluasi hasil tes.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <a href="{{ route('admin.disc.banks.index') }}"
                           class="inline-flex items-center gap-1.5 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold text-white backdrop-blur transition hover:bg-white/20">
                            <span>📚 Bank Soal</span>
                        </a>
                        <a href="{{ route('admin.disc.assessments.create') }}"
                           class="inline-flex items-center gap-1.5 rounded-full bg-[#f97316] px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#ea580c]">
                            <span>+ Buat Asesmen Baru</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- NOTIFIKASI FLASH --}}
            @if(session('success'))
                <div class="mt-4 flex items-center gap-2.5 rounded-[20px] border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-medium text-emerald-800 shadow-xs">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-[10px]">✓</span>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="mt-4 flex items-center gap-2.5 rounded-[20px] border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-medium text-rose-800 shadow-xs">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-rose-600 text-white font-bold text-[10px]">✕</span>
                    <p>{{ $errors->first() }}</p>
                </div>
            @endif

            {{-- STATS OVERVIEW --}}
            <div class="mt-5 grid grid-cols-2 gap-3.5 sm:grid-cols-4">
                <div class="rounded-[22px] border border-white bg-white p-4 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-[#6f6b67]">Total Asesmen</p>
                    <p class="mt-1.5 text-2xl sm:text-3xl font-light text-[#322d2a]">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-[22px] border border-white bg-white p-4 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Asesmen Aktif</p>
                    <p class="mt-1.5 text-2xl sm:text-3xl font-light text-emerald-700">{{ $stats['published'] }}</p>
                </div>
                <div class="rounded-[22px] border border-white bg-white p-4 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Total Peserta</p>
                    <p class="mt-1.5 text-2xl sm:text-3xl font-light text-blue-700">{{ $stats['total_participants'] }}</p>
                </div>
                <div class="rounded-[22px] border border-white bg-white p-4 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-purple-600">Selesai (Completed)</p>
                    <p class="mt-1.5 text-2xl sm:text-3xl font-light text-purple-700">{{ $stats['completed_sessions'] }}</p>
                </div>
            </div>

            {{-- FILTER & SEARCH --}}
            <div class="mt-4 rounded-[24px] sm:rounded-[28px] border border-white bg-white p-4 sm:p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.disc.assessments.index') }}" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div class="flex flex-wrap items-center gap-1.5">
                        <a href="{{ route('admin.disc.assessments.index') }}"
                           class="rounded-full px-3.5 py-1.5 text-xs font-medium transition {{ empty($status) ? 'bg-[#322d2a] text-white' : 'bg-[#f0efe9] text-[#6f6b67] hover:bg-[#fff3e8] hover:text-[#f97316]' }}">
                            Semua Status
                        </a>
                        <a href="{{ route('admin.disc.assessments.index', ['status' => 'PUBLISHED']) }}"
                           class="rounded-full px-3.5 py-1.5 text-xs font-medium transition {{ $status === 'PUBLISHED' ? 'bg-emerald-600 text-white' : 'bg-[#f0efe9] text-[#6f6b67] hover:bg-emerald-50 hover:text-emerald-700' }}">
                            Aktif (Published)
                        </a>
                        <a href="{{ route('admin.disc.assessments.index', ['status' => 'DRAFT']) }}"
                           class="rounded-full px-3.5 py-1.5 text-xs font-medium transition {{ $status === 'DRAFT' ? 'bg-amber-600 text-white' : 'bg-[#f0efe9] text-[#6f6b67] hover:bg-amber-50 hover:text-amber-700' }}">
                            Draft
                        </a>
                        <a href="{{ route('admin.disc.assessments.index', ['status' => 'CLOSED']) }}"
                           class="rounded-full px-3.5 py-1.5 text-xs font-medium transition {{ $status === 'CLOSED' ? 'bg-slate-800 text-white' : 'bg-[#f0efe9] text-[#6f6b67] hover:bg-slate-200' }}">
                            Closed
                        </a>
                    </div>

                    <div class="flex items-center gap-2">
                        <input
                            type="text"
                            name="keyword"
                            value="{{ $keyword }}"
                            placeholder="Cari judul atau kode asesmen..."
                            class="w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white md:w-64"
                        >
                        <button type="submit" class="rounded-full bg-[#322d2a] px-4 py-2 text-xs font-medium text-white transition hover:bg-[#f97316]">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            {{-- ASSESSMENTS TABLE --}}
            <div class="mt-4 overflow-hidden rounded-[24px] sm:rounded-[28px] border border-white bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#322d2a]/10 text-xs">
                        <thead class="bg-[#322d2a] text-left text-[11px] font-normal uppercase tracking-[0.14em] text-white">
                            <tr>
                                <th class="px-4 py-3">Nama Asesmen</th>
                                <th class="px-4 py-3">Durasi Sub-Tes</th>
                                <th class="px-4 py-3">Bank Soal</th>
                                <th class="px-4 py-3">Peserta & Sesi</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#322d2a]/10 bg-white">
                            @forelse ($assessments as $assess)
                                <tr class="transition hover:bg-[#fff8f3]">
                                    <td class="px-4 py-3.5 align-top">
                                        <div class="font-medium text-[#322d2a]">{{ $assess->title }}</div>
                                        <div class="mt-0.5 text-[10px] text-[#6f6b67]">{{ $assess->creator->name ?? 'HRD' }} • {{ $assess->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-4 py-3.5 align-top">
                                        <span class="rounded-md bg-[#f0efe9] px-2 py-0.5 font-mono text-[11px] font-bold text-[#322d2a]">{{ $assess->code }}</span>
                                        <div class="mt-1 flex items-center gap-1.5 text-[10px] font-mono">
                                            <span class="text-blue-700 font-bold">Deret: {{ $assess->duration_stage_1 ?: 10 }}m</span>
                                            <span class="text-[#6f6b67]">•</span>
                                            <span class="text-emerald-700 font-bold">Analogi: {{ $assess->duration_stage_2 ?: 10 }}m</span>
                                            <span class="text-[#6f6b67]">•</span>
                                            <span class="text-purple-700 font-bold">Kepribadian: Untimed</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 align-top">
                                        <div class="font-medium text-[#322d2a]">{{ $assess->questionBank->name ?? '-' }}</div>
                                        <div class="text-[10px] text-[#6f6b67]">Versi {{ $assess->question_bank_version }}</div>
                                    </td>
                                    <td class="px-4 py-3.5 align-top">
                                        <span class="font-semibold text-[#322d2a]">{{ $assess->participants_count }}</span> Peserta
                                        <div class="text-[10px] text-[#6f6b67]">{{ $assess->sessions_count }} Sesi</div>
                                    </td>
                                    <td class="px-4 py-3.5 align-top">
                                        @if ($assess->status === 'PUBLISHED')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-medium text-emerald-700 ring-1 ring-emerald-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                                            </span>
                                        @elseif ($assess->status === 'DRAFT')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-0.5 text-[10px] font-medium text-amber-700 ring-1 ring-amber-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Draft
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-medium text-slate-600">
                                                Closed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 align-top text-right">
                                        <div class="inline-flex flex-wrap items-center justify-end gap-1.5">
                                            @if ($assess->status === 'PUBLISHED')
                                                <form action="{{ route('admin.disc.assessments.close', $assess) }}" method="POST" class="inline" onsubmit="return confirm('Tutup asesmen ini?')">
                                                    @csrf
                                                    <button type="submit" class="rounded-full bg-rose-50 px-2.5 py-1 text-[11px] font-medium text-rose-700 ring-1 ring-rose-200 hover:bg-rose-100 transition">
                                                        ✕ Tutup
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.disc.assessments.publish', $assess) }}" method="POST" class="inline" onsubmit="return confirm('Buka asesmen sekarang?')">
                                                    @csrf
                                                    <button type="submit" class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-medium text-emerald-700 ring-1 ring-emerald-200 hover:bg-emerald-100 transition">
                                                        ✓ Buka
                                                    </button>
                                                </form>
                                            @endif

                                            <button type="button"
                                                    onclick="openEditModal({{ json_encode([
                                                        'id' => $assess->id,
                                                        'title' => $assess->title,
                                                        'code' => $assess->code,
                                                        'question_bank_id' => $assess->question_bank_id,
                                                        'duration_stage_1' => $assess->duration_stage_1 ?: 10,
                                                        'duration_stage_2' => $assess->duration_stage_2 ?: 10,
                                                        'start_at' => $assess->start_at ? $assess->start_at->format('Y-m-d\TH:i') : '',
                                                        'end_at' => $assess->end_at ? $assess->end_at->format('Y-m-d\TH:i') : '',
                                                        'result_visibility' => $assess->result_visibility ?: 'HRD_ONLY',
                                                        'status' => $assess->status,
                                                        'update_url' => route('admin.disc.assessments.update', $assess),
                                                        'show_url' => route('admin.disc.assessments.show', $assess),
                                                    ]) }})"
                                                    class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-medium text-blue-700 ring-1 ring-blue-200 hover:bg-blue-600 hover:text-white transition"
                                                    title="Edit Jadwal & Durasi Asesmen">
                                                ✏️ Edit
                                            </button>

                                            <a href="{{ route('admin.disc.assessments.monitor', $assess) }}"
                                               class="rounded-full bg-[#f0efe9] px-2.5 py-1 text-[11px] font-medium text-[#322d2a] hover:bg-[#322d2a] hover:text-white transition">
                                                🛰 Monitor
                                            </a>
                                            <a href="{{ route('admin.disc.assessments.show', $assess) }}"
                                               class="rounded-full bg-[#322d2a] px-3 py-1 text-[11px] font-medium text-white hover:bg-[#f97316] transition">
                                                Detail →
                                            </a>

                                            <button type="button"
                                                    onclick="openDeleteModal('{{ $assess->id }}', '{{ addslashes($assess->title) }}', '{{ route('admin.disc.assessments.destroy', $assess) }}', {{ $assess->sessions_count }})"
                                                    class="rounded-full bg-rose-50 px-2.5 py-1 text-[11px] font-medium text-rose-600 ring-1 ring-rose-200 hover:bg-rose-600 hover:text-white transition"
                                                    title="Hapus Asesmen">
                                                ✕ Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-xs text-[#6f6b67]">
                                        Belum ada jadwal asesmen psikotes. Silakan buat asesmen baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($assessments->hasPages())
                    <div class="border-t border-[#322d2a]/10 bg-[#f0efe9] p-3 text-xs">
                        {{ $assessments->links() }}
                    </div>
                @endif
            </div>

        </div>

        {{-- MODAL KONFIRMASI HAPUS ASESMEN --}}
        <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-delete-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative w-full max-w-md transform overflow-hidden rounded-[28px] border border-white bg-white p-6 text-left shadow-2xl transition-all">
                    <div class="flex items-center gap-3.5">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 ring-1 ring-rose-200">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-[#322d2a]" id="modal-delete-title">Hapus Jadwal Asesmen</h3>
                            <p class="text-xs text-[#6f6b67]">Konfirmasi penghapusan data asesmen</p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl bg-rose-50/70 p-3.5 border border-rose-100 text-xs text-rose-900 leading-relaxed">
                        <p class="mb-1">Apakah Anda yakin ingin menghapus jadwal asesmen <span id="deleteAssessmentTitle" class="font-bold text-[#322d2a]"></span>?</p>
                        <div id="deleteAssessmentWarning" class="mt-2 text-[11px] text-rose-700"></div>
                    </div>

                    <form id="deleteAssessmentForm" method="POST" action="" class="mt-5 flex items-center justify-end gap-2.5">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="closeDeleteModal()" class="rounded-full bg-[#f0efe9] px-4 py-2 text-xs font-medium text-[#6f6b67] hover:bg-[#e4e2dc] hover:text-[#322d2a] transition">
                            Batal
                        </button>
                        <button type="submit" id="deleteSubmitBtn" class="rounded-full bg-rose-600 px-5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-rose-700 transition">
                            Ya, Hapus Asesmen
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT ASESMEN --}}
        <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-edit-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative w-full max-w-xl transform overflow-hidden rounded-[28px] border border-white bg-white p-6 text-left shadow-2xl transition-all">
                    <div class="flex items-center justify-between border-b border-[#322d2a]/10 pb-3.5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 ring-1 ring-blue-200 text-base">
                                ✏️
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-[#322d2a]" id="modal-edit-title">Edit Jadwal Asesmen</h3>
                                <p class="text-xs text-[#6f6b67]">Perbarui judul, durasi sub-tes, bank soal & periode</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeEditModal()" class="rounded-full p-1.5 text-[#6f6b67] hover:bg-[#f0efe9] hover:text-[#322d2a]">
                            ✕
                        </button>
                    </div>

                    <form id="editAssessmentForm" method="POST" action="" class="mt-4 space-y-3.5 text-xs">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Judul Asesmen <span class="text-rose-500">*</span></label>
                            <input type="text" id="editAssessmentTitle" name="title" required class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Kode Asesmen <span class="text-rose-500">*</span></label>
                                <input type="text" id="editAssessmentCode" name="code" required class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 font-mono text-xs uppercase text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Bank Soal <span class="text-rose-500">*</span></label>
                                <select id="editAssessmentBankId" name="question_bank_id" class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                                    @foreach ($questionBanks as $qb)
                                        <option value="{{ $qb->id }}">{{ $qb->name }} (v{{ $qb->version }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-[#322d2a]/10 bg-[#f9f8f5] p-3.5">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#322d2a]">Pengaturan Waktu Sub-Tes</span>
                                <span class="text-[10px] text-[#6f6b67]">Satuan: Menit</span>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="block text-[10px] font-medium text-[#6f6b67]">Tahap 1: Deret Hitung <span class="text-rose-500">*</span></label>
                                    <div class="relative mt-1">
                                        <input type="number" id="editAssessmentDuration1" name="duration_stage_1" min="1" max="120" required class="w-full rounded-[12px] border border-[#322d2a]/15 bg-white px-3 py-1.5 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316]">
                                        <span class="absolute right-3 top-1.5 text-[10px] text-[#6f6b67]">Menit</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-[#6f6b67]">Tahap 2: Analogi Verbal <span class="text-rose-500">*</span></label>
                                    <div class="relative mt-1">
                                        <input type="number" id="editAssessmentDuration2" name="duration_stage_2" min="1" max="120" required class="w-full rounded-[12px] border border-[#322d2a]/15 bg-white px-3 py-1.5 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316]">
                                        <span class="absolute right-3 top-1.5 text-[10px] text-[#6f6b67]">Menit</span>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2 text-[10px] text-[#6f6b67]">ℹ️ Tahap 3 (Kepribadian / DISC 24 Butir) tidak memiliki batas waktu (untimed).</p>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Jadwal Mulai (Opsional)</label>
                                <input type="datetime-local" id="editAssessmentStartAt" name="start_at" class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3 py-2 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Jadwal Selesai (Opsional)</label>
                                <input type="datetime-local" id="editAssessmentEndAt" name="end_at" class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3 py-2 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Status Asesmen</label>
                                <select id="editAssessmentStatus" name="status" class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                                    <option value="DRAFT">DRAFT (Belum dapat dikerjakan)</option>
                                    <option value="PUBLISHED">PUBLISHED (Aktif & Siap Dikerjakan)</option>
                                    <option value="CLOSED">CLOSED (Ditutup / Selesai)</option>
                                    <option value="ARCHIVED">ARCHIVED (Diarsipkan)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Visibilitas Hasil</label>
                                <select id="editAssessmentVisibility" name="result_visibility" class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                                    <option value="HRD_ONLY">Hanya Tim HRD</option>
                                    <option value="PARTICIPANT_AFTER_COMPLETION">Kandidat Setelah Selesai</option>
                                    <option value="PARTICIPANT_AFTER_HRD_APPROVAL">Setelah Persetujuan HRD</option>
                                </select>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-3 flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[11px] font-semibold text-blue-900">Kelola Peserta & Detail Asesmen</p>
                                <p class="text-[10px] text-blue-700">Undang peserta, atur batas attempts, dan monitor live pengerjaan.</p>
                            </div>
                            <a id="editAssessmentShowLink" href="#" class="rounded-full bg-blue-600 px-3 py-1.5 text-[11px] font-semibold text-white hover:bg-blue-700 transition whitespace-nowrap shadow-xs">
                                Buka Detail →
                            </a>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-[#322d2a]/10">
                            <button type="button" onclick="closeEditModal()" class="rounded-full bg-[#f0efe9] px-4 py-2 text-xs font-medium text-[#6f6b67] hover:bg-[#e4e2dc] hover:text-[#322d2a] transition">
                                Batal
                            </button>
                            <button type="submit" class="rounded-full bg-[#f97316] px-5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#ea580c] transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openDeleteModal(assessmentId, assessmentTitle, actionUrl, sessionsCount) {
                document.getElementById('deleteAssessmentTitle').textContent = '"' + assessmentTitle + '"';
                document.getElementById('deleteAssessmentForm').action = actionUrl;

                var warningDiv = document.getElementById('deleteAssessmentWarning');
                var submitBtn = document.getElementById('deleteSubmitBtn');

                if (sessionsCount > 0) {
                    warningDiv.innerHTML = '<span class="font-bold text-rose-700">⚠️ Peringatan:</span> Asesmen ini telah memiliki <span class="font-bold underline">' + sessionsCount + ' sesi pengerjaan</span> oleh kandidat.<br><span class="mt-1 block text-rose-800">Sistem memproteksi penghapusan asesmen dengan riwayat sesi untuk menjaga integritas data evaluasi kandidat. Silakan tutup asesmen dengan mengubah status menjadi <b>Closed</b>.</span>';
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    warningDiv.innerHTML = 'Apakah Anda yakin ingin menghapus jadwal asesmen ini? Undangan peserta yang belum mulai mengerjakan tes akan otomatis dibatalkan.';
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }

                document.getElementById('deleteModal').classList.remove('hidden');
            }

            function closeDeleteModal() {
                document.getElementById('deleteModal').classList.add('hidden');
            }

            function openEditModal(data) {
                document.getElementById('editAssessmentForm').action = data.update_url;
                document.getElementById('editAssessmentTitle').value = data.title || '';
                document.getElementById('editAssessmentCode').value = data.code || '';
                document.getElementById('editAssessmentBankId').value = data.question_bank_id || '';
                document.getElementById('editAssessmentDuration1').value = data.duration_stage_1 || 10;
                document.getElementById('editAssessmentDuration2').value = data.duration_stage_2 || 10;
                document.getElementById('editAssessmentStartAt').value = data.start_at || '';
                document.getElementById('editAssessmentEndAt').value = data.end_at || '';
                document.getElementById('editAssessmentVisibility').value = data.result_visibility || 'HRD_ONLY';
                document.getElementById('editAssessmentStatus').value = data.status || 'DRAFT';
                document.getElementById('editAssessmentShowLink').href = data.show_url;
                document.getElementById('editModal').classList.remove('hidden');
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeDeleteModal();
                    closeEditModal();
                }
            });
        </script>
    </section>
</x-layouts.app>
