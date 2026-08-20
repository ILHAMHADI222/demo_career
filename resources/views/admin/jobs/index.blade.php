<x-layouts.app title="Kelola Lowongan - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-28 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">
            <div class="relative overflow-hidden rounded-[56px] bg-[#322d2a] p-8 text-white md:p-10">
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#f97316]/20 blur-3xl"></div>
                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>
                <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-normal uppercase tracking-[0.22em] text-white backdrop-blur">
                            <span class="h-2 w-2 rounded-full bg-[#f97316]"></span>
                            HRD Job Management
                        </p>
                        <h1 class="mt-5 text-4xl font-light tracking-tight md:text-5xl">Kelola lowongan pekerjaan.</h1>
                        <p class="mt-4 max-w-3xl text-sm leading-7 text-white/75">HRD dan superadmin bisa membuat, mengubah, mempublikasikan, menutup, dan menghapus lowongan tanpa mengganggu fitur rekrutmen yang sudah ada.</p>
                    </div>
                    <a href="{{ route('admin.jobs.create') }}" class="inline-flex w-fit items-center gap-2 rounded-full bg-[#f97316] px-6 py-3 text-sm font-normal text-white transition hover:bg-[#ea580c]">
                        Tambah Lowongan
                        <span>+</span>
                    </a>
                </div>
            </div>

            <div class="mt-8 rounded-[40px] border border-white bg-white p-5 md:p-8">
                <form method="GET" action="{{ route('admin.jobs.index') }}" class="grid gap-3 md:grid-cols-[1fr_260px_auto]">
                    <input name="keyword" value="{{ request('keyword') }}" placeholder="Cari judul, kode, lokasi" class="rounded-[24px] border border-white bg-[#f0efe9] px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition placeholder:text-[#6f6b67]/60 focus:border-[#f97316] focus:bg-white focus:ring-4 focus:ring-[#f97316]/20">
                    <select name="status" class="rounded-[24px] border border-white bg-[#f0efe9] px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white focus:ring-4 focus:ring-[#f97316]/20">
                        <option value="">Semua status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-[24px] bg-[#322d2a] px-6 py-3 text-sm font-normal text-white transition hover:bg-[#f97316]">Filter</button>
                </form>

                <div class="mt-6 overflow-hidden rounded-[32px] border border-[#322d2a]/10">
                    <table class="min-w-full divide-y divide-[#322d2a]/10 text-sm">
                        <thead class="bg-[#322d2a] text-left text-xs font-normal uppercase tracking-[0.14em] text-white">
                            <tr>
                                <th class="px-5 py-4">Lowongan</th>
                                <th class="px-5 py-4">Departemen</th>
                                <th class="px-5 py-4">Periode Pendaftaran</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Pelamar</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#322d2a]/10 bg-white">
                            @forelse($jobs as $job)
                                @php
                                    $effectiveState = $job->status;
                                    $stateClasses = 'bg-[#f0efe9] text-[#322d2a]';

                                    if ($job->status === 'PUBLISHED' && $job->registration_start_at && $job->registration_start_at->isFuture()) {
                                        $effectiveState = 'BELUM DIBUKA';
                                        $stateClasses = 'bg-sky-50 text-sky-700';
                                    } elseif ($job->isOpen()) {
                                        $effectiveState = 'OPEN';
                                        $stateClasses = 'bg-emerald-50 text-emerald-700';
                                    } elseif ($job->status === 'CLOSED' || ($job->deadline_at && $job->deadline_at->isPast())) {
                                        $effectiveState = 'TUTUP';
                                        $stateClasses = 'bg-rose-50 text-rose-700';
                                    } elseif ($job->status === 'DRAFT') {
                                        $effectiveState = 'DRAFT';
                                        $stateClasses = 'bg-[#fff3e8] text-[#f97316]';
                                    }
                                @endphp
                                <tr class="transition hover:bg-[#f0efe9]">
                                    <td class="px-5 py-4 align-top">
                                        <p class="font-normal text-[#322d2a]">{{ $job->title }}</p>
                                        <p class="mt-1 text-xs font-normal text-[#6f6b67]">{{ $job->job_code }} • {{ $job->location }} • {{ $job->recruiter?->name ?? 'Belum ada recruiter' }}</p>
                                    </td>
                                    <td class="px-5 py-4 align-top font-normal text-[#322d2a]">{{ $job->department?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 align-top">
                                        <div class="space-y-2 rounded-[24px] bg-[#f0efe9] p-4 ring-1 ring-[#322d2a]/10">
                                            <div>
                                                <p class="text-[10px] font-normal uppercase tracking-[0.18em] text-[#6f6b67]">Mulai</p>
                                                <p class="mt-1 text-sm font-normal text-[#322d2a]">{{ optional($job->registration_start_at)->format('d M Y H:i') ?? 'Langsung setelah publish' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-normal uppercase tracking-[0.18em] text-[#6f6b67]">Berakhir</p>
                                                <p class="mt-1 text-sm font-normal text-[#322d2a]">{{ optional($job->deadline_at)->format('d M Y H:i') ?? 'Tanpa batas waktu' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 align-top">
                                        <div class="space-y-2">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-normal {{ $stateClasses }}">{{ $effectiveState }}</span>
                                            <p class="text-xs font-normal text-[#6f6b67]">Status sistem: {{ $job->status }}</p>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 align-top font-normal text-[#322d2a]">{{ $job->applications_count ?? $job->applications()->count() }}</td>
                                    <td class="px-5 py-4 align-top">
                                        <div class="flex flex-wrap justify-end gap-2">
                                            <a href="{{ route('admin.jobs.edit', $job) }}" class="rounded-full bg-[#322d2a] px-3 py-2 text-xs font-normal text-white transition hover:bg-[#f97316]">Edit</a>
                                            <form method="POST" action="{{ route('admin.jobs.publish', $job) }}">@csrf<button class="rounded-full bg-emerald-500 px-3 py-2 text-xs font-normal text-white transition hover:bg-emerald-600">Publish</button></form>
                                            <form method="POST" action="{{ route('admin.jobs.close', $job) }}">@csrf<button class="rounded-full bg-amber-500 px-3 py-2 text-xs font-normal text-white transition hover:bg-amber-600">Tutup</button></form>
                                            <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}" onsubmit="return confirm('Hapus lowongan ini?')">@csrf @method('DELETE')<button class="rounded-full bg-rose-500 px-3 py-2 text-xs font-normal text-white transition hover:bg-rose-600">Hapus</button></form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-10 text-center font-normal text-[#6f6b67]">Belum ada lowongan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 rounded-[24px] border border-white bg-[#f0efe9] p-4">{{ $jobs->links() }}</div>
            </div>
        </div>
    </section>
</x-layouts.app>
