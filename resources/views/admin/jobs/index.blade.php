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
                        <p class="mt-2 max-w-2xl text-xs sm:text-sm font-normal text-white/75">Buat, ubah, publikasikan, dan pantau seluruh posisi pekerjaan aktif di Career Indodata.</p>
                    </div>
                    <a href="{{ route('admin.jobs.create') }}" class="shrink-0 inline-flex items-center gap-2 rounded-full bg-[#f97316] px-5 py-2.5 text-xs font-medium text-white shadow-sm transition hover:bg-[#ea580c]">
                        <span>+</span>
                        Tambah Lowongan Baru
                    </a>
                </div>
            </div>

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
                                                {{ optional($job->deadline_at)->format('d M Y') ?? 'Tidak terbatas' }}
                                            </p>
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
                                            <div class="flex flex-wrap justify-end gap-1.5">
                                                <a href="{{ route('admin.jobs.edit', $job) }}" class="rounded-full bg-[#322d2a] px-3 py-1 text-[11px] font-medium text-white transition hover:bg-[#f97316]">
                                                    Edit
                                                </a>
                                                @if($job->status !== 'PUBLISHED')
                                                    <form method="POST" action="{{ route('admin.jobs.publish', $job) }}">
                                                        @csrf
                                                        <button class="rounded-full bg-emerald-600 px-3 py-1 text-[11px] font-medium text-white transition hover:bg-emerald-700">Publish</button>
                                                    </form>
                                                @endif
                                                @if($job->status === 'PUBLISHED')
                                                    <form method="POST" action="{{ route('admin.jobs.close', $job) }}">
                                                        @csrf
                                                        <button class="rounded-full bg-amber-600 px-3 py-1 text-[11px] font-medium text-white transition hover:bg-amber-700">Tutup</button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="rounded-full bg-rose-600 px-3 py-1 text-[11px] font-medium text-white transition hover:bg-rose-700">Hapus</button>
                                                </form>
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
    </section>
</x-layouts.app>
