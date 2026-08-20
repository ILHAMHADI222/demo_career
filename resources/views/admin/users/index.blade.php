<x-layouts.app title="Kelola User - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-28 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">
            <div class="relative overflow-hidden rounded-[56px] bg-[#322d2a] p-8 text-white md:p-10">
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#f97316]/20 blur-3xl"></div>
                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>
                <div class="relative z-10 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-normal uppercase tracking-[0.22em] text-white backdrop-blur">
                            <span class="h-2 w-2 rounded-full bg-[#f97316]"></span>
                            User Management
                        </p>
                        <h1 class="mt-5 text-4xl font-light tracking-tight md:text-5xl">Kelola User</h1>
                        <p class="mt-4 max-w-2xl text-sm font-normal leading-7 text-white/75">Tambah, edit, hapus, dan atur role user.</p>
                    </div>
                    <div class="rounded-[28px] border border-white/15 bg-white/10 px-5 py-4 text-sm backdrop-blur">
                        <p class="text-white/70">Total User</p>
                        <p class="mt-1 text-3xl font-light text-white">{{ $users->total() }}</p>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mt-6 rounded-[28px] border border-[#f97316]/20 bg-[#fff3e8] p-5 text-sm text-[#322d2a]">
                    <p class="font-normal text-[#f97316]">Data belum valid:</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-6 rounded-[40px] border border-white bg-white p-5 md:p-6">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="mb-5 flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-normal uppercase tracking-[0.22em] text-[#f97316]">Tambah User</p>
                            <h2 class="mt-2 text-xl font-normal text-[#322d2a]">Buat akun internal baru</h2>
                        </div>
                    </div>

                    <div class="grid gap-3 lg:grid-cols-[1fr_1fr_1fr_1.2fr_auto] lg:items-start">
                        <input name="name" value="{{ old('name') }}" placeholder="Nama" class="rounded-[24px] border border-white bg-[#f0efe9] px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition placeholder:text-[#6f6b67]/60 focus:border-[#f97316] focus:bg-white focus:ring-4 focus:ring-[#f97316]/20" required>
                        <input name="email" value="{{ old('email') }}" type="email" placeholder="Email" class="rounded-[24px] border border-white bg-[#f0efe9] px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition placeholder:text-[#6f6b67]/60 focus:border-[#f97316] focus:bg-white focus:ring-4 focus:ring-[#f97316]/20" required>
                        <input name="password" type="password" placeholder="Password minimal 8 karakter" class="rounded-[24px] border border-white bg-[#f0efe9] px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition placeholder:text-[#6f6b67]/60 focus:border-[#f97316] focus:bg-white focus:ring-4 focus:ring-[#f97316]/20" required>

                        <div class="flex flex-wrap gap-2">
                            @foreach($roles as $role)
                                <label class="inline-flex items-center gap-2 rounded-full border border-[#322d2a]/10 bg-[#f0efe9] px-3 py-2 text-xs font-normal text-[#322d2a]">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="rounded border-[#322d2a]/20 text-[#f97316] focus:ring-[#f97316]">
                                    {{ $role->label }}
                                </label>
                            @endforeach
                        </div>

                        <button class="rounded-full bg-[#f97316] px-5 py-3 text-sm font-normal text-white transition hover:bg-[#ea580c]">Simpan</button>
                    </div>
                </form>
            </div>

            <div class="mt-6 overflow-hidden rounded-[40px] border border-white bg-white">
                <div class="border-b border-[#322d2a]/10 p-5 md:p-6">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="grid gap-3 md:grid-cols-[1fr_220px_auto]">
                        <input name="keyword" value="{{ request('keyword') }}" placeholder="Cari nama atau email" class="rounded-[24px] border border-white bg-[#f0efe9] px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition placeholder:text-[#6f6b67]/60 focus:border-[#f97316] focus:bg-white focus:ring-4 focus:ring-[#f97316]/20">
                        <select name="role" class="rounded-[24px] border border-white bg-[#f0efe9] px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white focus:ring-4 focus:ring-[#f97316]/20">
                            <option value="">Semua role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ $role->label }}</option>
                            @endforeach
                        </select>
                        <button class="rounded-[24px] bg-[#322d2a] px-5 py-3 text-sm font-normal text-white transition hover:bg-[#f97316]">Filter</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#322d2a]/10 text-sm">
                        <thead class="bg-[#322d2a] text-left text-xs font-normal uppercase tracking-[0.14em] text-white">
                            <tr>
                                <th class="px-5 py-4">User</th>
                                <th class="px-5 py-4">Email</th>
                                <th class="px-5 py-4">Password</th>
                                <th class="px-5 py-4">Role</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#322d2a]/10 bg-white">
                            @forelse($users as $user)
                                <tr class="transition hover:bg-[#f0efe9]">
                                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                                        @csrf
                                        @method('PUT')

                                        <td class="px-5 py-4 align-top">
                                            <input name="name" value="{{ $user->name }}" class="w-full min-w-[180px] rounded-[20px] border border-white bg-[#f0efe9] px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white focus:ring-4 focus:ring-[#f97316]/20" required>
                                        </td>
                                        <td class="px-5 py-4 align-top">
                                            <input name="email" value="{{ $user->email }}" type="email" class="w-full min-w-[220px] rounded-[20px] border border-white bg-[#f0efe9] px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white focus:ring-4 focus:ring-[#f97316]/20" required>
                                        </td>
                                        <td class="px-5 py-4 align-top">
                                            <input name="password" type="password" placeholder="Opsional" class="w-full min-w-[170px] rounded-[20px] border border-white bg-[#f0efe9] px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition placeholder:text-[#6f6b67]/60 focus:border-[#f97316] focus:bg-white focus:ring-4 focus:ring-[#f97316]/20">
                                        </td>
                                        <td class="px-5 py-4 align-top">
                                            <div class="flex min-w-[260px] flex-wrap gap-2">
                                                @foreach($roles as $role)
                                                    <label class="inline-flex items-center gap-2 rounded-full border border-[#322d2a]/10 bg-[#f0efe9] px-3 py-2 text-xs font-normal text-[#322d2a]">
                                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" @checked($user->roles->contains('id', $role->id)) class="rounded border-[#322d2a]/20 text-[#f97316] focus:ring-[#f97316]">
                                                        {{ $role->label }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 align-top">
                                            <div class="flex justify-end gap-2">
                                                <button class="rounded-full bg-[#f97316] px-4 py-2.5 text-xs font-normal text-white transition hover:bg-[#ea580c]">Update</button>
                                            </div>
                                        </td>
                                    </form>
                                </tr>

                                @if(! $user->is(auth()->user()))
                                    <tr class="bg-white transition hover:bg-[#f0efe9]">
                                        <td colspan="5" class="px-5 pb-4 text-right">
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="rounded-full bg-rose-50 px-4 py-2 text-xs font-normal text-rose-600 transition hover:bg-rose-100 hover:text-rose-700">Hapus User</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center text-sm font-normal text-[#6f6b67]">User tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-[#322d2a]/10 bg-[#f0efe9] px-5 py-4">
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
