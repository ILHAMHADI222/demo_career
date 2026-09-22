<x-layouts.app title="Bank Soal - Career Indodata">
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
                            Psychometric Management
                        </span>
                        <h1 class="mt-2.5 text-2xl sm:text-3xl md:text-4xl font-light tracking-tight text-white">
                            Kelola <span class="text-[#f97316]">Bank Soal</span>
                        </h1>
                        <p class="mt-1.5 max-w-2xl text-xs sm:text-sm font-normal text-white/75">
                            Pusat manajemen 3 Sub-Tes (Deret Hitung 25 Soal, Analogi Verbal 50 Soal, & Tes Kepribadian 24 Soal).
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <a href="{{ route('admin.disc.assessments.index') }}"
                           class="inline-flex items-center gap-1.5 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold text-white backdrop-blur transition hover:bg-white/20">
                            <span>📅 Jadwal Asesmen</span>
                        </a>
                        <a href="{{ route('admin.disc.banks.create') }}"
                           class="inline-flex items-center gap-1.5 rounded-full bg-[#f97316] px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#ea580c]">
                            <span>+ Buat Bank Soal</span>
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
            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                <div class="rounded-[20px] border border-white bg-white p-3.5 sm:p-4 shadow-sm">
                    <p class="text-[10px] uppercase tracking-wider text-[#6f6b67]">Total Bank Soal</p>
                    <p class="mt-1 text-2xl font-light text-[#322d2a]">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-[20px] border border-white bg-white p-3.5 sm:p-4 shadow-sm">
                    <p class="text-[10px] uppercase tracking-wider text-emerald-600">Published (Aktif)</p>
                    <p class="mt-1 text-2xl font-light text-emerald-700">{{ $stats['published'] }}</p>
                </div>
                <div class="rounded-[20px] border border-white bg-white p-3.5 sm:p-4 shadow-sm">
                    <p class="text-[10px] uppercase tracking-wider text-amber-600">Draft / Review</p>
                    <p class="mt-1 text-2xl font-light text-amber-700">{{ $stats['draft'] }}</p>
                </div>
                <div class="rounded-[20px] border border-white bg-white p-3.5 sm:p-4 shadow-sm">
                    <p class="text-[10px] uppercase tracking-wider text-[#6f6b67]">Archived</p>
                    <p class="mt-1 text-2xl font-light text-[#6f6b67]">{{ $stats['archived'] }}</p>
                </div>
            </div>

            {{-- FILTER & SEARCH --}}
            <div class="mt-4 rounded-[24px] sm:rounded-[28px] border border-white bg-white p-4 sm:p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.disc.banks.index') }}" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div class="flex flex-wrap items-center gap-1.5">
                        <a href="{{ route('admin.disc.banks.index') }}"
                           class="rounded-full px-3.5 py-1.5 text-xs font-medium transition {{ empty($status) ? 'bg-[#322d2a] text-white' : 'bg-[#f0efe9] text-[#6f6b67] hover:bg-[#fff3e8] hover:text-[#f97316]' }}">
                            Semua Status
                        </a>
                        <a href="{{ route('admin.disc.banks.index', ['status' => 'PUBLISHED']) }}"
                           class="rounded-full px-3.5 py-1.5 text-xs font-medium transition {{ $status === 'PUBLISHED' ? 'bg-emerald-600 text-white' : 'bg-[#f0efe9] text-[#6f6b67] hover:bg-emerald-50 hover:text-emerald-700' }}">
                            Published
                        </a>
                        <a href="{{ route('admin.disc.banks.index', ['status' => 'DRAFT']) }}"
                           class="rounded-full px-3.5 py-1.5 text-xs font-medium transition {{ $status === 'DRAFT' ? 'bg-amber-600 text-white' : 'bg-[#f0efe9] text-[#6f6b67] hover:bg-amber-50 hover:text-amber-700' }}">
                            Draft
                        </a>
                        <a href="{{ route('admin.disc.banks.index', ['status' => 'ARCHIVED']) }}"
                           class="rounded-full px-3.5 py-1.5 text-xs font-medium transition {{ $status === 'ARCHIVED' ? 'bg-slate-800 text-white' : 'bg-[#f0efe9] text-[#6f6b67] hover:bg-slate-200' }}">
                            Archived
                        </a>
                    </div>

                    <div class="flex items-center gap-2">
                        <input
                            type="text"
                            name="keyword"
                            value="{{ $keyword }}"
                            placeholder="Cari nama atau kode bank soal..."
                            class="w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white md:w-64"
                        >
                        <button type="submit" class="rounded-full bg-[#322d2a] px-4 py-2 text-xs font-medium text-white transition hover:bg-[#f97316]">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            {{-- BANK SOAL LIST TABLE --}}
            <div class="mt-4 overflow-hidden rounded-[24px] sm:rounded-[28px] border border-white bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#322d2a]/10 text-xs">
                        <thead class="bg-[#322d2a] text-left text-[11px] font-normal uppercase tracking-[0.14em] text-white">
                            <tr>
                                <th class="px-4 py-3">Bank Soal</th>
                                <th class="px-4 py-3">Kode & Versi</th>
                                <th class="px-4 py-3">Kelengkapan 3 Sub-Tes</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Asesmen Terkait</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#322d2a]/10 bg-white">
                            @forelse ($banks as $bank)
                                <tr class="transition hover:bg-[#fff8f3]">
                                    <td class="px-4 py-3.5 align-top">
                                        <div class="font-medium text-[#322d2a]">{{ $bank->name }}</div>
                                        <div class="mt-0.5 text-[10px] text-[#6f6b67]">{{ Str::limit($bank->description ?? 'Tidak ada deskripsi', 60) }}</div>
                                    </td>
                                    <td class="px-4 py-3.5 align-top">
                                        <span class="rounded-md bg-[#f0efe9] px-2 py-0.5 font-mono text-[11px] font-bold text-[#322d2a]">{{ $bank->code }}</span>
                                        <span class="ml-1 text-[10px] text-[#6f6b67]">v{{ $bank->version }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 align-top">
                                        <div class="flex items-center gap-1.5 font-mono text-[11px]">
                                            <span class="rounded bg-blue-50 text-blue-700 px-1.5 py-0.5 font-bold">Deret: {{ $bank->deretQuestions()->count() }}/25</span>
                                            <span class="rounded bg-emerald-50 text-emerald-700 px-1.5 py-0.5 font-bold">Analogi: {{ $bank->analogiQuestions()->count() }}/50</span>
                                            <span class="rounded bg-purple-50 text-purple-700 px-1.5 py-0.5 font-bold">Kepribadian: {{ $bank->questions_count }}/24</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 align-top">
                                        @if ($bank->status === 'PUBLISHED')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-medium text-emerald-700 ring-1 ring-emerald-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Published
                                            </span>
                                        @elseif ($bank->status === 'DRAFT')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-0.5 text-[10px] font-medium text-amber-700 ring-1 ring-amber-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Draft
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-medium text-slate-600">
                                                {{ $bank->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 align-top text-xs text-[#6f6b67]">
                                        {{ $bank->assessments_count }} Asesmen
                                    </td>
                                    <td class="px-4 py-3.5 align-top text-right">
                                        <div class="inline-flex flex-wrap items-center justify-end gap-1.5">
                                            <a href="{{ route('admin.disc.banks.show', $bank) }}"
                                               class="rounded-full bg-[#f0efe9] px-2.5 py-1 text-[11px] font-medium text-[#322d2a] hover:bg-[#322d2a] hover:text-white transition"
                                               title="Preview Butir Soal">
                                                Preview
                                            </a>
                                            <a href="{{ route('admin.disc.banks.scoring-keys', $bank) }}"
                                               class="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-medium text-amber-700 ring-1 ring-amber-200 hover:bg-amber-100 transition"
                                               title="Kunci Jawaban Scoring">
                                                Kunci Scoring
                                            </a>
                                            <button type="button"
                                                    onclick="openEditModal({{ json_encode([
                                                        'id' => $bank->id,
                                                        'name' => $bank->name,
                                                        'code' => $bank->code,
                                                        'version' => $bank->version,
                                                        'description' => $bank->description,
                                                        'status' => $bank->status,
                                                        'update_url' => route('admin.disc.banks.update', $bank),
                                                        'manage_url' => route('admin.disc.banks.edit', $bank),
                                                    ]) }})"
                                                    class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-medium text-blue-700 ring-1 ring-blue-200 hover:bg-blue-600 hover:text-white transition"
                                                    title="Edit Informasi Bank Soal">
                                                ✏️ Edit
                                            </button>
                                            <a href="{{ route('admin.disc.banks.edit', $bank) }}"
                                               class="rounded-full bg-[#322d2a] px-3 py-1 text-[11px] font-medium text-white hover:bg-[#f97316] transition"
                                               title="Kelola Butir Soal 3 Sub-Tes">
                                                Kelola Soal →
                                            </a>
                                            <form method="POST" action="{{ route('admin.disc.banks.duplicate', $bank) }}" onsubmit="return confirm('Duplikasi bank soal ini menjadi versi draft baru?')" class="inline">
                                                @csrf
                                                <button type="submit" class="rounded-full bg-[#f0efe9] px-2 py-1 text-[11px] font-medium text-[#6f6b67] hover:bg-slate-200 hover:text-[#322d2a] transition" title="Duplikasi">
                                                    ⧉
                                                </button>
                                            </form>
                                            <button type="button"
                                                    onclick="openDeleteModal('{{ $bank->id }}', '{{ addslashes($bank->name) }}', '{{ route('admin.disc.banks.destroy', $bank) }}')"
                                                    class="rounded-full bg-rose-50 px-2.5 py-1 text-[11px] font-medium text-rose-600 ring-1 ring-rose-200 hover:bg-rose-600 hover:text-white transition"
                                                    title="Hapus Bank Soal">
                                                ✕ Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-xs text-[#6f6b67]">
                                            Belum ada data bank soal. Silakan buat bank soal baru atau duplikasi.
                                        </td>
                                    </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($banks->hasPages())
                    <div class="border-t border-[#322d2a]/10 bg-[#f0efe9] p-3 text-xs">
                        {{ $banks->links() }}
                    </div>
                @endif
            </div>

        </div>

        {{-- MODAL KONFIRMASI HAPUS --}}
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
                            <h3 class="text-base font-semibold text-[#322d2a]" id="modal-delete-title">Hapus Bank Soal</h3>
                            <p class="text-xs text-[#6f6b67]">Konfirmasi penghapusan data bank soal</p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl bg-rose-50/70 p-3.5 border border-rose-100 text-xs text-rose-900 leading-relaxed">
                        Apakah Anda yakin ingin menghapus bank soal <span id="deleteBankName" class="font-bold text-[#322d2a]"></span>?
                        <p class="mt-1 text-[11px] text-rose-700">Bank soal yang sedang terhubung dengan jadwal asesmen tidak dapat dihapus demi menjaga integritas data.</p>
                    </div>

                    <form id="deleteBankForm" method="POST" action="" class="mt-5 flex items-center justify-end gap-2.5">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="closeDeleteModal()" class="rounded-full bg-[#f0efe9] px-4 py-2 text-xs font-medium text-[#6f6b67] hover:bg-[#e4e2dc] hover:text-[#322d2a] transition">
                            Batal
                        </button>
                        <button type="submit" class="rounded-full bg-rose-600 px-5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-rose-700 transition">
                            Ya, Hapus Bank Soal
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT BANK SOAL --}}
        <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-edit-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative w-full max-w-lg transform overflow-hidden rounded-[28px] border border-white bg-white p-6 text-left shadow-2xl transition-all">
                    <div class="flex items-center justify-between border-b border-[#322d2a]/10 pb-3.5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 ring-1 ring-blue-200 text-base">
                                ✏️
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-[#322d2a]" id="modal-edit-title">Edit Bank Soal</h3>
                                <p class="text-xs text-[#6f6b67]">Perbarui informasi nama, kode, versi & status</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeEditModal()" class="rounded-full p-1.5 text-[#6f6b67] hover:bg-[#f0efe9] hover:text-[#322d2a]">
                            ✕
                        </button>
                    </div>

                    <form id="editBankForm" method="POST" action="" class="mt-4 space-y-3.5 text-xs">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Nama Bank Soal <span class="text-rose-500">*</span></label>
                            <input type="text" id="editBankName" name="name" required class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Kode Unik <span class="text-rose-500">*</span></label>
                                <input type="text" id="editBankCode" name="code" required class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 font-mono text-xs uppercase text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Versi <span class="text-rose-500">*</span></label>
                                <input type="text" id="editBankVersion" name="version" required class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Status Publikasi</label>
                            <select id="editBankStatus" name="status" class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                                <option value="DRAFT">DRAFT (Dalam Pengeditan)</option>
                                <option value="PUBLISHED">PUBLISHED (Aktif & Siap Dipakai Asesmen)</option>
                                <option value="ARCHIVED">ARCHIVED (Diarsipkan)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Deskripsi (Opsional)</label>
                            <textarea id="editBankDescription" name="description" rows="2" placeholder="Catatan singkat mengenai bank soal ini..." class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] p-3 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"></textarea>
                        </div>

                        <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-3 flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[11px] font-semibold text-blue-900">Kelola Butir Soal 3 Sub-Tes</p>
                                <p class="text-[10px] text-blue-700">Deret Hitung (25), Analogi Verbal (50), & Kepribadian (24)</p>
                            </div>
                            <a id="editBankManageLink" href="#" class="rounded-full bg-blue-600 px-3 py-1.5 text-[11px] font-semibold text-white hover:bg-blue-700 transition whitespace-nowrap shadow-xs">
                                Buka Butir Soal →
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
            function openDeleteModal(bankId, bankName, actionUrl) {
                document.getElementById('deleteBankName').textContent = '"' + bankName + '"';
                document.getElementById('deleteBankForm').action = actionUrl;
                document.getElementById('deleteModal').classList.remove('hidden');
            }

            function closeDeleteModal() {
                document.getElementById('deleteModal').classList.add('hidden');
            }

            function openEditModal(data) {
                document.getElementById('editBankForm').action = data.update_url;
                document.getElementById('editBankName').value = data.name || '';
                document.getElementById('editBankCode').value = data.code || '';
                document.getElementById('editBankVersion').value = data.version || '1.0';
                document.getElementById('editBankStatus').value = data.status || 'DRAFT';
                document.getElementById('editBankDescription').value = data.description || '';
                document.getElementById('editBankManageLink').href = data.manage_url;
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
