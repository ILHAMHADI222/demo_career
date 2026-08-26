<x-layouts.app title="Career Indodata">
    <section class="bg-[#f0efe9] px-4 pb-10 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto grid max-w-[1200px] gap-6 lg:grid-cols-[1.08fr_0.92fr] lg:items-stretch">
            <div
                class="relative min-h-[500px] overflow-hidden rounded-[40px] bg-cover bg-center sm:min-h-[540px]"
                style="background-image: url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=2000&q=85');"
            >
                <div class="absolute inset-0 bg-[#0f0e12]/40"></div>
                <div class="absolute left-6 top-6 rounded-full border border-white/25 bg-white/15 px-4 py-2 text-xs font-normal uppercase tracking-[0.12em] text-white backdrop-blur-md sm:left-8 sm:top-8">
                    Hallonet Career Portal
                </div>

                <div class="absolute inset-x-6 bottom-6 sm:inset-x-8 sm:bottom-8">
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-white">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        PT Indodata Solusi Persada
                    </div>
                    <h1 class="mt-3 max-w-3xl text-[32px] font-light leading-tight tracking-[0.32px] text-white md:text-[42px]">
                        Temukan peluang. Bangun masa depan.
                    </h1>
                    <p class="mt-3 max-w-2xl text-xs sm:text-sm font-normal leading-[1.5] text-white/90">
                        Bergabung bersama PT INDODATA SOLUSI PERSADA dan temukan peluang karier yang sesuai dengan kemampuan, pengalaman, dan tujuan profesional Anda.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-2.5">
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center rounded-full bg-[#f97316] px-5 py-3 text-xs font-normal text-white shadow-sm transition hover:bg-[#ea580c]">
                            Lihat Lowongan
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full border border-white/70 bg-white/15 px-5 py-3 text-xs font-normal text-white backdrop-blur-md transition hover:bg-white/25">
                            Daftar Kandidat
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex min-h-[500px] flex-col justify-between rounded-[40px] border border-white bg-white p-6 sm:min-h-[540px] sm:p-8">
                <div>
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        Proses Rekrutmen
                    </div>
                    <h2 class="mt-3 text-[28px] font-light leading-none tracking-[0.32px] text-[#322d2a] md:text-[36px]">
                        Mudah, rapi, dan terstruktur.
                    </h2>
                    <p class="mt-2 text-xs sm:text-sm font-normal leading-[1.5] text-[#6f6b67]">
                        Alur seleksi terstruktur mulai dari melengkapi profil, seleksi administrasi, psikotest, interview HRD & user, hingga tahap offering.
                    </p>
                </div>

                <div class="mt-5 space-y-2">
                    @foreach([
                        ['title' => 'Melengkapi Profile', 'desc' => 'Pengisian data diri, pendidikan, pengalaman, dan berkas CV.'],
                        ['title' => 'Seleksi Administrasi', 'desc' => 'Verifikasi kualifikasi dan kelengkapan berkas lamaran.'],
                        ['title' => 'Psikotest', 'desc' => 'Pengerjaan asesmen psikologi dan tes potensi secara online.'],
                        ['title' => 'Interview HRD', 'desc' => 'Sesi wawancara mendalam terkait profil dan budaya kerja.'],
                        ['title' => 'Interview User', 'desc' => 'Evaluasi kompetensi teknis bersama tim dan calon atasan.'],
                        ['title' => 'Offering', 'desc' => 'Penawaran kerja resmi, kompensasi benefit, dan onboarding.']
                    ] as $item)
                        <div class="flex items-center justify-between gap-3 rounded-[16px] border border-[#322d2a]/10 bg-[#f0efe9] px-3.5 py-2.5">
                            <div>
                                <p class="text-xs sm:text-sm font-medium leading-[1.3] text-[#322d2a]">{{ $item['title'] }}</p>
                                <p class="mt-0.5 text-[11px] font-normal leading-[1.3] text-[#6f6b67]">{{ $item['desc'] }}</p>
                            </div>
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#f97316] text-[11px] font-normal text-white">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#f0efe9] px-4 py-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1200px]">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        Lowongan Aktif
                    </div>
                    <h2 class="mt-2 max-w-3xl text-[28px] font-light leading-none tracking-[0.32px] text-[#322d2a] md:text-[36px]">
                        Temukan posisi yang sesuai dengan pengalaman Anda.
                    </h2>
                    <p class="mt-2 max-w-2xl text-xs sm:text-sm font-normal leading-[1.5] text-[#6f6b67]">
                        Pilih posisi berdasarkan departemen, lokasi, tipe pekerjaan, dan sistem kerja yang sesuai dengan kebutuhan Anda.
                    </p>
                </div>

                <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#f97316] bg-transparent px-4 py-2 text-xs font-normal text-[#f97316] transition hover:bg-[#f97316] hover:text-white">
                    Semua Lowongan →
                </a>
            </div>

            <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse($jobs as $job)
                    <a href="{{ route('jobs.show', $job) }}" class="group flex min-h-[280px] flex-col justify-between rounded-[32px] border border-white bg-white p-5 transition hover:-translate-y-1 hover:border-[#f97316]/40 hover:bg-[#fff8f3]">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full border border-[#322d2a]/30 px-2.5 py-0.5 text-[10px] font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                    {{ $job->status }}
                                </span>
                                <span class="rounded-full border border-[#f97316]/50 bg-[#fff3e8] px-2.5 py-0.5 text-[10px] font-normal uppercase tracking-[0.12em] text-[#f97316]">
                                    {{ $job->department?->name ?? 'General' }}
                                </span>
                            </div>

                            <h3 class="mt-4 text-[22px] font-light leading-[1.2] text-[#322d2a] transition group-hover:text-[#f97316]">
                                {{ $job->title }}
                            </h3>

                            <p class="mt-2 text-[10px] font-normal uppercase leading-[1.5] tracking-[0.1em] text-[#8b8b8b]">
                                {{ $job->location }} · {{ str_replace('_', ' ', $job->employment_type) }} · {{ str_replace('_', ' ', $job->work_arrangement) }}
                            </p>

                            <p class="mt-3 line-clamp-3 text-xs sm:text-sm font-normal leading-[1.5] text-[#6f6b67]">
                                {{ $job->description }}
                            </p>
                        </div>

                        <div class="mt-5 flex items-center justify-between border-t border-[#322d2a]/15 pt-4 text-xs font-normal text-[#322d2a]">
                            <span>Lihat Detail Lowongan</span>
                            <span class="flex h-8 w-8 items-center justify-center rounded-full border border-[#f97316] bg-[#f97316] text-xs text-white transition group-hover:translate-x-1">→</span>
                        </div>
                    </a>
                @empty
                    <div class="rounded-[32px] border border-dashed border-[#f97316]/60 bg-white p-8 text-center text-xs sm:text-sm font-normal text-[#6f6b67] md:col-span-2 xl:col-span-3">
                        Belum ada lowongan aktif saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="bg-[#f0efe9] px-4 py-8 pb-14 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1200px] overflow-hidden rounded-[40px] bg-[#322d2a] text-white lg:grid lg:grid-cols-[1fr_0.75fr]">
            <div class="px-6 py-8 sm:px-8 lg:px-10">
                <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-white">
                    <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                    Mulai Karier Anda
                </div>
                <h2 class="mt-3 max-w-3xl text-[28px] font-light leading-tight tracking-[0.32px] text-white md:text-[36px]">
                    Siap menjadi bagian dari PT INDODATA SOLUSI PERSADA?
                </h2>
                <p class="mt-3 max-w-2xl text-xs sm:text-sm font-normal leading-[1.5] text-white/70">
                    Buat akun kandidat sekarang dan temukan posisi yang sesuai dengan kemampuan Anda.
                </p>
                <div class="mt-6 flex flex-wrap gap-2.5">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full bg-[#f97316] px-5 py-3 text-xs font-normal text-white shadow-sm transition hover:bg-[#ea580c]">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center rounded-full border border-white/60 bg-transparent px-5 py-3 text-xs font-normal text-white transition hover:bg-white/10">
                        Lihat Lowongan
                    </a>
                </div>
            </div>
            <div class="min-h-[220px] bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1200&q=85');"></div>
        </div>
    </section>
</x-layouts.app>
