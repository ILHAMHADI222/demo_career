<x-layouts.app :title="$job->title . ' - Career PT Indodata Solusi Persada'">
    {{-- HERO SECTION --}}
    <section class="bg-[#f0efe9] px-4 pb-6 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">
            {{-- Breadcrumb / Back Link --}}
            <div class="mb-4">
                <a
                    href="{{ route('jobs.index') }}"
                    class="inline-flex items-center gap-2 rounded-full border border-[#322d2a]/15 bg-white px-4 py-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a] shadow-xs transition hover:border-[#f97316] hover:bg-[#fff8f3] hover:text-[#f97316]"
                >
                    <span>←</span>
                    <span>Kembali ke Semua Lowongan</span>
                </a>
            </div>

            {{-- Main Hero Banner --}}
            <div
                class="relative min-h-[300px] overflow-hidden rounded-[36px] bg-cover bg-center p-6 sm:p-8 md:p-10 lg:min-h-[320px]"
                style="background-image: url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=2000&q=85');"
            >
                <div class="absolute inset-0 bg-gradient-to-r from-[#0f0e12]/90 via-[#0f0e12]/75 to-[#0f0e12]/45"></div>

                <div class="relative z-10 flex h-full flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-white/90">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                            PT Indodata Solusi Persada • Detail Posisi
                        </div>

                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <span class="rounded-full border border-white/25 bg-white/15 px-3 py-1 text-xs font-normal uppercase tracking-[0.12em] text-white backdrop-blur-md">
                                {{ $job->department?->name ?? 'General' }}
                            </span>
                            <span class="rounded-full border border-white/25 bg-white/15 px-3 py-1 text-xs font-normal uppercase tracking-[0.12em] text-white backdrop-blur-md">
                                {{ ucwords(str_replace('_', ' ', $job->employment_type)) }}
                            </span>
                            <span class="rounded-full border border-white/25 bg-white/15 px-3 py-1 text-xs font-normal uppercase tracking-[0.12em] text-white backdrop-blur-md">
                                {{ ucwords(str_replace('_', ' ', $job->work_arrangement)) }}
                            </span>
                            <span class="rounded-full border border-emerald-400/40 bg-emerald-500/20 px-3 py-1 text-xs font-normal uppercase tracking-[0.12em] text-emerald-200 backdrop-blur-md">
                                Pendaftaran Dibuka
                            </span>
                        </div>

                        <h1 class="mt-4 max-w-4xl text-[28px] font-light leading-tight tracking-[0.32px] text-white sm:text-[36px] md:text-[42px]">
                            {{ $job->title }}
                        </h1>
                    </div>

                    <div class="mt-6 flex flex-wrap items-center gap-2.5 pt-4 border-t border-white/15 text-xs text-white/90">
                        <div class="flex items-center gap-1.5 rounded-full border border-white/20 bg-white/10 px-3.5 py-1.5 backdrop-blur-md">
                            <span class="text-[#f97316]">📍</span>
                            <span>{{ $job->location }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 rounded-full border border-white/20 bg-white/10 px-3.5 py-1.5 backdrop-blur-md">
                            <span class="text-[#f97316]">👥</span>
                            <span>{{ $job->number_of_openings ?? 1 }} Formasi Dibutuhkan</span>
                        </div>
                        <div class="flex items-center gap-1.5 rounded-full border border-white/20 bg-white/10 px-3.5 py-1.5 backdrop-blur-md">
                            <span class="text-[#f97316]">⏰</span>
                            <span>Batas Lamaran: {{ optional($job->deadline_at)->format('d M Y') ?? 'Tanpa Batas Waktu' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT GRID --}}
    <section class="bg-[#f0efe9] px-4 pb-12 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">
            <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr] lg:items-start">

                {{-- LEFT COLUMN: DETAIL SECTIONS --}}
                <div class="space-y-5">

                    {{-- OVERVIEW CARD --}}
                    <div class="rounded-[36px] border border-white bg-white p-6 sm:p-7 md:p-8 shadow-xs">
                        <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                            Tentang Pekerjaan
                        </div>
                        <h2 class="mt-3 text-[22px] font-light leading-none tracking-[0.24px] text-[#322d2a] md:text-[26px]">
                            Deskripsi Posisi
                        </h2>
                        <div class="mt-4 text-xs sm:text-sm font-normal leading-[1.7] text-[#6f6b67]">
                            <p class="whitespace-pre-line">{{ $job->description ?: 'Posisi ini bertanggung jawab untuk mendukung operasional dan pengembangan layanan di lingkungan PT Indodata Solusi Persada.' }}</p>
                        </div>

                        {{-- Quick Highlights --}}
                        <div class="mt-6 grid gap-3 sm:grid-cols-3">
                            <div class="rounded-[20px] border border-[#322d2a]/10 bg-[#f0efe9] p-3.5">
                                <p class="text-[10px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Mulai Pendaftaran</p>
                                <p class="mt-1 text-xs sm:text-sm font-semibold text-[#322d2a]">{{ optional($job->registration_start_at)->format('d M Y') ?? 'Sudah Dibuka' }}</p>
                            </div>
                            <div class="rounded-[20px] border border-[#322d2a]/10 bg-[#f0efe9] p-3.5">
                                <p class="text-[10px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Batas Akhir</p>
                                <p class="mt-1 text-xs sm:text-sm font-semibold text-[#322d2a]">{{ optional($job->deadline_at)->format('d M Y') ?? 'Terbuka Terus' }}</p>
                            </div>
                            <div class="rounded-[20px] border border-[#f97316]/30 bg-[#fff3e8] p-3.5">
                                <p class="text-[10px] font-normal uppercase tracking-[0.12em] text-[#f97316]">Status Lowongan</p>
                                <p class="mt-1 text-xs sm:text-sm font-semibold text-[#f97316]">Pendaftaran Aktif</p>
                            </div>
                        </div>
                    </div>

                    {{-- RESPONSIBILITIES CARD --}}
                    <div class="rounded-[36px] border border-white bg-white p-6 sm:p-7 md:p-8 shadow-xs">
                        <div class="flex items-start gap-4">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#f97316] text-xs font-normal text-white">
                                01
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                    <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                                    Peran & Tugas
                                </div>
                                <h3 class="mt-2 text-[22px] font-light leading-none tracking-[0.24px] text-[#322d2a] md:text-[26px]">
                                    Tanggung Jawab Utama
                                </h3>
                                <div class="prose mt-4 max-w-none text-xs sm:text-sm font-normal leading-[1.7] text-[#6f6b67]">
                                    {!! nl2br(e($job->responsibilities ?: 'Tanggung jawab lengkap untuk posisi ini akan dipaparkan secara mendalam saat tahap screening dan wawancara lanjutan.')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- REQUIREMENTS CARD --}}
                    <div class="rounded-[36px] border border-white bg-white p-6 sm:p-7 md:p-8 shadow-xs">
                        <div class="flex items-start gap-4">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#f97316] text-xs font-normal text-white">
                                02
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                    <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                                    Kriteria Pelamar
                                </div>
                                <h3 class="mt-2 text-[22px] font-light leading-none tracking-[0.24px] text-[#322d2a] md:text-[26px]">
                                    Kualifikasi & Persyaratan
                                </h3>
                                <div class="prose mt-4 max-w-none text-xs sm:text-sm font-normal leading-[1.7] text-[#6f6b67]">
                                    {!! nl2br(e($job->requirements ?: 'Kualifikasi disesuaikan dengan kompetensi teknis, latar belakang pendidikan, serta dedikasi dalam bidang terkait.')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BENEFITS CARD --}}
                    <div class="rounded-[36px] border border-white bg-white p-6 sm:p-7 md:p-8 shadow-xs">
                        <div class="flex items-start gap-4">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#f97316] text-xs font-normal text-white">
                                03
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                    <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                                    Fasilitas & Kompensasi
                                </div>
                                <h3 class="mt-2 text-[22px] font-light leading-none tracking-[0.24px] text-[#322d2a] md:text-[26px]">
                                    Benefit yang Didapatkan
                                </h3>
                                <div class="prose mt-4 max-w-none text-xs sm:text-sm font-normal leading-[1.7] text-[#6f6b67]">
                                    {!! nl2br(e($job->benefits ?: 'Benefit kompetitif, lingkungan kerja kolaboratif, serta peluang pengembangan karir profesional.')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SCREENING QUESTIONS CARD --}}
                    @if($job->questions && $job->questions->count() > 0)
                        <div class="rounded-[36px] border border-white bg-white p-6 sm:p-7 md:p-8 shadow-xs">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                                <div>
                                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                                        Screening Awal
                                    </div>
                                    <h3 class="mt-2 text-[22px] font-light leading-none tracking-[0.24px] text-[#322d2a] md:text-[26px]">
                                        Pertanyaan Tambahan
                                    </h3>
                                </div>
                                <span class="rounded-full border border-[#322d2a]/20 bg-[#f0efe9] px-3.5 py-1 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                    {{ $job->questions->count() }} Pertanyaan
                                </span>
                            </div>

                            <div class="mt-5 space-y-3">
                                @foreach($job->questions as $question)
                                    <div class="rounded-[20px] border border-[#322d2a]/10 bg-[#f0efe9] p-4">
                                        <p class="text-sm font-semibold text-[#322d2a]">{{ $loop->iteration }}. {{ $question->question }}</p>
                                        <div class="mt-2 flex items-center gap-2 text-xs text-[#6f6b67]">
                                            <span class="rounded-md bg-white px-2 py-0.5 font-mono text-[10px] uppercase font-bold text-[#322d2a] border border-[#322d2a]/10">{{ $question->type }}</span>
                                            <span>•</span>
                                            <span class="{{ $question->required ? 'text-[#f97316] font-semibold' : 'text-[#6f6b67]' }}">
                                                {{ $question->required ? 'Wajib diisi saat seleksi' : 'Opsional' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- HELP & SUPPORT CARD (SEBELAH KIRI) --}}
                    <div class="rounded-[36px] border border-white bg-white p-6 sm:p-7 md:p-8 shadow-xs">
                        <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                            Bantuan Pelamar
                        </div>
                        <h4 class="mt-2 text-lg font-semibold text-[#322d2a]">Butuh Informasi Lebih Lanjut?</h4>
                        <p class="mt-2 text-xs sm:text-sm font-normal leading-[1.6] text-[#6f6b67]">
                            Jika Anda mengalami kendala teknis saat mendaftar atau mengunggah berkas, silakan hubungi tim HRD kami melalui email resmi perusahaan.
                        </p>
                        <div class="mt-4 rounded-[18px] border border-[#322d2a]/10 bg-[#f0efe9] p-3.5 text-xs text-[#322d2a]">
                            <p class="font-medium">PT Indodata Solusi Persada</p>
                            <p class="mt-0.5 text-xs text-[#6f6b67]">hrd@indodata.co.id</p>
                        </div>
                    </div>

                </div>

                {{-- RIGHT COLUMN: SIDEBAR --}}
                <aside class="space-y-6 lg:sticky lg:top-24">

                    {{-- SUMMARY & APPLY CARD --}}
                    <div class="rounded-[36px] border border-white bg-white p-6 sm:p-7 shadow-xs">
                        <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                            Informasi Rekrutmen
                        </div>
                        <h2 class="mt-2 text-[22px] font-light leading-none tracking-[0.24px] text-[#322d2a]">
                            Ringkasan Posisi
                        </h2>

                        <div class="mt-5 space-y-2.5 text-sm">
                            <div class="flex items-center justify-between rounded-[18px] border border-[#322d2a]/10 bg-[#f0efe9] px-4 py-3">
                                <span class="text-xs uppercase tracking-[0.12em] text-[#6f6b67]">Departemen</span>
                                <span class="font-semibold text-[#322d2a]">{{ $job->department?->name ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between rounded-[18px] border border-[#322d2a]/10 bg-[#f0efe9] px-4 py-3">
                                <span class="text-xs uppercase tracking-[0.12em] text-[#6f6b67]">Divisi</span>
                                <span class="font-semibold text-[#322d2a]">{{ $job->division?->name ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between rounded-[18px] border border-[#322d2a]/10 bg-[#f0efe9] px-4 py-3">
                                <span class="text-xs uppercase tracking-[0.12em] text-[#6f6b67]">Jabatan / Posisi</span>
                                <span class="font-semibold text-[#322d2a]">{{ $job->position?->name ?? $job->title }}</span>
                            </div>
                            <div class="flex items-center justify-between rounded-[18px] border border-[#322d2a]/10 bg-[#f0efe9] px-4 py-3">
                                <span class="text-xs uppercase tracking-[0.12em] text-[#6f6b67]">Lokasi</span>
                                <span class="font-semibold text-[#322d2a]">{{ $job->location }}</span>
                            </div>
                            <div class="flex items-center justify-between rounded-[18px] border border-[#322d2a]/10 bg-[#f0efe9] px-4 py-3">
                                <span class="text-xs uppercase tracking-[0.12em] text-[#6f6b67]">Jumlah Kebutuhan</span>
                                <span class="font-semibold text-[#322d2a]">{{ $job->number_of_openings ?? 1 }} Orang</span>
                            </div>

                            <div class="rounded-[20px] border border-[#f97316]/30 bg-[#fff8f3] p-3.5 text-xs">
                                <p class="font-semibold text-[#f97316] uppercase tracking-[0.12em]">Jadwal Pendaftaran</p>
                                <p class="mt-1.5 text-[#322d2a]">
                                    <span class="font-medium">Mulai:</span> {{ optional($job->registration_start_at)->format('d M Y') ?? 'Sudah dibuka' }}
                                </p>
                                <p class="mt-0.5 text-[#322d2a]">
                                    <span class="font-medium">Berakhir:</span> {{ optional($job->deadline_at)->format('d M Y') ?? 'Tanpa batas waktu' }}
                                </p>
                            </div>
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="mt-5 pt-4 border-t border-[#322d2a]/10">
                            @auth
                                @if (auth()->user()->hasAnyRole(['SUPERADMIN', 'ADMIN']))
                                    <a
                                        href="{{ route('admin.applications.index') }}"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#322d2a] px-5 py-3 text-xs font-normal tracking-[0.14px] text-white transition hover:bg-black"
                                    >
                                        <span>Lihat Daftar Pelamar</span>
                                        <span>→</span>
                                    </a>
                                @else
                                    <form method="POST" action="{{ route('jobs.apply', $job) }}" onsubmit="return confirm('Apakah Anda yakin ingin mengirim lamaran untuk posisi {{ $job->title }}?')">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#f97316] px-5 py-3.5 text-xs font-normal tracking-[0.14px] text-white shadow-sm transition hover:bg-[#ea580c]"
                                        >
                                            <span>Lamar Posisi Ini Sekarang</span>
                                            <span>→</span>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <div class="space-y-2">
                                    <a
                                        href="{{ route('register') }}"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#f97316] px-5 py-3.5 text-xs font-normal tracking-[0.14px] text-white shadow-sm transition hover:bg-[#ea580c]"
                                    >
                                        <span>Daftar Akun untuk Melamar</span>
                                        <span>→</span>
                                    </a>
                                    <a
                                        href="{{ route('login') }}"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-[#322d2a]/20 bg-white px-5 py-2.5 text-xs font-normal tracking-[0.12em] uppercase text-[#322d2a] transition hover:bg-[#f0efe9]"
                                    >
                                        <span>Sudah punya akun? Masuk</span>
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>

                    {{-- ALUR TAHAPAN REKRUTMEN CARD --}}
                    <div class="rounded-[36px] border border-white bg-white p-6 sm:p-7 shadow-xs">
                        <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                            Tahapan Seleksi
                        </div>
                        <h3 class="mt-2 text-[22px] font-light leading-none tracking-[0.24px] text-[#322d2a]">
                            Alur Rekrutmen
                        </h3>
                        <p class="mt-1.5 text-xs font-normal leading-[1.5] text-[#6f6b67]">
                            Berikut 6 tahapan seleksi terstruktur yang akan Anda lalui:
                        </p>

                        <div class="mt-4 space-y-2">
                            @foreach([
                                ['title' => 'Melengkapi Profile', 'desc' => 'Pengisian data diri, pendidikan, pengalaman, dan berkas CV.'],
                                ['title' => 'Seleksi Administrasi', 'desc' => 'Verifikasi kualifikasi dan kelengkapan berkas lamaran.'],
                                ['title' => 'Psikotest', 'desc' => 'Pengerjaan asesmen psikologi dan tes potensi secara online.'],
                                ['title' => 'Interview HRD', 'desc' => 'Sesi wawancara mendalam terkait profil dan budaya kerja.'],
                                ['title' => 'Interview User', 'desc' => 'Evaluasi kompetensi teknis bersama tim dan calon atasan.'],
                                ['title' => 'Offering', 'desc' => 'Penawaran kerja resmi, kompensasi benefit, dan onboarding.']
                            ] as $step)
                                <div class="flex items-start justify-between gap-3 rounded-[18px] border border-[#322d2a]/10 bg-[#f0efe9] p-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-[#322d2a]">{{ $step['title'] }}</p>
                                        <p class="mt-0.5 text-[11px] font-normal leading-relaxed text-[#6f6b67]">{{ $step['desc'] }}</p>
                                    </div>
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#f97316] text-[10px] font-normal text-white">
                                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </aside>
            </div>
        </div>
    </section>

    {{-- BOTTOM CTA BANNER --}}
    <section class="bg-[#f0efe9] px-4 pb-20 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">
            <div class="relative overflow-hidden rounded-[48px] bg-[#0f0e12] p-8 text-white sm:p-12 md:p-16 lg:flex lg:items-center lg:justify-between">
                <div class="relative z-10 max-w-2xl">
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#f97316]">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        Career Indodata
                    </div>
                    <h2 class="mt-4 text-[32px] font-light leading-tight tracking-[0.32px] md:text-[42px]">
                        Siap memulai karir impian Anda bersama kami?
                    </h2>
                    <p class="mt-4 text-sm font-normal leading-[1.6] text-white/80">
                        Pastikan biodata dan dokumen pendukung Anda telah lengkap agar proses evaluasi administrasi berjalan cepat dan lancar.
                    </p>
                </div>

                <div class="relative z-10 mt-8 flex flex-wrap items-center gap-3 lg:mt-0">
                    @auth
                        <a
                            href="{{ route('dashboard') }}"
                            class="inline-flex items-center justify-center rounded-full bg-[#f97316] px-6 py-4 text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#ea580c]"
                        >
                            Buka Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('register') }}"
                            class="inline-flex items-center justify-center rounded-full bg-[#f97316] px-6 py-4 text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#ea580c]"
                        >
                            Daftar Sekarang
                        </a>
                    @endauth

                    <a
                        href="{{ route('jobs.index') }}"
                        class="inline-flex items-center justify-center rounded-full border border-white/40 bg-white/10 px-6 py-4 text-sm font-normal tracking-[0.14px] text-white backdrop-blur-md transition hover:bg-white/20"
                    >
                        Lihat Posisi Lain
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
