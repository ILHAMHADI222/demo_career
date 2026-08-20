<x-layouts.app title="Kelola Bank Soal DISC - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-16 pt-28 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">

            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('admin.disc.banks.index') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    ← Kembali ke Bank Soal
                </a>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.disc.banks.scoring-keys', $bank) }}"
                       class="rounded-full border border-orange-200 bg-orange-50 px-5 py-2.5 text-xs font-semibold text-[#f97316] transition hover:bg-orange-100">
                        ⚡ Edit Matriks Kunci Scoring
                    </a>

                    @if ($bank->status !== 'PUBLISHED')
                        <form method="POST" action="{{ route('admin.disc.banks.publish', $bank) }}">
                            @csrf
                            <button type="submit"
                                    class="rounded-full bg-emerald-600 px-6 py-2.5 text-xs font-semibold text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700">
                                ✓ Publikasikan Bank Soal
                            </button>
                        </form>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-4 py-2 text-xs font-semibold text-emerald-800">
                            ● Status: PUBLISHED
                        </span>
                    @endif
                </div>
            </div>

            {{-- METADATA CARD --}}
            <div class="rounded-[40px] border border-white/80 bg-white p-8 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <span class="rounded-md bg-slate-100 px-2.5 py-1 font-mono text-xs font-bold text-slate-700">{{ $bank->code }}</span>
                        <span class="ml-2 text-xs text-slate-500">Versi {{ $bank->version }}</span>
                        <h1 class="mt-2 text-2xl font-light text-[#322d2a] md:text-3xl">
                            {{ $bank->name }}
                        </h1>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ $bank->description ?? 'Tidak ada catatan deskripsi.' }}
                        </p>
                    </div>

                    <div class="flex items-center gap-6 rounded-3xl bg-slate-50 p-5">
                        <div class="text-center">
                            <p class="text-xs uppercase text-slate-500">Total Butir Soal</p>
                            <p class="text-2xl font-light text-[#322d2a]">{{ $bank->questions->count() }} <span class="text-xs text-slate-400">/ 24</span></p>
                        </div>
                        <div class="h-8 w-px bg-slate-200"></div>
                        <div class="text-center">
                            <p class="text-xs uppercase text-slate-500">Status</p>
                            <p class="text-sm font-semibold {{ $bank->status === 'PUBLISHED' ? 'text-emerald-600' : 'text-amber-600' }}">{{ $bank->status }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 24 QUESTIONS PREVIEW & EDITOR ACCORDION --}}
            <div class="mt-8">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-light text-[#322d2a]">
                        Daftar Butir Pertanyaan ({{ $bank->questions->count() }} Soal)
                    </h2>
                    <p class="text-xs text-slate-500">
                        Setiap soal wajib memiliki 4 opsi dengan pemetaan Most dan Least (D, I, S, C, atau *).
                    </p>
                </div>

                <div class="space-y-4">
                    @forelse ($bank->questions as $q)
                        <div class="overflow-hidden rounded-3xl border border-white/80 bg-white p-6 shadow-sm">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#322d2a] text-xs font-semibold text-white">
                                        {{ $q->question_number }}
                                    </span>
                                    <span class="text-xs font-medium text-slate-700">Pertanyaan No. {{ $q->question_number }}</span>
                                </div>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-600">4 Opsi Lengkap</span>
                            </div>

                            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                @foreach ($q->options as $opt)
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                        <div class="flex items-center justify-between">
                                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-orange-100 text-xs font-bold text-[#f97316]">
                                                {{ $opt->option_label }}
                                            </span>
                                            <div class="flex gap-2 font-mono text-[11px] font-bold">
                                                <span class="rounded bg-blue-50 px-1.5 py-0.5 text-blue-700" title="Most Mapping">M: {{ $opt->most_mapping }}</span>
                                                <span class="rounded bg-rose-50 px-1.5 py-0.5 text-rose-700" title="Least Mapping">L: {{ $opt->least_mapping }}</span>
                                            </div>
                                        </div>
                                        <p class="mt-3 text-xs leading-relaxed text-slate-700">
                                            {{ $opt->statement }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="rounded-3xl border border-white/80 bg-white p-12 text-center text-slate-500 shadow-sm">
                            Belum ada pertanyaan pada bank soal ini.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </section>
</x-layouts.app>
