<x-layouts.app title="Tambah Lowongan - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-28 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">
            <div class="overflow-hidden rounded-[56px] border border-white bg-white">
                <div class="relative overflow-hidden bg-[#322d2a] p-8 text-white md:p-10">
                    <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#f97316]/20 blur-3xl"></div>
                    <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>
                    <div class="relative z-10 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                        <div>
                            <a href="{{ route('admin.jobs.index') }}" class="inline-flex items-center gap-2 text-sm font-normal text-white/70 transition hover:text-[#f97316]">
                                <span>←</span>
                                Kembali ke daftar lowongan
                            </a>
                            <p class="mt-6 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-normal uppercase tracking-[0.22em] text-white backdrop-blur">
                                <span class="h-2 w-2 rounded-full bg-[#f97316]"></span>
                                Job Setup
                            </p>
                            <h1 class="mt-4 text-4xl font-light tracking-tight md:text-5xl">Tambah Lowongan</h1>
                            <p class="mt-4 max-w-2xl text-sm leading-7 text-white/75">Lengkapi informasi posisi, tambah departemen baru bila belum tersedia, dan atur periode pendaftaran.</p>
                        </div>

                        <div class="rounded-[28px] border border-white/15 bg-white/10 px-5 py-4 text-sm backdrop-blur">
                            <p class="font-normal text-[#f97316]">Status awal</p>
                            <p class="mt-1 font-normal text-white/75">Simpan sebagai draft atau langsung publish sesuai kebutuhan.</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.jobs.store') }}" class="p-5 md:p-8">
                    @include('admin.jobs.form')
                </form>
            </div>
        </div>
    </section>
</x-layouts.app>
