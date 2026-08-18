<x-layouts.app title="Tambah Lowongan - Career Indodata">
    <section class="min-h-screen bg-slate-50 py-10 md:py-14">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] bg-slate-950 p-8 text-white shadow-2xl md:p-10">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-300">HRD Job Management</p>
                <h1 class="mt-4 text-4xl font-black tracking-tight md:text-5xl">Tambah lowongan pekerjaan.</h1>
                <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-200">HRD dapat membuat lowongan lengkap dengan deskripsi, persyaratan, deadline, dan status publikasi.</p>
            </div>

            <form method="POST" action="{{ route('admin.jobs.store') }}" class="mt-8 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                @include('admin.jobs.form')
            </form>
        </div>
    </section>
</x-layouts.app>
