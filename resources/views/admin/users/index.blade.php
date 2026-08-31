<x-layouts.app title="Kelola User - Career Indodata">
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
                            User Management
                        </span>
                        <h1 class="mt-3 text-2xl sm:text-3xl md:text-4xl font-light tracking-tight text-white">Kelola Pengguna</h1>
                        <p class="mt-2 max-w-2xl text-xs sm:text-sm font-normal text-white/75">Tambah, ubah data, atur hak akses (role), dan kelola akun internal sistem.</p>
                    </div>
                    <div class="rounded-[20px] border border-white/15 bg-white/10 px-4 py-3 text-xs backdrop-blur text-right">
                        <p class="text-white/70">Total Pengguna</p>
                        <p class="mt-0.5 text-2xl font-light text-white">{{ $users->total() }}</p>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mt-4 rounded-[20px] border border-rose-200 bg-rose-50 p-4 text-xs text-rose-800">
                    <p class="font-semibold">Data belum valid:</p>
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
                        <h2 class="mt-1 text-lg font-medium text-[#322d2a]">Buat akun internal</h2>
                    </div>

                    <div class="grid gap-3 lg:grid-cols-[1fr_1fr_1fr_1.2fr_auto] lg:items-center">
                        <input name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" class="rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white" required>
                        <input name="email" value="{{ old('email') }}" type="email" placeholder="Email" class="rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white" required>
                        <input name="password" type="password" placeholder="Password (min 8 char)" class="rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white" required>

                        <div class="flex flex-wrap gap-1.5">
                            @foreach($roles as $role)
                                <label class="inline-flex items-center gap-1.5 rounded-full border border-[#322d2a]/10 bg-[#f0efe9] px-2.5 py-1 text-[11px] font-normal text-[#322d2a] cursor-pointer hover:bg-[#fff3e8]">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="rounded border-[#322d2a]/20 text-[#f97316] focus:ring-[#f97316]">
                                    {{ $role->label }}
                                </label>
                            @endforeach
                        </div>

                        <button class="inline-flex items-center justify-center rounded-full bg-[#f97316] px-5 py-2 text-xs font-medium text-white shadow-sm transition hover:bg-[#ea580c]">
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
                                <th class="px-4 py-3">Role</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#322d2a]/10 bg-white">
                            @forelse($users as $user)
                                <tr class="transition hover:bg-[#fff8f3]">
                                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                                        @csrf
                                        @method('PUT')

                                        <td class="px-4 py-3 align-top">
                                            <input name="name" value="{{ $user->name }}" class="w-full min-w-[150px] rounded-[10px] border border-[#322d2a]/15 bg-[#f0efe9] px-3 py-1.5 text-xs text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white" required>
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <input name="email" value="{{ $user->email }}" type="email" class="w-full min-w-[180px] rounded-[10px] border border-[#322d2a]/15 bg-[#f0efe9] px-3 py-1.5 text-xs text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white" required>
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <input name="password" type="password" placeholder="Kosongkan jika tetap" class="w-full min-w-[140px] rounded-[10px] border border-[#322d2a]/15 bg-[#f0efe9] px-3 py-1.5 text-xs text-[#322d2a] outline-none placeholder:text-[#6f6b67]/60 focus:border-[#f97316] focus:bg-white">
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <div class="flex min-w-[200px] flex-wrap gap-1">
                                                @foreach($roles as $role)
                                                    <label class="inline-flex items-center gap-1 rounded-full border border-[#322d2a]/10 bg-[#f0efe9] px-2 py-0.5 text-[10px] text-[#322d2a]">
                                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" @checked($user->roles->contains('id', $role->id)) class="rounded border-[#322d2a]/20 text-[#f97316] focus:ring-[#f97316]">
                                                        {{ $role->label }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <div class="flex justify-end gap-1.5">
                                                <button type="submit" class="rounded-full bg-[#f97316] px-3 py-1 text-[11px] font-medium text-white transition hover:bg-[#ea580c]">
                                                    Update
                                                </button>
                                            </div>
                                        </td>
                                    </form>
                                </tr>

                                @if(! $user->is(auth()->user()))
                                    <tr class="bg-white">
                                        <td colspan="5" class="px-4 pb-2.5 pt-0 text-right">
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-[10px] text-rose-600 hover:underline">✕ Hapus Akun User</button>
                                            </form>
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
    </section>
</x-layouts.app>
