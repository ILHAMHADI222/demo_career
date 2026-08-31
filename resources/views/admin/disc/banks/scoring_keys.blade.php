<x-layouts.app :title="'Kunci Skoring DISC - ' . $bank->title">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-16 pt-6 sm:pt-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">

            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('admin.disc.banks.edit', $bank) }}"
                   class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    ← Kembali ke Detail Bank Soal
                </a>

                <div class="flex items-center gap-3">
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-mono font-semibold text-slate-700">
                        {{ $bank->name }} ({{ $bank->code }})
                    </span>
                </div>
            </div>

            <div class="overflow-hidden rounded-[40px] border border-white/80 bg-white p-8 shadow-sm">
                <div class="border-b border-slate-100 pb-6">
                    <span class="inline-flex items-center gap-2 rounded-full bg-orange-50 px-3.5 py-1 text-xs font-semibold text-[#f97316]">
                        Scoring Engine Key Matrix
                    </span>
                    <h1 class="mt-3 text-2xl font-light text-[#322d2a] md:text-3xl">
                        Editor <span class="font-normal text-[#f97316]">Kunci Jawaban Scoring DISC</span>
                    </h1>
                    <p class="mt-2 text-xs text-slate-500">
                        Kelola pemetaan dimensi DISC (D, I, S, C, atau * untuk netral) untuk pilihan PALING (Most) dan PALING TIDAK (Least).
                        Kunci ini tersimpan aman di server dan tidak pernah dikirim ke peserta.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.disc.banks.scoring-keys.update', $bank) }}" class="mt-6">
                    @csrf
                    @method('PUT')

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-700">
                            <thead class="border-b border-slate-200 bg-slate-50/70 text-xs font-semibold uppercase tracking-wider text-slate-600">
                                <tr>
                                    <th class="w-16 px-4 py-3 text-center">No</th>
                                    <th class="w-16 px-4 py-3 text-center">Opsi</th>
                                    <th class="px-6 py-3">Pernyataan Soal (Statement)</th>
                                    <th class="w-28 px-4 py-3 text-center">Kunci Most (M)</th>
                                    <th class="w-28 px-4 py-3 text-center">Kunci Least (L)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($bank->questions as $q)
                                    @foreach ($q->options as $opt)
                                        <tr class="transition hover:bg-slate-50/50 {{ $loop->first ? 'border-t-2 border-slate-200' : '' }}">
                                            @if ($loop->first)
                                                <td rowspan="4" class="border-r border-slate-100 bg-slate-50/40 px-4 py-3 text-center font-bold text-[#322d2a]">
                                                    {{ $q->question_number }}
                                                </td>
                                            @endif

                                            <td class="px-4 py-3 text-center">
                                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-800 mx-auto">
                                                    {{ $opt->option_label }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-3">
                                                <input
                                                    type="text"
                                                    name="options[{{ $opt->id }}][statement]"
                                                    value="{{ $opt->statement }}"
                                                    required
                                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs text-slate-800 focus:border-[#f97316] focus:bg-white focus:outline-none"
                                                >
                                            </td>

                                            <td class="px-4 py-3 text-center">
                                                <select
                                                    name="options[{{ $opt->id }}][most_mapping]"
                                                    class="rounded-xl border border-slate-200 bg-blue-50/50 px-3 py-1.5 text-xs font-bold font-mono text-blue-800 focus:border-[#f97316] focus:outline-none"
                                                >
                                                    @foreach (['D', 'I', 'S', 'C', '*'] as $factor)
                                                        <option value="{{ $factor }}" {{ $opt->most_mapping === $factor ? 'selected' : '' }}>{{ $factor }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="px-4 py-3 text-center">
                                                <select
                                                    name="options[{{ $opt->id }}][least_mapping]"
                                                    class="rounded-xl border border-slate-200 bg-rose-50/50 px-3 py-1.5 text-xs font-bold font-mono text-rose-800 focus:border-[#f97316] focus:outline-none"
                                                >
                                                    @foreach (['D', 'I', 'S', 'C', '*'] as $factor)
                                                        <option value="{{ $factor }}" {{ $opt->least_mapping === $factor ? 'selected' : '' }}>{{ $factor }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="sticky bottom-6 mt-8 flex items-center justify-between rounded-3xl border border-white/80 bg-[#322d2a] p-4 text-white shadow-2xl">
                        <div class="text-xs text-slate-300">
                            Total 24 Soal (96 Kunci Pemetaan). Seluruh perubahan akan tercatat di Audit Log.
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.disc.banks.edit', $bank) }}"
                               class="rounded-full px-5 py-2.5 text-xs font-semibold text-slate-300 hover:text-white">
                                Batal
                            </a>
                            <button type="submit"
                                    class="rounded-full bg-[#f97316] px-6 py-2.5 text-xs font-semibold text-white shadow-lg shadow-[#f97316]/25 transition hover:bg-[#ea580c]">
                                Simpan Kunci Scoring
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </section>
</x-layouts.app>
