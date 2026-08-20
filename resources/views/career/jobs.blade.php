<x-layouts.app title="Lowongan - Career Indodata">
    <section class="bg-[#f0efe9] px-4 pb-12 pt-28 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto grid max-w-[1200px] gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-stretch">
            <div
                class="relative min-h-[520px] overflow-hidden rounded-[56px] bg-cover bg-center"
                style="background-image: url('https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=2000&q=85');"
            >
                <div class="absolute inset-0 bg-[#0f0e12]/40"></div>
                <div class="absolute left-8 top-8 rounded-full border border-white/25 bg-white/15 px-5 py-3 text-xs font-normal uppercase tracking-[0.12em] text-white backdrop-blur-md sm:left-12 sm:top-12">
                    Career Opportunities
                </div>
                <div class="absolute inset-x-8 bottom-8 sm:inset-x-12 sm:bottom-12">
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-white">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        Lowongan PT Indodata Solusi Persada
                    </div>
                    <h1 class="mt-6 max-w-3xl text-[44px] font-light leading-none tracking-[0.52px] text-white md:text-[52px]">
                        Temukan posisi yang tepat untuk Anda.
                    </h1>
                    <p class="mt-6 max-w-2xl text-sm font-normal leading-[1.5] tracking-[0.14px] text-white/90">
                        Jelajahi lowongan aktif berdasarkan divisi, lokasi, tipe pekerjaan, dan sistem kerja yang sesuai dengan pengalaman serta tujuan karier Anda.
                    </p>
                </div>
            </div>

            <div class="flex min-h-[520px] flex-col justify-between rounded-[56px] border border-white bg-white px-6 py-9 sm:px-10 lg:px-12">
                <div>
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        Ringkasan Portal
                    </div>
                    <h2 class="mt-6 text-[38px] font-light leading-none tracking-[0.52px] text-[#322d2a] md:text-[50px]">
                        Semua peluang dalam satu halaman.
                    </h2>
                    <p class="mt-5 text-sm font-normal leading-[1.5] tracking-[0.14px] text-[#6f6b67]">
                        Gunakan filter untuk mempersempit hasil dan pilih posisi yang paling sesuai dengan profil profesional Anda.
                    </p>
                </div>

                <div class="mt-10 grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
                    <div class="rounded-[28px] border border-[#f97316]/30 bg-[#fff3e8] p-5">
                        <p class="text-[34px] font-light leading-none tracking-[0.32px] text-[#f97316]">{{ $jobs->total() }}</p>
                        <p class="mt-2 text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Lowongan</p>
                    </div>
                    <div class="rounded-[28px] border border-[#322d2a]/10 bg-[#f0efe9] p-5">
                        <p class="text-[34px] font-light leading-none tracking-[0.32px] text-[#322d2a]">{{ $departments->count() }}</p>
                        <p class="mt-2 text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Departemen</p>
                    </div>
                    <div class="rounded-[28px] border border-[#322d2a]/10 bg-[#f0efe9] p-5">
                        <p class="text-[34px] font-light leading-none tracking-[0.32px] text-[#322d2a]">Online</p>
                        <p class="mt-2 text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Proses</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#f0efe9] px-4 py-14 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1200px]">
            <div class="rounded-[56px] border border-white bg-white p-6 sm:p-8 lg:p-10">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                            Cari Lowongan
                        </div>
                        <h2 class="mt-4 text-[34px] font-light leading-none tracking-[0.32px] text-[#322d2a] md:text-[44px]">
                            Filter Pencarian
                        </h2>
                        <p class="mt-3 text-sm font-normal leading-[1.5] tracking-[0.14px] text-[#6f6b67]">
                            Sesuaikan hasil lowongan berdasarkan preferensi Anda.
                        </p>
                    </div>

                    @if(request()->hasAny(['keyword', 'department', 'location', 'work_arrangement']))
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#f97316] px-5 py-3 text-sm font-normal tracking-[0.14px] text-[#f97316] transition hover:bg-[#f97316] hover:text-white">
                            Hapus semua filter ×
                        </a>
                    @endif
                </div>

                <form method="GET" action="{{ route('jobs.index') }}" class="mt-8 grid gap-4 lg:grid-cols-12">
                    <div class="lg:col-span-4">
                        <label class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Keyword</label>
                        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Posisi, skill, atau kata kunci" class="mt-2 w-full rounded-[18px] border border-[#322d2a]/20 bg-[#f0efe9] px-5 py-4 text-sm font-normal tracking-[0.14px] text-[#322d2a] outline-none transition placeholder:text-[#8b8b8b] focus:border-[#f97316] focus:bg-white">
                    </div>

                    <div class="lg:col-span-3">
                        <label class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Departemen</label>
                        <select name="department" class="mt-2 w-full rounded-[18px] border border-[#322d2a]/20 bg-[#f0efe9] px-5 py-4 text-sm font-normal tracking-[0.14px] text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                            <option value="">Semua departemen</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->code }}" @selected(request('department') === $department->code)>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-3">
                        <label class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Lokasi</label>
                        <input type="text" name="location" value="{{ request('location') }}" placeholder="Jakarta, Bandung, Remote" class="mt-2 w-full rounded-[18px] border border-[#322d2a]/20 bg-[#f0efe9] px-5 py-4 text-sm font-normal tracking-[0.14px] text-[#322d2a] outline-none transition placeholder:text-[#8b8b8b] focus:border-[#f97316] focus:bg-white">
                    </div>

                    <div class="lg:col-span-2">
                        <label class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Mode Kerja</label>
                        <select name="work_arrangement" class="mt-2 w-full rounded-[18px] border border-[#322d2a]/20 bg-[#f0efe9] px-5 py-4 text-sm font-normal tracking-[0.14px] text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                            <option value="">Semua mode</option>
                            @foreach(['ONSITE', 'HYBRID', 'REMOTE'] as $v)
                                <option value="{{ $v }}" @selected(request('work_arrangement') === $v)>{{ ucfirst(strtolower($v)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-wrap gap-3 lg:col-span-12">
                        <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#f97316] px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#ea580c]">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#322d2a]/40 bg-transparent px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-[#322d2a] transition hover:bg-[#f0efe9]">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="mt-14 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        Lowongan Tersedia
                    </div>
                    <h2 class="mt-4 text-[38px] font-light leading-none tracking-[0.52px] text-[#322d2a] md:text-[50px]">
                        Peluang karier untuk Anda
                    </h2>
                    <p class="mt-4 text-sm font-normal leading-[1.5] tracking-[0.14px] text-[#6f6b67]">
                        Menampilkan {{ $jobs->count() }} dari {{ $jobs->total() }} lowongan.
                    </p>
                </div>
                <div class="rounded-full border border-[#322d2a]/20 bg-white px-5 py-3 text-sm font-normal tracking-[0.14px] text-[#6f6b67]">
                    Halaman {{ $jobs->currentPage() }} dari {{ $jobs->lastPage() }}
                </div>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse($jobs as $job)
                    <article class="group flex min-h-[390px] flex-col justify-between rounded-[36px] border border-white bg-white p-6 transition hover:-translate-y-1 hover:border-[#f97316]/40 hover:bg-[#fff8f3]">
                        <div>
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <span class="rounded-full border border-[#f97316]/50 bg-[#fff3e8] px-3 py-1 text-[10px] font-normal uppercase tracking-[0.12em] text-[#f97316]">
                                    {{ $job->department?->name ?? 'General' }}
                                </span>
                                <span class="rounded-full border border-[#322d2a]/30 px-3 py-1 text-[10px] font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                    Open
                                </span>
                            </div>

                            <h2 class="mt-8 text-[28px] font-light leading-[1.15] tracking-[0.32px] text-[#322d2a] transition group-hover:text-[#f97316]">
                                {{ $job->title }}
                            </h2>

                            <div class="mt-4 flex flex-wrap gap-2 text-[10px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">
                                <span class="rounded-full bg-[#f0efe9] px-3 py-1.5">{{ $job->location }}</span>
                                <span class="rounded-full bg-[#f0efe9] px-3 py-1.5">{{ ucwords(str_replace('_', ' ', $job->employment_type)) }}</span>
                                <span class="rounded-full bg-[#f0efe9] px-3 py-1.5">{{ ucwords(str_replace('_', ' ', $job->work_arrangement)) }}</span>
                            </div>

                            <p class="mt-5 line-clamp-3 text-sm font-normal leading-[1.5] tracking-[0.14px] text-[#6f6b67]">
                                {{ $job->description }}
                            </p>

                            <div class="mt-6 rounded-[24px] border border-[#322d2a]/10 bg-[#f0efe9] p-5">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <p class="text-[10px] font-normal uppercase tracking-[0.12em] text-[#8b8b8b]">Mulai Pendaftaran</p>
                                        <p class="mt-2 text-sm font-normal tracking-[0.14px] text-[#322d2a]">{{ optional($job->registration_start_at)->format('d M Y') ?? 'Sudah dibuka' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-normal uppercase tracking-[0.12em] text-[#8b8b8b]">Berakhir</p>
                                        <p class="mt-2 text-sm font-normal tracking-[0.14px] text-[#322d2a]">{{ optional($job->deadline_at)->format('d M Y') ?? 'Tanpa batas waktu' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-between border-t border-[#322d2a]/20 pt-5">
                            <span class="text-xs font-normal uppercase tracking-[0.12em] text-[#f97316]">Pendaftaran Dibuka</span>
                            <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center justify-center rounded-full bg-[#f97316] px-5 py-3 text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#ea580c]">
                                Detail
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="md:col-span-2 xl:col-span-3">
                        <div class="rounded-[36px] border border-dashed border-[#f97316]/60 bg-white px-6 py-16 text-center">
                            <div class="mx-auto mb-5 h-1.5 w-1.5 bg-[#f97316]"></div>
                            <h3 class="text-[28px] font-light leading-[1.15] tracking-[0.32px] text-[#322d2a]">Lowongan tidak ditemukan</h3>
                            <p class="mx-auto mt-4 max-w-lg text-sm font-normal leading-[1.5] tracking-[0.14px] text-[#6f6b67]">
                                Tidak ada lowongan yang cocok dengan filter pencarian Anda. Coba gunakan kata kunci atau filter yang berbeda.
                            </p>
                            <a href="{{ route('jobs.index') }}" class="mt-6 inline-flex rounded-full bg-[#f97316] px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#ea580c]">
                                Reset Pencarian
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($jobs->hasPages())
                <div class="mt-12 rounded-[28px] border border-white bg-white p-4">
                    {{ $jobs->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </section>

    <section class="bg-[#f0efe9] px-4 py-14 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1200px] overflow-hidden rounded-[56px] bg-[#322d2a] text-white lg:grid lg:grid-cols-[1fr_0.75fr]">
            <div class="px-8 py-12 sm:px-10 lg:px-12">
                <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-white">
                    <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                    Career Indodata
                </div>
                <h2 class="mt-6 max-w-3xl text-[38px] font-light leading-none tracking-[0.52px] text-white md:text-[50px]">
                    Belum menemukan posisi yang sesuai?
                </h2>
                <p class="mt-5 max-w-2xl text-sm font-normal leading-[1.5] tracking-[0.14px] text-white/70">
                    Buat akun kandidat terlebih dahulu agar proses pendaftaran lowongan berikutnya menjadi lebih cepat dan praktis.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full bg-[#f97316] px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#ea580c]">
                        Daftar Kandidat
                    </a>
                    <a href="{{ route('career.home') }}" class="inline-flex items-center justify-center rounded-full border border-white/60 bg-transparent px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-white transition hover:bg-white/10">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
            <div class="min-h-[260px] bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1200&q=85');"></div>
        </div>
    </section>
</x-layouts.app>
