<x-layouts.app title="Career Indodata">
    <section class="bg-[#f0efe9] px-4 pb-12 pt-28 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto grid max-w-[1200px] gap-8 lg:grid-cols-[1.08fr_0.92fr] lg:items-stretch">
            <div
                class="relative min-h-[620px] overflow-hidden rounded-[56px] bg-cover bg-center"
                style="background-image: url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=2000&q=85');"
            >
                <div class="absolute inset-0 bg-[#0f0e12]/35"></div>
                <div class="absolute left-8 top-8 rounded-full border border-white/25 bg-white/15 px-5 py-3 text-xs font-normal uppercase tracking-[0.12em] text-white backdrop-blur-md sm:left-12 sm:top-12">
                    Hallonet Career Portal
                </div>

                <div class="absolute inset-x-8 bottom-8 sm:inset-x-12 sm:bottom-12">
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-white">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        PT Indodata Solusi Persada
                    </div>
                    <h1 class="mt-6 max-w-3xl text-[44px] font-light leading-none tracking-[0.52px] text-white md:text-[52px]">
                        Temukan peluang. Bangun masa depan.
                    </h1>
                    <p class="mt-6 max-w-2xl text-sm font-normal leading-[1.5] tracking-[0.14px] text-white/90">
                        Bergabung bersama PT INDODATA SOLUSI PERSADA dan temukan peluang karier yang sesuai dengan kemampuan, pengalaman, dan tujuan profesional Anda.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center rounded-full bg-[#f97316] px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#ea580c]">
                            Lihat Lowongan
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full border border-white/70 bg-white/15 px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-white backdrop-blur-md transition hover:bg-white/25">
                            Daftar Kandidat
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex min-h-[620px] flex-col justify-between rounded-[56px] border border-white bg-white px-6 py-9 sm:px-10 lg:px-12">
                <div>
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        Proses Rekrutmen
                    </div>
                    <h2 class="mt-6 text-[38px] font-light leading-none tracking-[0.52px] text-[#322d2a] md:text-[50px]">
                        Mudah, rapi, dan terstruktur.
                    </h2>
                    <p class="mt-5 text-sm font-normal leading-[1.5] tracking-[0.14px] text-[#6f6b67]">
                        Alur kandidat dibuat sederhana: daftar akun, lengkapi profil, pilih posisi, unggah dokumen, lalu pantau setiap tahap seleksi dari dashboard.
                    </p>
                </div>

                <div class="mt-10 space-y-3">
                    @foreach([
                        'Buat akun kandidat',
                        'Lengkapi biodata',
                        'Upload CV dan dokumen',
                        'Pilih posisi yang tersedia',
                        'Pantau proses seleksi'
                    ] as $item)
                        <div class="flex items-center justify-between gap-5 rounded-[24px] border border-[#322d2a]/15 bg-[#f0efe9] px-5 py-4">
                            <div>
                                <p class="text-base font-normal leading-[1.3] tracking-[0.16px] text-[#322d2a]">{{ $item }}</p>
                                <p class="mt-1 text-xs font-normal leading-[1.4] tracking-[0.12px] text-[#6f6b67]">Proses cepat, jelas, dan mudah dipantau.</p>
                            </div>
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#f97316] text-xs font-normal text-white">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#f0efe9] px-4 py-14 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1200px]">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        Lowongan Aktif
                    </div>
                    <h2 class="mt-6 max-w-3xl text-[38px] font-light leading-none tracking-[0.52px] text-[#322d2a] md:text-[50px]">
                        Temukan posisi yang sesuai dengan pengalaman Anda.
                    </h2>
                    <p class="mt-5 max-w-2xl text-sm font-normal leading-[1.5] tracking-[0.14px] text-[#6f6b67]">
                        Pilih posisi berdasarkan departemen, lokasi, tipe pekerjaan, dan sistem kerja yang sesuai dengan kebutuhan Anda.
                    </p>
                </div>

                <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#f97316] bg-transparent px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-[#f97316] transition hover:bg-[#f97316] hover:text-white">
                    Semua Lowongan
                </a>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse($jobs as $job)
                    <a href="{{ route('jobs.show', $job) }}" class="group flex min-h-[300px] flex-col justify-between rounded-[36px] border border-white bg-white p-6 transition hover:-translate-y-1 hover:border-[#f97316]/40 hover:bg-[#fff8f3]">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full border border-[#322d2a]/40 px-3 py-1 text-[10px] font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                    {{ $job->status }}
                                </span>
                                <span class="rounded-full border border-[#f97316]/50 bg-[#fff3e8] px-3 py-1 text-[10px] font-normal uppercase tracking-[0.12em] text-[#f97316]">
                                    {{ $job->department?->name ?? 'General' }}
                                </span>
                            </div>

                            <h3 class="mt-8 text-[28px] font-light leading-[1.15] tracking-[0.32px] text-[#322d2a] transition group-hover:text-[#f97316]">
                                {{ $job->title }}
                            </h3>

                            <p class="mt-4 text-xs font-normal uppercase leading-[1.5] tracking-[0.12em] text-[#8b8b8b]">
                                {{ $job->location }} · {{ str_replace('_', ' ', $job->employment_type) }} · {{ str_replace('_', ' ', $job->work_arrangement) }}
                            </p>

                            <p class="mt-5 line-clamp-3 text-sm font-normal leading-[1.5] tracking-[0.14px] text-[#6f6b67]">
                                {{ $job->description }}
                            </p>
                        </div>

                        <div class="mt-8 flex items-center justify-between border-t border-[#322d2a]/20 pt-5 text-sm font-normal tracking-[0.14px] text-[#322d2a]">
                            <span>Lihat Detail</span>
                            <span class="flex h-10 w-10 items-center justify-center rounded-full border border-[#f97316] bg-[#f97316] text-white transition group-hover:translate-x-1">→</span>
                        </div>
                    </a>
                @empty
                    <div class="rounded-[36px] border border-dashed border-[#f97316]/60 bg-white p-12 text-center text-sm font-normal leading-[1.5] tracking-[0.14px] text-[#6f6b67] md:col-span-2 xl:col-span-3">
                        Belum ada lowongan aktif saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="bg-[#f0efe9] px-4 py-14 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto grid max-w-[1200px] gap-8 lg:grid-cols-[0.95fr_1.05fr] lg:items-center">
            <div class="rounded-[56px] border border-white bg-white p-8 sm:p-10 lg:p-12">
                <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                    <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                    Proses Digital
                </div>
                <h2 class="mt-6 text-[38px] font-light leading-none tracking-[0.52px] text-[#322d2a] md:text-[50px]">
                    Proses lamaran lebih praktis dan transparan.
                </h2>
                <p class="mt-5 text-sm font-normal leading-[1.5] tracking-[0.14px] text-[#6f6b67]">
                    Seluruh data kandidat dan dokumen tersimpan dalam satu sistem sehingga proses seleksi menjadi lebih rapi bagi kandidat maupun HRD.
                </p>
            </div>

            <div class="grid gap-4">
                @foreach([
                    ['number' => '01', 'title' => 'Biodata Lengkap', 'description' => 'Kandidat dapat melengkapi seluruh informasi pribadi, pendidikan, pengalaman, dan kemampuan secara terstruktur.'],
                    ['number' => '02', 'title' => 'Upload Dokumen', 'description' => 'CV, sertifikat, dan dokumen pendukung lainnya dapat diunggah dalam format PDF dengan mudah.'],
                    ['number' => '03', 'title' => 'Review HRD', 'description' => 'HRD dapat memeriksa data dan dokumen kandidat serta memantau setiap tahapan proses rekrutmen.'],
                ] as $feature)
                    <div class="rounded-[32px] border border-[#322d2a]/20 bg-white/40 p-6 transition hover:border-[#f97316]/60 hover:bg-white">
                        <div class="flex items-start gap-5">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#f97316] text-xs font-normal text-white">{{ $feature['number'] }}</span>
                            <div>
                                <h3 class="text-[22px] font-normal leading-[1.3] tracking-[0.22px] text-[#322d2a]">{{ $feature['title'] }}</h3>
                                <p class="mt-2 text-sm font-normal leading-[1.5] tracking-[0.14px] text-[#6f6b67]">{{ $feature['description'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-[#f0efe9] px-4 py-14 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1200px] overflow-hidden rounded-[56px] bg-[#322d2a] text-white lg:grid lg:grid-cols-[1fr_0.75fr]">
            <div class="px-8 py-12 sm:px-10 lg:px-12">
                <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-white">
                    <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                    Mulai Karier Anda
                </div>
                <h2 class="mt-6 max-w-3xl text-[38px] font-light leading-none tracking-[0.52px] text-white md:text-[50px]">
                    Siap menjadi bagian dari PT INDODATA SOLUSI PERSADA?
                </h2>
                <p class="mt-5 max-w-2xl text-sm font-normal leading-[1.5] tracking-[0.14px] text-white/70">
                    Buat akun kandidat sekarang dan temukan posisi yang sesuai dengan kemampuan Anda.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full bg-[#f97316] px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#ea580c]">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center rounded-full border border-white/60 bg-transparent px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-white transition hover:bg-white/10">
                        Lihat Lowongan
                    </a>
                </div>
            </div>
            <div class="min-h-[260px] bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1200&q=85');"></div>
        </div>
    </section>
</x-layouts.app>
