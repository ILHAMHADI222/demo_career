<x-layouts.app title="Kelola User - Career Indodata">
    <section class="min-h-screen bg-slate-50 py-10 md:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] bg-slate-950 p-8 text-white shadow-2xl md:p-10">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-300">Superadmin Control</p>
                <h1 class="mt-4 text-4xl font-black tracking-tight md:text-5xl">Kelola semua user, HRD, role, dan akses.</h1>
                <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-200">Panel ini khusus superadmin untuk membuat akun, mengubah data, dan mengatur role tanpa menghapus konsep role dan permission yang sudah ada.</p>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[0.85fr_1.15fr]">
                <form method="POST" action="{{ route('admin.users.store') }}" class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    @csrf
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-orange-500">Tambah User</p>
                    <h2 class="mt-2 text-2xl font-black text-slate-950">Buat akun baru</h2>

                    <div class="mt-6 grid gap-4">
                        <input name="name" value="{{ old('name') }}" placeholder="Nama" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100" required>
                        <input name="email" value="{{ old('email') }}" type="email" placeholder="Email" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100" required>
                        <input name="password" type="password" placeholder="Password minimal 8 karakter" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100" required>

                        <div>
                            <p class="mb-3 text-xs font-black uppercase tracking-[0.16em] text-slate-500">Role</p>
                            <div class="grid gap-2 sm:grid-cols-3">
                                @foreach($roles as $role)
                                    <label class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700">
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                                        {{ $role->label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button class="rounded-2xl bg-orange-500 px-5 py-3 text-sm font-black text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">Simpan User</button>
                    </div>
                </form>

                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-orange-500">Daftar User</p>
                            <h2 class="mt-2 text-2xl font-black text-slate-950">Semua akun sistem</h2>
                        </div>
                        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col gap-2 sm:flex-row">
                            <input name="keyword" value="{{ request('keyword') }}" placeholder="Cari user" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
                            <select name="role" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
                                <option value="">Semua role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ $role->label }}</option>
                                @endforeach
                            </select>
                            <button class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black text-white">Filter</button>
                        </form>
                    </div>

                    <div class="mt-6 space-y-4">
                        @foreach($users as $user)
                            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                @csrf
                                @method('PUT')
                                <div class="grid gap-3 xl:grid-cols-[1fr_1fr_1fr_auto]">
                                    <input name="name" value="{{ $user->name }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold" required>
                                    <input name="email" value="{{ $user->email }}" type="email" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold" required>
                                    <input name="password" type="password" placeholder="Password baru opsional" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold">
                                    <button class="rounded-xl bg-orange-500 px-4 py-3 text-sm font-black text-white">Update</button>
                                </div>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach($roles as $role)
                                        <label class="flex items-center gap-2 rounded-xl bg-white px-3 py-2 text-xs font-bold text-slate-700 ring-1 ring-slate-200">
                                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" @checked($user->roles->contains('id', $role->id))>
                                            {{ $role->label }}
                                        </label>
                                    @endforeach
                                </div>
                            </form>

                            @if(! $user->is(auth()->user()))
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')" class="-mt-2 text-right">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs font-black text-rose-600 hover:text-rose-700">Hapus User</button>
                                </form>
                            @endif
                        @endforeach
                    </div>

                    <div class="mt-6">{{ $users->links() }}</div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
