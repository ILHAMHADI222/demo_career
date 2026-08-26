<x-layouts.app title="Lowongan - Career Indodata">
    <section class="bg-[#f0efe9] px-4 pt-6 sm:pt-8 pb-12 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1200px]">
            <div class="rounded-[40px] border border-white bg-white p-6 sm:p-7 lg:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                            Cari Lowongan
                        </div>
                        <h2 class="mt-2 text-[28px] font-light leading-none tracking-[0.28px] text-[#322d2a] md:text-[34px]">
                            Filter Pencarian
                        </h2>
                        <p class="mt-1.5 text-xs sm:text-sm font-normal leading-[1.5] text-[#6f6b67]">
                            Sesuaikan hasil lowongan berdasarkan preferensi Anda.
                        </p>
                    </div>

                    @if(request()->hasAny(['keyword', 'department', 'location', 'work_arrangement']))
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#f97316] px-4 py-2 text-xs font-normal text-[#f97316] transition hover:bg-[#f97316] hover:text-white">
                            Hapus semua filter ×
                        </a>
                    @endif
                </div>

                <form method="GET" action="{{ route('jobs.index') }}" class="mt-6 grid gap-3.5 lg:grid-cols-12">
                    <div class="lg:col-span-4">
                        <label class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Keyword</label>
                        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Posisi, skill, atau kata kunci" class="mt-1.5 w-full rounded-[16px] border border-[#322d2a]/20 bg-[#f0efe9] px-4 py-2.5 text-sm font-normal tracking-[0.14px] text-[#322d2a] outline-none transition placeholder:text-[#8b8b8b] focus:border-[#f97316] focus:bg-white">
                    </div>

                    <div class="lg:col-span-3">
                        <label class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Departemen</label>
                        <select name="department" class="mt-1.5 w-full rounded-[16px] border border-[#322d2a]/20 bg-[#f0efe9] px-4 py-2.5 text-sm font-normal tracking-[0.14px] text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                            <option value="">Semua departemen</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->code }}" @selected(request('department') === $department->code)>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-3">
                        <label class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Lokasi</label>
                        <input type="text" name="location" value="{{ request('location') }}" placeholder="Jakarta, Bandung, Remote" class="mt-1.5 w-full rounded-[16px] border border-[#322d2a]/20 bg-[#f0efe9] px-4 py-2.5 text-sm font-normal tracking-[0.14px] text-[#322d2a] outline-none transition placeholder:text-[#8b8b8b] focus:border-[#f97316] focus:bg-white">
                    </div>

                    <div class="lg:col-span-2">
                        <label class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Mode Kerja</label>
                        <select name="work_arrangement" class="mt-1.5 w-full rounded-[16px] border border-[#322d2a]/20 bg-[#f0efe9] px-4 py-2.5 text-sm font-normal tracking-[0.14px] text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                            <option value="">Semua mode</option>
                            @foreach(['ONSITE', 'HYBRID', 'REMOTE'] as $v)
                                <option value="{{ $v }}" @selected(request('work_arrangement') === $v)>{{ ucfirst(strtolower($v)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-wrap gap-2.5 lg:col-span-12 mt-2">
                        <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#f97316] px-5 py-2.5 text-xs font-normal text-white shadow-sm transition hover:bg-[#ea580c]">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#322d2a]/30 bg-transparent px-5 py-2.5 text-xs font-normal text-[#322d2a] transition hover:bg-[#f0efe9]">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="mt-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        Lowongan Tersedia
                    </div>
                    <h2 class="mt-2 text-[28px] font-light leading-none tracking-[0.32px] text-[#322d2a] md:text-[36px]">
                        Peluang karier untuk Anda
                    </h2>
                    <p class="mt-2 text-xs sm:text-sm font-normal text-[#6f6b67]">
                        Menampilkan {{ $jobs->count() }} dari {{ $jobs->total() }} lowongan.
                    </p>
                </div>
                <div class="rounded-full border border-[#322d2a]/20 bg-white px-4 py-2 text-xs font-normal text-[#6f6b67]">
                    Halaman {{ $jobs->currentPage() }} dari {{ $jobs->lastPage() }}
                </div>
            </div>

            <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse($jobs as $job)
                    <article class="group flex min-h-[350px] flex-col justify-between rounded-[32px] border border-white bg-white p-5 sm:p-6 transition hover:-translate-y-1 hover:border-[#f97316]/40 hover:bg-[#fff8f3]">
                        <div>
                            <div class="flex flex-wrap items-start justify-between gap-2">
                                <span class="rounded-full border border-[#f97316]/50 bg-[#fff3e8] px-3 py-0.5 text-[10px] font-normal uppercase tracking-[0.12em] text-[#f97316]">
                                    {{ $job->department?->name ?? 'General' }}
                                </span>
                                <span class="rounded-full border border-[#322d2a]/30 px-3 py-0.5 text-[10px] font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                    Open
                                </span>
                            </div>

                            <h2 class="mt-5 text-[22px] font-light leading-[1.2] text-[#322d2a] transition group-hover:text-[#f97316]">
                                {{ $job->title }}
                            </h2>

                            <div class="mt-3 flex flex-wrap gap-1.5 text-[10px] font-normal uppercase tracking-[0.1em] text-[#6f6b67]">
                                <span class="rounded-full bg-[#f0efe9] px-2.5 py-1">{{ $job->location }}</span>
                                <span class="rounded-full bg-[#f0efe9] px-2.5 py-1">{{ ucwords(str_replace('_', ' ', $job->employment_type)) }}</span>
                                <span class="rounded-full bg-[#f0efe9] px-2.5 py-1">{{ ucwords(str_replace('_', ' ', $job->work_arrangement)) }}</span>
                            </div>

                            <p class="mt-4 line-clamp-3 text-xs sm:text-sm font-normal leading-[1.5] text-[#6f6b67]">
                                {{ $job->description }}
                            </p>

                            <div class="mt-5 rounded-[20px] border border-[#322d2a]/10 bg-[#f0efe9] p-4">
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <p class="text-[10px] font-normal uppercase tracking-[0.12em] text-[#8b8b8b]">Mulai Pendaftaran</p>
                                        <p class="mt-1 text-xs sm:text-sm font-medium text-[#322d2a]">{{ optional($job->registration_start_at)->format('d M Y') ?? 'Sudah dibuka' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-normal uppercase tracking-[0.12em] text-[#8b8b8b]">Berakhir</p>
                                        <p class="mt-1 text-xs sm:text-sm font-medium text-[#322d2a]">{{ optional($job->deadline_at)->format('d M Y') ?? 'Tanpa batas waktu' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-between border-t border-[#322d2a]/15 pt-4">
                            <span class="text-xs font-normal uppercase tracking-[0.1em] text-[#f97316]">Pendaftaran Dibuka</span>
                            <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center justify-center rounded-full bg-[#f97316] px-4 py-2 text-xs font-normal text-white shadow-sm transition hover:bg-[#ea580c]">
                                Detail Lowongan →
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="md:col-span-2 xl:col-span-3">
                        <div class="rounded-[32px] border border-dashed border-[#f97316]/60 bg-white px-6 py-12 text-center">
                            <div class="mx-auto mb-4 h-1.5 w-1.5 bg-[#f97316]"></div>
                            <h3 class="text-[24px] font-light text-[#322d2a]">Lowongan tidak ditemukan</h3>
                            <p class="mx-auto mt-2 max-w-lg text-xs sm:text-sm font-normal text-[#6f6b67]">
                                Tidak ada lowongan yang cocok dengan filter pencarian Anda. Coba gunakan kata kunci atau filter yang berbeda.
                            </p>
                            <a href="{{ route('jobs.index') }}" class="mt-5 inline-flex rounded-full bg-[#f97316] px-5 py-2.5 text-xs font-normal text-white shadow-sm transition hover:bg-[#ea580c]">
                                Reset Pencarian
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($jobs->hasPages())
                <div class="mt-8 rounded-[24px] border border-white bg-white p-3.5">
                    {{ $jobs->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
