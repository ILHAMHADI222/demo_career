<x-layouts.app title="Kelola User - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">

            @php
                $currentUser = auth()->user();
                $isSuperAdmin = $currentUser->hasRole('SUPERADMIN');
            @endphp

            {{-- HERO HEADER --}}
            <div class="relative overflow-hidden rounded-[32px] sm:rounded-[36px] bg-[#322d2a] p-6 sm:p-7 md:p-8 text-white shadow-lg">
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#f97316]/20 blur-3xl"></div>
                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>
                <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3.5 py-1.5 text-xs font-normal uppercase tracking-[0.16em] text-white backdrop-blur">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#f97316]"></span>
                            User Management
                        </span>
                        <h1 class="mt-3 text-2xl sm:text-3xl md:text-4xl font-light tracking-tight text-white">Kelola Pengguna</h1>
                        <p class="mt-2 max-w-2xl text-xs sm:text-sm font-normal text-white/75">
                            {{ $isSuperAdmin ? 'Kelola akun pengguna, atur peran hak akses (role), dan kontrol tahap tes psikotes.' : 'Buat akun internal user (kandidat), atur hak akses tahap tes psikotes, dan kelola data akun.' }}
                        </p>
                    </div>
                    <div class="rounded-[20px] border border-white/15 bg-white/10 px-4 py-3 text-xs backdrop-blur text-right">
                        <p class="text-white/70">Total Pengguna</p>
                        <p class="mt-0.5 text-2xl font-light text-white">{{ $users->total() }}</p>
                    </div>
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
                    <p class="font-semibold">Periksa kembali data Anda:</p>
                    <ul class="mt-1 list-inside list-disc space-y-0.5 text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM TAMBAH USER --}}
            <div class="mt-5 rounded-[28px] sm:rounded-[32px] border border-white bg-white p-5 sm:p-6 shadow-sm">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="mb-4">
                        <p class="text-[10px] font-normal uppercase tracking-[0.16em] text-[#f97316]">Tambah Pengguna Baru</p>
                        <h2 class="mt-1 text-lg font-medium text-[#322d2a]">
                            {{ $isSuperAdmin ? 'Buat Akun Pengguna' : 'Buat Akun Internal User (Candidate)' }}
                        </h2>
                        <p class="text-xs text-[#6f6b67] mt-0.5">
                            {{ $isSuperAdmin ? 'Tentukan data akun, role sistem, dan izin akses tahap psikotes.' : 'Akun ini otomatis dibuat sebagai internal user. Ceklis tahap tes psikotes yang dapat dikerjakan oleh user ini.' }}
                        </p>
                    </div>

                    <div class="grid gap-3 lg:grid-cols-[1fr_1fr_1.1fr_1.4fr_auto] lg:items-center">
                        <input name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" class="rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white" required>
                        <input name="email" value="{{ old('email') }}" type="email" placeholder="Email (contoh: user@hallonet.test)" class="rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white" required>
                        <input name="password" type="password" placeholder="Password (min 8 char)" class="rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white" required>

                        {{-- CHECKBOXES --}}
                        <div>
                            @if ($isSuperAdmin)
                                <div class="mb-1.5 flex flex-wrap gap-1.5">
                                    @foreach($roles as $role)
                                        <label class="inline-flex items-center gap-1.5 rounded-full border border-[#322d2a]/10 bg-[#f0efe9] px-2.5 py-1 text-[11px] font-normal text-[#322d2a] cursor-pointer hover:bg-[#fff3e8]">
                                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="rounded border-[#322d2a]/20 text-[#f97316] focus:ring-[#f97316]">
                                            {{ $role->label }}
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            {{-- CEKLIS TAHAP PSIKOTES --}}
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#6f6b67] mr-1">Tahap Tes:</span>
                                <label class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-2.5 py-1 text-[11px] font-medium text-blue-800 cursor-pointer hover:bg-blue-100 transition" title="Sub-Tes 1: Deret Hitung">
                                    <input type="checkbox" name="allowed_stages[]" value="1" checked class="rounded border-blue-300 text-blue-600 focus:ring-blue-500">
                                    <span>Tahap 1 (Deret)</span>
                                </label>
                                <label class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-medium text-emerald-800 cursor-pointer hover:bg-emerald-100 transition" title="Sub-Tes 2: Analogi Verbal">
                                    <input type="checkbox" name="allowed_stages[]" value="2" checked class="rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500">
                                    <span>Tahap 2 (Analogi)</span>
                                </label>
                                <label class="inline-flex items-center gap-1.5 rounded-full border border-purple-200 bg-purple-50 px-2.5 py-1 text-[11px] font-medium text-purple-800 cursor-pointer hover:bg-purple-100 transition" title="Sub-Tes 3: Kepribadian / DISC">
                                    <input type="checkbox" name="allowed_stages[]" value="3" checked class="rounded border-purple-300 text-purple-600 focus:ring-purple-500">
                                    <span>Tahap 3 (DISC)</span>
                                </label>
                            </div>
                        </div>

                        <button class="inline-flex items-center justify-center rounded-full bg-[#f97316] px-5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#ea580c] whitespace-nowrap">
                            Simpan <span>+</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- DAFTAR USER --}}
            <div class="mt-5 overflow-hidden rounded-[28px] sm:rounded-[32px] border border-white bg-white shadow-sm">
                <div class="border-b border-[#322d2a]/10 p-4 sm:p-5">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="grid gap-3 sm:grid-cols-[1fr_220px_auto] items-center">
                        <input name="keyword" value="{{ request('keyword') }}" placeholder="Cari nama atau email..." class="w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                        <select name="role" class="w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                            <option value="">Semua Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ $role->label }}</option>
                            @endforeach
                        </select>
                        <div class="flex gap-2">
                            <button class="rounded-full bg-[#322d2a] px-5 py-2 text-xs font-medium text-white transition hover:bg-[#f97316]">Filter</button>
                            @if(request()->hasAny(['keyword', 'role']))
                                <a href="{{ route('admin.users.index') }}" class="rounded-full bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#6f6b67] hover:text-[#322d2a]">Reset ✕</a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#322d2a]/10 text-xs">
                        <thead class="bg-[#322d2a] text-left text-[11px] font-normal uppercase tracking-[0.14em] text-white">
                            <tr>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Ubah Password</th>
                                <th class="px-4 py-3">Hak Akses & Tahap Psikotes</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#322d2a]/10 bg-white">
                            @forelse($users as $user)
                                @php
                                    $isRowSuperAdmin = $user->hasRole('SUPERADMIN');
                                    $isRowAdmin = $user->hasRole('ADMIN');
                                    $isRowInternalUser = !$isRowSuperAdmin && !$isRowAdmin;
                                    $isSelf = $user->is($currentUser);
                                    $canEditRow = $isSuperAdmin || $isRowInternalUser || $isSelf;
                                @endphp
                                <tr class="transition hover:bg-[#fff8f3]">
                                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                                        @csrf
                                        @method('PUT')

                                        <td class="px-4 py-3.5 align-top">
                                            <div class="flex items-start gap-3">
                                                <div class="shrink-0 pt-0.5">
                                                    @if($user->photo_url)
                                                        <img src="{{ $user->photo_url }}" alt="Foto {{ $user->name }}" class="h-9 w-9 rounded-[12px] object-cover ring-1 ring-[#322d2a]/10 shadow-xs" onerror="this.onerror=null; this.src='{{ $user->avatar_url }}';">
                                                    @else
                                                        <img src="{{ $user->avatar_url }}" alt="Avatar {{ $user->name }}" class="h-9 w-9 rounded-[12px] object-cover ring-1 ring-[#322d2a]/10 shadow-xs">
                                                    @endif
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    @if ($canEditRow)
                                                        <input name="name" value="{{ $user->name }}" class="w-full min-w-[150px] rounded-[10px] border border-[#322d2a]/15 bg-[#f0efe9] px-3 py-1.5 text-xs text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white" required>
                                                    @else
                                                        <div class="font-medium text-[#322d2a]">{{ $user->name }}</div>
                                                    @endif
                                                    @if ($isSelf)
                                                        <span class="inline-block mt-1 text-[10px] text-emerald-700 font-semibold">(Akun Anda)</span>
                                                    @endif
                                                    @if($user->candidateProfile && $user->candidateProfile->full_name !== $user->name)
                                                        <div class="mt-0.5 text-[10px] text-[#6f6b67]">
                                                            Kandidat: {{ $user->candidateProfile->full_name }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-4 py-3.5 align-top">
                                            @if ($canEditRow)
                                                <input name="email" value="{{ $user->email }}" type="email" class="w-full min-w-[180px] rounded-[10px] border border-[#322d2a]/15 bg-[#f0efe9] px-3 py-1.5 text-xs text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white" required>
                                            @else
                                                <div class="font-mono text-xs text-[#6f6b67]">{{ $user->email }}</div>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3.5 align-top">
                                            @if ($canEditRow)
                                                <input name="password" type="password" placeholder="Kosongkan jika tetap" class="w-full min-w-[140px] rounded-[10px] border border-[#322d2a]/15 bg-[#f0efe9] px-3 py-1.5 text-xs text-[#322d2a] outline-none placeholder:text-[#6f6b67]/60 focus:border-[#f97316] focus:bg-white">
                                            @else
                                                <span class="text-[11px] text-[#6f6b67]/60">••••••••</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3.5 align-top">
                                            @if ($isSuperAdmin)
                                                {{-- Superadmin view: can manage roles and view stages --}}
                                                <div class="flex min-w-[200px] flex-wrap gap-1 mb-1.5">
                                                    @foreach($roles as $role)
                                                        <label class="inline-flex items-center gap-1 rounded-full border border-[#322d2a]/10 bg-[#f0efe9] px-2 py-0.5 text-[10px] text-[#322d2a]">
                                                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" @checked($user->roles->contains('id', $role->id)) class="rounded border-[#322d2a]/20 text-[#f97316] focus:ring-[#f97316]">
                                                            {{ $role->label }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                                <div class="flex flex-wrap gap-1">
                                                    <label class="inline-flex items-center gap-1 rounded-full border border-blue-200 bg-blue-50 px-2 py-0.5 text-[10px] text-blue-800 cursor-pointer">
                                                        <input type="checkbox" name="allowed_stages[]" value="1" @checked($user->canTakeStage(1)) class="rounded border-blue-300 text-blue-600 focus:ring-blue-500">
                                                        <span>T1</span>
                                                    </label>
                                                    <label class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[10px] text-emerald-800 cursor-pointer">
                                                        <input type="checkbox" name="allowed_stages[]" value="2" @checked($user->canTakeStage(2)) class="rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500">
                                                        <span>T2</span>
                                                    </label>
                                                    <label class="inline-flex items-center gap-1 rounded-full border border-purple-200 bg-purple-50 px-2 py-0.5 text-[10px] text-purple-800 cursor-pointer">
                                                        <input type="checkbox" name="allowed_stages[]" value="3" @checked($user->canTakeStage(3)) class="rounded border-purple-300 text-purple-600 focus:ring-purple-500">
                                                        <span>T3</span>
                                                    </label>
                                                </div>
                                            @else
                                                {{-- HRD View: --}}
                                                @if ($isRowSuperAdmin)
                                                    <div class="inline-flex items-center gap-1.5 rounded-full bg-slate-900 px-3 py-1 text-[10px] font-semibold text-white">
                                                        <span>★</span> Superadmin
                                                    </div>
                                                    <div class="mt-1 text-[10px] text-[#6f6b67]">Akun Sistem Utama</div>
                                                @elseif ($isRowAdmin)
                                                    <div class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 border border-blue-200 px-3 py-1 text-[10px] font-semibold text-blue-800">
                                                        <span>🛡</span> Admin HRD
                                                    </div>
                                                    <div class="mt-1 text-[10px] text-[#6f6b67]">Akses Manajemen HR</div>
                                                @else
                                                    {{-- Internal User (Candidate): Checkboxes for Psikotes Stages --}}
                                                    <div class="space-y-1">
                                                        <span class="inline-block rounded-md bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-800 border border-amber-200">
                                                            Akun Internal (Candidate)
                                                        </span>
                                                        <div class="flex flex-wrap gap-1 pt-0.5">
                                                            <label class="inline-flex items-center gap-1 rounded-full border border-blue-200 bg-blue-50 px-2.5 py-0.5 text-[10px] font-medium text-blue-800 cursor-pointer hover:bg-blue-100" title="Sub-Tes 1: Deret Hitung">
                                                                <input type="checkbox" name="allowed_stages[]" value="1" @checked($user->canTakeStage(1)) class="rounded border-blue-300 text-blue-600 focus:ring-blue-500">
                                                                <span>Tahap 1 (Deret)</span>
                                                            </label>
                                                            <label class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[10px] font-medium text-emerald-800 cursor-pointer hover:bg-emerald-100" title="Sub-Tes 2: Analogi Verbal">
                                                                <input type="checkbox" name="allowed_stages[]" value="2" @checked($user->canTakeStage(2)) class="rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500">
                                                                <span>Tahap 2 (Analogi)</span>
                                                            </label>
                                                            <label class="inline-flex items-center gap-1 rounded-full border border-purple-200 bg-purple-50 px-2.5 py-0.5 text-[10px] font-medium text-purple-800 cursor-pointer hover:bg-purple-100" title="Sub-Tes 3: Kepribadian / DISC">
                                                                <input type="checkbox" name="allowed_stages[]" value="3" @checked($user->canTakeStage(3)) class="rounded border-purple-300 text-purple-600 focus:ring-purple-500">
                                                                <span>Tahap 3 (DISC)</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </td>

                                        <td class="px-4 py-3.5 align-top text-right">
                                            @if ($canEditRow)
                                                <button type="submit" class="rounded-full bg-[#f97316] px-3.5 py-1 text-[11px] font-medium text-white shadow-xs transition hover:bg-[#ea580c]">
                                                    Update
                                                </button>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-medium text-slate-500">
                                                    🔒 Terkunci
                                                </span>
                                            @endif
                                        </td>
                                    </form>
                                </tr>

                                {{-- ROW TOMBOL HAPUS (HANYA UNTUK USER INTERNAL ATAU JIKA SUPERADMIN) --}}
                                @if (!$isSelf && ($isSuperAdmin || $isRowInternalUser))
                                    <tr class="bg-white">
                                        <td colspan="5" class="px-4 pb-2.5 pt-0 text-right">
                                            <button type="button"
                                                    onclick="openDeleteUserModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ route('admin.users.destroy', $user) }}')"
                                                    class="text-[10px] font-medium text-rose-600 hover:text-rose-800 hover:underline transition">
                                                ✕ Hapus Akun User
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-xs text-[#6f6b67]">Data user tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="border-t border-[#322d2a]/10 bg-[#f0efe9] p-3 text-xs">
                        {{ $users->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- MODAL HAPUS USER --}}
        <div id="deleteUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteUserModal()"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative w-full max-w-md transform overflow-hidden rounded-[28px] border border-white bg-white p-6 text-left shadow-2xl transition-all">
                    <div class="flex items-center gap-3.5">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 ring-1 ring-rose-200">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-[#322d2a]" id="modal-title">Hapus Pengguna</h3>
                            <p class="text-xs text-[#6f6b67]">Konfirmasi penghapusan akun pengguna</p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl bg-rose-50/70 p-3.5 border border-rose-100 text-xs text-rose-900 leading-relaxed">
                        Apakah Anda yakin ingin menghapus akun <span id="deleteUserName" class="font-bold text-[#322d2a]"></span>? Tindakan ini tidak dapat dibatalkan.
                    </div>

                    <form id="deleteUserForm" method="POST" action="" class="mt-5 flex items-center justify-end gap-2.5">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="closeDeleteUserModal()" class="rounded-full bg-[#f0efe9] px-4 py-2 text-xs font-medium text-[#6f6b67] hover:bg-[#e4e2dc] hover:text-[#322d2a] transition">
                            Batal
                        </button>
                        <button type="submit" class="rounded-full bg-rose-600 px-5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-rose-700 transition">
                            Ya, Hapus Pengguna
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openDeleteUserModal(userId, userName, actionUrl) {
                document.getElementById('deleteUserName').textContent = '"' + userName + '"';
                document.getElementById('deleteUserForm').action = actionUrl;
                document.getElementById('deleteUserModal').classList.remove('hidden');
            }

            function closeDeleteUserModal() {
                document.getElementById('deleteUserModal').classList.add('hidden');
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDeleteUserModal();
                }
            });
        </script>
    </section>
</x-layouts.app>
