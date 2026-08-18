<x-layouts.app title="Kelola Lowongan - Career Indodata">
    <section class="min-h-screen bg-slate-50 py-10 md:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-5 rounded-[2rem] bg-slate-950 p-8 text-white shadow-2xl md:p-10 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-300">HRD Job Management</p>
                    <h1 class="mt-4 text-4xl font-black tracking-tight md:text-5xl">Kelola lowongan pekerjaan.</h1>
                    <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-200">HRD dan superadmin bisa membuat, mengubah, mempublikasikan, menutup, dan menghapus lowongan tanpa mengganggu fitur rekrutmen yang sudah ada.</p>
                </div>
                <a href="{{ route('admin.jobs.create') }}" class="rounded-2xl bg-orange-500 px-6 py-3 text-sm font-black text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">Tambah Lowongan</a>
            </div>

            <div class="mt-8 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                <form method="GET" action="{{ route('admin.jobs.index') }}" class="grid gap-3 md:grid-cols-[1fr_260px_auto]">
                    <input name="keyword" value="{{ request('keyword') }}" placeholder="Cari judul, kode, lokasi" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
                    <select name="status" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
                        <option value="">Semua status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-2xl bg-slate-950 px-6 py-3 text-sm font-black text-white">Filter</button>
                </form>

                <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-100 text-left text-xs font-black uppercase tracking-[0.14em] text-slate-500">
                            <tr>
                                <th class="px-5 py-4">Lowongan</th>
                                <th class="px-5 py-4">Departemen</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Pelamar</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse($jobs as $job)
                                <tr>
                                    <td class="px-5 py-4">
                                        <p class="font-black text-slate-950">{{ $job->title }}</p>
                                        <p class="mt-1 text-xs font-semibold text-slate-500">{{ $job->job_code }} • {{ $job->location }} • {{ $job->recruiter?->name ?? 'Belum ada recruiter' }}</p>
                                    </td>
                                    <td class="px-5 py-4 font-semibold text-slate-700">{{ $job->department?->name ?? '-' }}</td>
                                    <td class="px-5 py-4"><span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-black text-orange-600">{{ $job->status }}</span></td>
                                    <td class="px-5 py-4 font-black text-slate-950">{{ $job->applications_count ?? $job->applications()->count() }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap justify-end gap-2">
                                            <a href="{{ route('admin.jobs.edit', $job) }}" class="rounded-xl bg-slate-950 px-3 py-2 text-xs font-black text-white">Edit</a>
                                            <form method="POST" action="{{ route('admin.jobs.publish', $job) }}">@csrf<button class="rounded-xl bg-emerald-500 px-3 py-2 text-xs font-black text-white">Publish</button></form>
                                            <form method="POST" action="{{ route('admin.jobs.close', $job) }}">@csrf<button class="rounded-xl bg-amber-500 px-3 py-2 text-xs font-black text-white">Tutup</button></form>
                                            <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}" onsubmit="return confirm('Hapus lowongan ini?')">@csrf @method('DELETE')<button class="rounded-xl bg-rose-500 px-3 py-2 text-xs font-black text-white">Hapus</button></form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-5 py-10 text-center font-semibold text-slate-500">Belum ada lowongan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">{{ $jobs->links() }}</div>
            </div>
        </div>
    </section>
</x-layouts.app>
