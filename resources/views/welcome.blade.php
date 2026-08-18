<x-layouts.app title="Career Indodata">
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-[2rem] bg-gradient-to-br from-orange-500 via-amber-400 to-orange-500 p-8 text-white shadow-2xl shadow-orange-500/20 md:p-14">
            <div class="max-w-3xl">
                <p class="inline-flex rounded-full bg-white/15 px-4 py-2 text-sm font-black uppercase tracking-[0.22em] text-white/90">Career Indodata</p>
                <h1 class="mt-6 text-4xl font-black tracking-tight md:text-6xl">Portal karier dan ATS modern untuk PT INDODATA SOLUSI PERSADA.</h1>
                <p class="mt-6 text-lg leading-8 text-white/90">Kelola lowongan, kandidat, seleksi, interview, test, offering, talent pool, report, dan audit trail dalam satu sistem rekrutmen terpadu.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('jobs.index') }}" class="rounded-2xl bg-white px-6 py-3 font-black text-orange-600 shadow-lg transition hover:-translate-y-0.5">Lihat Lowongan</a>
                    <a href="{{ route('register') }}" class="rounded-2xl border border-white/30 px-6 py-3 font-black text-white transition hover:bg-white/10">Daftar Kandidat</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
