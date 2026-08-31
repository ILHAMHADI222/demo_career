<x-layouts.app title="Edit Lowongan - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">
            <div class="overflow-hidden rounded-[32px] sm:rounded-[36px] border border-white bg-white shadow-sm">
                <div class="relative overflow-hidden bg-[#322d2a] p-6 sm:p-7 md:p-8 text-white">
                    <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#f97316]/20 blur-3xl"></div>
                    <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>
                    <div class="relative z-10 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                        <div>
                            <a href="{{ route('admin.jobs.index') }}" class="inline-flex items-center gap-1.5 text-xs font-normal text-white/70 transition hover:text-[#f97316]">
                                <span>←</span>
                                Kembali ke daftar lowongan
                            </a>
                            <div class="mt-3">
                                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3.5 py-1 text-xs font-normal uppercase tracking-[0.16em] text-white backdrop-blur">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#f97316]"></span>
                                    Job Update
                                </span>
                            </div>
                            <h1 class="mt-2.5 text-2xl sm:text-3xl md:text-4xl font-light tracking-tight text-white">Edit Lowongan Pekerjaan</h1>
                            <p class="mt-1.5 max-w-2xl text-xs sm:text-sm font-normal text-white/75">Perbarui informasi posisi, periode pendaftaran, kualifikasi, serta deskripsi pekerjaan.</p>
                        </div>

                        <div class="rounded-[20px] border border-white/15 bg-white/10 px-4 py-3 text-xs backdrop-blur">
                            <p class="font-medium text-white">{{ $job->job_code ?? 'Kode otomatis' }}</p>
                            <p class="mt-0.5 text-white/75">Status: <strong class="text-[#f97316]">{{ $job->status ?? 'DRAFT' }}</strong></p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.jobs.update', $job) }}" class="p-5 sm:p-6 md:p-8">
                    @method('PUT')
                    @include('admin.jobs.form')
                </form>
            </div>
        </div>
    </section>
</x-layouts.app>
