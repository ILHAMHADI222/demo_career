<x-layouts.app title="Kelola Lowongan - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">
            {{-- HERO --}}
            <div class="relative overflow-hidden rounded-[32px] sm:rounded-[36px] bg-[#322d2a] p-6 sm:p-7 md:p-8 text-white shadow-lg">
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#f97316]/20 blur-3xl"></div>
                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>
                <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3.5 py-1.5 text-xs font-normal uppercase tracking-[0.16em] text-white backdrop-blur">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#f97316]"></span>
                            HRD Job Management
                        </span>
                        <h1 class="mt-3 text-2xl sm:text-3xl md:text-4xl font-light tracking-tight text-white">Kelola Lowongan Pekerjaan</h1>
                        <p class="mt-2 max-w-2xl text-xs sm:text-sm font-normal text-white/75">Buat, atur masa aktif hari penayangan, publikasikan, dan pantau seluruh posisi pekerjaan aktif di Career Indodata.</p>
                    </div>
                    <a href="{{ route('admin.jobs.create') }}" class="shrink-0 inline-flex items-center gap-2 rounded-full bg-[#f97316] px-5 py-2.5 text-xs font-medium text-white shadow-sm transition hover:bg-[#ea580c]">
                        <span>+</span>
                        Tambah Lowongan Baru
                    </a>
                </div>
            </div>

            {{-- NOTIFIKASI FLASH --}}
            @if (session('success'))
                <div class="mt-4 flex items-center gap-2.5 rounded-[20px] border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-medium text-emerald-800 shadow-xs">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-[10px]">✓</span>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-4 rounded-[20px] border border-rose-200 bg-rose-50 p-4 text-xs text-rose-800 shadow-xs">
                    <p class="font-semibold">{{ $errors->first() }}</p>
                </div>
            @endif

            {{-- FILTER & TABLE CONTAINER --}}
            <div class="mt-5 rounded-[28px] sm:rounded-[32px] border border-white bg-white p-5 sm:p-6 shadow-sm">
                <form method="GET" action="{{ route('admin.jobs.index') }}" class="grid gap-3 sm:grid-cols-[1fr_220px_auto] items-center">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                            <svg class="h-4 w-4 text-[#6f6b67]/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z" />
                            </svg>
                        </div>
                        <input name="keyword" value="{{ request('keyword') }}" placeholder="Cari judul lowongan, kode posisi, lokasi..." class="w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] py-2 pl-9 pr-3.5 text-xs font-normal text-[#322d2a] outline-none transition placeholder:text-[#6f6b67]/70 focus:border-[#f97316] focus:bg-white">
                    </div>

                    <select name="status" class="w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#322d2a] px-5 py-2 text-xs font-medium text-white transition hover:bg-[#f97316]">
                            Filter
                        </button>
                        @if(request()->hasAny(['keyword', 'status']))
                            <a href="{{ route('admin.jobs.index') }}" class="inline-flex items-center justify-center rounded-full bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#6f6b67] hover:text-[#322d2a]">
                                Reset ✕
                            </a>
                        @endif
                    </div>
                </form>

                {{-- TABLE --}}
                <div class="mt-5 overflow-hidden rounded-[20px] border border-[#322d2a]/10">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#322d2a]/10 text-xs">
                            <thead class="bg-[#322d2a] text-left text-[11px] font-normal uppercase tracking-[0.14em] text-white">
                                <tr>
                                    <th class="px-4 py-3">Lowongan</th>
                                    <th class="px-4 py-3">Departemen</th>
                                    <th class="px-4 py-3">Periode Pendaftaran</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 text-center">Pelamar</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#322d2a]/10 bg-white">
                                @forelse($jobs as $job)
                                    @php
                                        $effectiveState = $job->status;
                                        $stateClasses = 'bg-[#f0efe9] text-[#322d2a]';

                                        if ($job->status === 'PUBLISHED' && $job->registration_start_at && $job->registration_start_at->isFuture()) {
                                            $effectiveState = 'BELUM DIBUKA';
                                            $stateClasses = 'bg-sky-50 text-sky-700 ring-1 ring-sky-200';
                                        } elseif ($job->isOpen()) {
                                            $effectiveState = 'OPEN';
                                            $stateClasses = 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200';
                                        } elseif ($job->status === 'CLOSED' || ($job->deadline_at && $job->deadline_at->isPast())) {
                                            $effectiveState = 'TUTUP';
                                            $stateClasses = 'bg-rose-50 text-rose-700 ring-1 ring-rose-200';
                                        } elseif ($job->status === 'DRAFT') {
                                            $effectiveState = 'DRAFT';
                                            $stateClasses = 'bg-[#fff3e8] text-[#f97316] ring-1 ring-[#f97316]/20';
                                        }
                                    @endphp
                                    <tr class="transition hover:bg-[#fff8f3]">
                                        <td class="px-4 py-3.5 align-top">
                                            <p class="font-medium text-[#322d2a]">{{ $job->title }}</p>
                                            <p class="mt-0.5 text-[10px] text-[#6f6b67]">{{ $job->job_code }} • {{ $job->location }} • {{ $job->recruiter?->name ?? 'HRD' }}</p>
                                        </td>
                                        <td class="px-4 py-3.5 align-top font-medium text-[#322d2a]">{{ $job->department?->name ?? '-' }}</td>
                                        <td class="px-4 py-3.5 align-top">
                                            <p class="text-[11px] text-[#322d2a] font-normal">
                                                {{ optional($job->registration_start_at)->format('d M Y') ?? 'Langsung' }}
                                                <span class="text-[#6f6b67]">s/d</span>
                                                <span class="{{ $job->deadline_at && $job->deadline_at->isPast() ? 'text-rose-600 font-semibold' : 'text-[#322d2a]' }}">
                                                    {{ optional($job->deadline_at)->format('d M Y') ?? 'Tidak terbatas' }}
                                                </span>
                                            </p>
                                            @if ($job->deadline_at && $job->deadline_at->isFuture())
                                                <span class="text-[10px] text-emerald-600 font-medium">Sisa {{ ceil(now()->diffInDays($job->deadline_at, false)) }} hari lagi</span>
                                            @elseif ($job->deadline_at && $job->deadline_at->isPast())
                                                <span class="text-[10px] text-rose-500 font-medium">Sudah berakhir</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 align-top">
                                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-medium {{ $stateClasses }}">
                                                {{ $effectiveState }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 align-top text-center font-medium text-[#322d2a]">
                                            <span class="inline-flex rounded-full bg-[#f0efe9] px-2.5 py-0.5 text-[11px]">
                                                {{ $job->applications_count ?? $job->applications()->count() }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 align-top">
                                            <div class="flex flex-wrap justify-end items-center gap-1.5">
                                                <a href="{{ route('admin.jobs.edit', $job) }}" class="rounded-full bg-[#f0efe9] px-2.5 py-1 text-[11px] font-medium text-[#322d2a] hover:bg-[#322d2a] hover:text-white transition">
                                                    Edit
                                                </a>

                                                @if($job->status !== 'PUBLISHED' || ($job->deadline_at && $job->deadline_at->isPast()))
                                                    <button type="button"
                                                            onclick="openPublishModal('{{ $job->id }}', '{{ addslashes($job->title) }}', '{{ route('admin.jobs.publish', $job) }}', '{{ $job->deadline_at ? $job->deadline_at->format('Y-m-d\TH:i') : '' }}', false)"
                                                            class="rounded-full bg-emerald-600 px-3.5 py-1 text-[11px] font-medium text-white shadow-xs hover:bg-emerald-700 transition"
                                                            title="Atur hari dan publikasikan lowongan">
                                                        ✓ Publish
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            onclick="openPublishModal('{{ $job->id }}', '{{ addslashes($job->title) }}', '{{ route('admin.jobs.publish', $job) }}', '{{ $job->deadline_at ? $job->deadline_at->format('Y-m-d\TH:i') : '' }}', true)"
                                                            class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-medium text-blue-700 ring-1 ring-blue-200 hover:bg-blue-600 hover:text-white transition"
                                                            title="Atur masa aktif / perpanjang hari lowongan">
                                                        ⏱ Atur Hari
                                                    </button>
                                                    <form method="POST" action="{{ route('admin.jobs.close', $job) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-medium text-amber-800 ring-1 ring-amber-200 hover:bg-amber-100 transition">
                                                            Tutup
                                                        </button>
                                                    </form>
                                                @endif

                                                <button type="button"
                                                        onclick="openDeleteJobModal('{{ $job->id }}', '{{ addslashes($job->title) }}', '{{ route('admin.jobs.destroy', $job) }}')"
                                                        class="rounded-full bg-rose-50 px-2 py-1 text-[11px] font-medium text-rose-600 ring-1 ring-rose-200 hover:bg-rose-600 hover:text-white transition"
                                                        title="Hapus Lowongan">
                                                    ✕
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-8 text-center text-xs text-[#6f6b67]">Belum ada data lowongan pekerjaan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($jobs->hasPages())
                    <div class="mt-4 rounded-[16px] border border-[#322d2a]/10 bg-[#f0efe9] p-3 text-xs">
                        {{ $jobs->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- MODAL PUBLISH & SET HARI --}}
        <div id="publishModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-publish-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closePublishModal()"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative w-full max-w-lg transform overflow-hidden rounded-[28px] border border-white bg-white p-6 text-left shadow-2xl transition-all">
                    <div class="flex items-center justify-between border-b border-[#322d2a]/10 pb-3.5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200 text-base">
                                📢
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-[#322d2a]" id="modal-publish-title">Publikasikan Lowongan</h3>
                                <p class="text-xs text-[#6f6b67]">Atur masa aktif penayangan hari lowongan di portal karir</p>
                            </div>
                        </div>
                        <button type="button" onclick="closePublishModal()" class="rounded-full p-1.5 text-[#6f6b67] hover:bg-[#f0efe9] hover:text-[#322d2a]">
                            ✕
                        </button>
                    </div>

                    <form id="publishJobForm" method="POST" action="" class="mt-4 space-y-4 text-xs">
                        @csrf

                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-3.5">
                            <p class="text-[11px] text-emerald-900">Lowongan: <span id="publishJobTitle" class="font-bold text-[#322d2a]"></span></p>
                            <p class="mt-1 text-[10px] text-emerald-700">Pilih berapa hari lowongan ini akan dibuka untuk pelamar.</p>
                        </div>

                        {{-- PRESET PILIHAN HARI --}}
                        <div>
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67] mb-1.5">Pilihan Cepat Masa Aktif:</label>
                            <div class="grid grid-cols-3 gap-2 sm:grid-cols-5">
                                <button type="button" onclick="selectPresetDays(7)" id="preset-7" class="preset-btn rounded-xl border border-[#322d2a]/15 bg-[#f0efe9] py-2 text-center text-xs font-medium text-[#322d2a] hover:border-[#f97316] hover:bg-[#fff3e8] transition">
                                    7 Hari
                                </button>
                                <button type="button" onclick="selectPresetDays(14)" id="preset-14" class="preset-btn rounded-xl border border-[#322d2a]/15 bg-[#f0efe9] py-2 text-center text-xs font-medium text-[#322d2a] hover:border-[#f97316] hover:bg-[#fff3e8] transition">
                                    14 Hari
                                </button>
                                <button type="button" onclick="selectPresetDays(30)" id="preset-30" class="preset-btn rounded-xl border border-[#f97316] bg-[#f97316] py-2 text-center text-xs font-semibold text-white shadow-xs transition">
                                    30 Hari
                                </button>
                                <button type="button" onclick="selectPresetDays(60)" id="preset-60" class="preset-btn rounded-xl border border-[#322d2a]/15 bg-[#f0efe9] py-2 text-center text-xs font-medium text-[#322d2a] hover:border-[#f97316] hover:bg-[#fff3e8] transition">
                                    60 Hari
                                </button>
                                <button type="button" onclick="selectPresetDays(-1)" id="preset--1" class="preset-btn rounded-xl border border-[#322d2a]/15 bg-[#f0efe9] py-2 text-center text-xs font-medium text-[#322d2a] hover:border-[#f97316] hover:bg-[#fff3e8] transition">
                                    Tanpa Batas
                                </button>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Jumlah Hari Aktif</label>
                                <div class="relative mt-1">
                                    <input type="number" id="modalActiveDays" name="active_days" value="30" min="1" max="365" oninput="onDaysInputChange()" class="w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                                    <span class="absolute right-3 top-2 text-[10px] text-[#6f6b67]">Hari</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Atau Tanggal Berakhir (Deadline)</label>
                                <input type="datetime-local" id="modalDeadlineAt" name="deadline_at" onchange="onDeadlineInputChange()" class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                            </div>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-xs">
                            <p class="text-[11px] text-[#6f6b67]">
                                Tanggal penutupan otomatis: <span id="modalDeadlinePreview" class="font-bold text-[#f97316]"></span>
                            </p>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-[#322d2a]/10">
                            <button type="button" onclick="closePublishModal()" class="rounded-full bg-[#f0efe9] px-4 py-2 text-xs font-medium text-[#6f6b67] hover:bg-[#e4e2dc] hover:text-[#322d2a] transition">
                                Batal
                            </button>
                            <button type="submit" id="modalSubmitBtn" class="rounded-full bg-emerald-600 px-5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-emerald-700 transition">
                                Publikasikan Sekarang ✓
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL HAPUS LOWONGAN --}}
        <div id="deleteJobModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-delete-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteJobModal()"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative w-full max-w-md transform overflow-hidden rounded-[28px] border border-white bg-white p-6 text-left shadow-2xl transition-all">
                    <div class="flex items-center gap-3.5">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 ring-1 ring-rose-200">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-[#322d2a]" id="modal-delete-title">Hapus Lowongan</h3>
                            <p class="text-xs text-[#6f6b67]">Konfirmasi penghapusan data lowongan</p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl bg-rose-50/70 p-3.5 border border-rose-100 text-xs text-rose-900 leading-relaxed">
                        Apakah Anda yakin ingin menghapus lowongan <span id="deleteJobTitle" class="font-bold text-[#322d2a]"></span>?
                    </div>

                    <form id="deleteJobForm" method="POST" action="" class="mt-5 flex items-center justify-end gap-2.5">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="closeDeleteJobModal()" class="rounded-full bg-[#f0efe9] px-4 py-2 text-xs font-medium text-[#6f6b67] hover:bg-[#e4e2dc] hover:text-[#322d2a] transition">
                            Batal
                        </button>
                        <button type="submit" class="rounded-full bg-rose-600 px-5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-rose-700 transition">
                            Ya, Hapus Lowongan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function selectPresetDays(days) {
                // Update preset button styles
                document.querySelectorAll('.preset-btn').forEach(btn => {
                    btn.classList.remove('bg-[#f97316]', 'text-white', 'border-[#f97316]', 'font-semibold');
                    btn.classList.add('bg-[#f0efe9]', 'text-[#322d2a]', 'border-[#322d2a]/15', 'font-medium');
                });

                var activeBtn = document.getElementById('preset-' + days);
                if (activeBtn) {
                    activeBtn.classList.remove('bg-[#f0efe9]', 'text-[#322d2a]', 'border-[#322d2a]/15', 'font-medium');
                    activeBtn.classList.add('bg-[#f97316]', 'text-white', 'border-[#f97316]', 'font-semibold');
                }

                var daysInput = document.getElementById('modalActiveDays');
                var deadlineInput = document.getElementById('modalDeadlineAt');

                if (days === -1) {
                    daysInput.value = '';
                    deadlineInput.value = '';
                    document.getElementById('modalDeadlinePreview').textContent = 'Tanpa batas waktu (selalu buka)';
                } else {
                    daysInput.value = days;
                    var targetDate = new Date();
                    targetDate.setDate(targetDate.getDate() + parseInt(days));
                    targetDate.setHours(23, 59, 0, 0);

                    // Format to YYYY-MM-DDTHH:mm
                    var year = targetDate.getFullYear();
                    var month = String(targetDate.getMonth() + 1).padStart(2, '0');
                    var day = String(targetDate.getDate()).padStart(2, '0');
                    var hours = String(targetDate.getHours()).padStart(2, '0');
                    var minutes = String(targetDate.getMinutes()).padStart(2, '0');
                    deadlineInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;

                    updatePreviewText(targetDate);
                }
            }

            function onDaysInputChange() {
                var days = parseInt(document.getElementById('modalActiveDays').value);
                if (!isNaN(days) && days > 0) {
                    selectPresetDays(days);
                }
            }

            function onDeadlineInputChange() {
                var val = document.getElementById('modalDeadlineAt').value;
                if (val) {
                    var targetDate = new Date(val);
                    var today = new Date();
                    var diffTime = targetDate.getTime() - today.getTime();
                    var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    document.getElementById('modalActiveDays').value = diffDays > 0 ? diffDays : 0;
                    updatePreviewText(targetDate);
                }
            }

            function updatePreviewText(targetDate) {
                var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                document.getElementById('modalDeadlinePreview').textContent = targetDate.toLocaleDateString('id-ID', options);
            }

            function openPublishModal(jobId, jobTitle, publishUrl, currentDeadline, isAlreadyPublished) {
                document.getElementById('publishJobTitle').textContent = '"' + jobTitle + '"';
                document.getElementById('publishJobForm').action = publishUrl;

                if (isAlreadyPublished) {
                    document.getElementById('modal-publish-title').textContent = 'Atur Masa Aktif Lowongan';
                    document.getElementById('modalSubmitBtn').textContent = 'Simpan Masa Aktif ✓';
                } else {
                    document.getElementById('modal-publish-title').textContent = 'Publikasikan Lowongan';
                    document.getElementById('modalSubmitBtn').textContent = 'Publikasikan Sekarang ✓';
                }

                if (currentDeadline) {
                    var curDate = new Date(currentDeadline);
                    if (curDate > new Date()) {
                        var diffDays = Math.ceil((curDate.getTime() - new Date().getTime()) / (1000 * 60 * 60 * 24));
                        if ([7, 14, 30, 60].includes(diffDays)) {
                            selectPresetDays(diffDays);
                        } else {
                            document.getElementById('modalDeadlineAt').value = currentDeadline;
                            onDeadlineInputChange();
                        }
                    } else {
                        selectPresetDays(30);
                    }
                } else if (isAlreadyPublished && !currentDeadline) {
                    selectPresetDays(-1);
                } else {
                    selectPresetDays(30);
                }

                document.getElementById('publishModal').classList.remove('hidden');
            }

            function closePublishModal() {
                document.getElementById('publishModal').classList.add('hidden');
            }

            function openDeleteJobModal(jobId, jobTitle, actionUrl) {
                document.getElementById('deleteJobTitle').textContent = '"' + jobTitle + '"';
                document.getElementById('deleteJobForm').action = actionUrl;
                document.getElementById('deleteJobModal').classList.remove('hidden');
            }

            function closeDeleteJobModal() {
                document.getElementById('deleteJobModal').classList.add('hidden');
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closePublishModal();
                    closeDeleteJobModal();
                }
            });
        </script>
    </section>
</x-layouts.app>
