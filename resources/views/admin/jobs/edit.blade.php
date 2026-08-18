<x-layouts.app title="Edit Lowongan - Career Indodata">
    <section class="min-h-screen bg-slate-50 py-10 md:py-14">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] bg-slate-950 p-8 text-white shadow-2xl md:p-10">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-300">HRD Job Management</p>
                <h1 class="mt-4 text-4xl font-black tracking-tight md:text-5xl">Edit lowongan pekerjaan.</h1>
                <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-200">Perbarui informasi lowongan, status publikasi, deadline, dan detail kebutuhan kandidat.</p>
            </div>

            <form method="POST" action="{{ route('admin.jobs.update', $job) }}" class="mt-8 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                @method('PUT')
                @include('admin.jobs.form')
            </form>
        </div>
    </section>
</x-layouts.app>
