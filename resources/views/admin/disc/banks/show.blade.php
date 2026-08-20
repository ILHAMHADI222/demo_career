<x-layouts.app title="Preview Bank Soal DISC - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-16 pt-28 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">

            <div class="mb-6 flex items-center justify-between">
                <a href="{{ route('admin.disc.banks.index') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    ← Kembali ke Daftar Bank Soal
                </a>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.disc.banks.edit', $bank) }}"
                       class="rounded-full bg-[#322d2a] px-5 py-2 text-xs font-semibold text-white transition hover:bg-black">
                        Kelola Bank Soal
                    </a>
                </div>
            </div>

            <div class="rounded-[40px] border border-white/80 bg-white p-8 shadow-sm">
                <span class="rounded-md bg-slate-100 px-2.5 py-1 font-mono text-xs font-bold text-slate-700">{{ $bank->code }}</span>
                <span class="ml-2 text-xs text-slate-500">Versi {{ $bank->version }} ({{ $bank->status }})</span>
                <h1 class="mt-2 text-2xl font-light text-[#322d2a] md:text-3xl">{{ $bank->name }}</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $bank->description }}</p>
            </div>

            <div class="mt-8 space-y-4">
                @foreach ($bank->questions as $q)
                    <div class="rounded-3xl border border-white/80 bg-white p-6 shadow-sm">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#322d2a] text-xs font-bold text-white">
                                {{ $q->question_number }}
                            </span>
                            <span class="text-xs font-medium text-slate-700">{{ $q->instruction }}</span>
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                            @foreach ($q->options as $opt)
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <div class="flex items-center justify-between">
                                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-orange-100 text-xs font-bold text-[#f97316]">
                                            {{ $opt->option_label }}
                                        </span>
                                        <div class="flex gap-1.5 font-mono text-[10px] font-bold">
                                            <span class="rounded bg-blue-50 px-1 py-0.5 text-blue-700">M: {{ $opt->most_mapping }}</span>
                                            <span class="rounded bg-rose-50 px-1 py-0.5 text-rose-700">L: {{ $opt->least_mapping }}</span>
                                        </div>
                                    </div>
                                    <p class="mt-2.5 text-xs text-slate-700 leading-relaxed">{{ $opt->statement }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>
</x-layouts.app>
