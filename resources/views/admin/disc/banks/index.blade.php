<x-layouts.app title="Bank Soal DISC - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-16 pt-28 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">

            {{-- HERO HEADER --}}
            <div class="relative overflow-hidden rounded-[48px] bg-[#322d2a] p-8 text-white shadow-xl shadow-slate-900/10 md:p-12">
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#f97316]/20 blur-3xl"></div>
                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/15 blur-3xl"></div>

                <div class="relative z-10 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-1.5 text-xs font-medium uppercase tracking-[0.2em] text-white backdrop-blur">
                            <span class="h-2 w-2 rounded-full bg-[#f97316]"></span>
                            DISC Management System
                        </span>
                        <h1 class="mt-4 text-3xl font-light tracking-tight md:text-4xl lg:text-5xl">
                            Kelola <span class="text-[#f97316] font-normal">Bank Soal & Scoring DISC</span>
                        </h1>
                        <p class="mt-3 max-w-2xl text-sm text-white/70 md:text-base">
                            Pusat pengelolaan 24 butir soal DISC, 4 pilihan opsi (Most/Least), pemetaan kunci D/I/S/C/*, dan versioning bank soal.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.disc.assessments.index') }}"
                           class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-5 py-3 text-sm font-medium text-white backdrop-blur transition hover:bg-white/20">
                            <span>📅 Daftar Asesmen</span>
                        </a>
                        <a href="{{ route('admin.disc.banks.create') }}"
                           class="inline-flex items-center gap-2 rounded-full bg-[#f97316] px-6 py-3 text-sm font-medium text-white shadow-lg shadow-[#f97316]/25 transition hover:-translate-y-0.5 hover:bg-[#ea580c]">
                            <span>+ Buat Bank Soal</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- STATS OVERVIEW --}}
            <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
                <div class="rounded-3xl border border-white/80 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wider text-[#6f6b67]">Total Bank Soal</p>
                    <p class="mt-2 text-3xl font-light text-[#322d2a]">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-3xl border border-white/80 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wider text-emerald-600">Published (Aktif)</p>
                    <p class="mt-2 text-3xl font-light text-emerald-700">{{ $stats['published'] }}</p>
                </div>
                <div class="rounded-3xl border border-white/80 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wider text-amber-600">Draft / Review</p>
                    <p class="mt-2 text-3xl font-light text-amber-700">{{ $stats['draft'] }}</p>
                </div>
                <div class="rounded-3xl border border-white/80 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wider text-[#6f6b67]">Archived</p>
                    <p class="mt-2 text-3xl font-light text-[#6f6b67]">{{ $stats['archived'] }}</p>
                </div>
            </div>

            {{-- FILTER & SEARCH --}}
            <div class="mt-8 rounded-[36px] border border-white/80 bg-white p-6 shadow-sm">
                <form method="GET" action="{{ route('admin.disc.banks.index') }}" class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('admin.disc.banks.index') }}"
                           class="rounded-full px-4 py-2 text-xs font-semibold transition {{ empty($status) ? 'bg-[#322d2a] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Semua Status
                        </a>
                        <a href="{{ route('admin.disc.banks.index', ['status' => 'PUBLISHED']) }}"
                           class="rounded-full px-4 py-2 text-xs font-semibold transition {{ $status === 'PUBLISHED' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Published
                        </a>
                        <a href="{{ route('admin.disc.banks.index', ['status' => 'DRAFT']) }}"
                           class="rounded-full px-4 py-2 text-xs font-semibold transition {{ $status === 'DRAFT' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Draft
                        </a>
                        <a href="{{ route('admin.disc.banks.index', ['status' => 'ARCHIVED']) }}"
                           class="rounded-full px-4 py-2 text-xs font-semibold transition {{ $status === 'ARCHIVED' ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Archived
                        </a>
                    </div>

                    <div class="flex items-center gap-3">
                        <input
                            type="text"
                            name="keyword"
                            value="{{ $keyword }}"
                            placeholder="Cari nama atau kode bank soal..."
                            class="w-full rounded-full border border-slate-200 bg-slate-50 px-5 py-2.5 text-xs text-slate-800 focus:border-[#f97316] focus:bg-white focus:outline-none md:w-72"
                        >
                        <button type="submit" class="rounded-full bg-[#322d2a] px-5 py-2.5 text-xs font-medium text-white transition hover:bg-black">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            {{-- BANK SOAL LIST TABLE --}}
            <div class="mt-6 overflow-hidden rounded-[36px] border border-white/80 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700">
                        <thead class="border-b border-slate-100 bg-slate-50/60 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-6 py-4">Bank Soal</th>
                                <th class="px-6 py-4">Kode & Versi</th>
                                <th class="px-6 py-4">Jumlah Soal</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Asesmen Terkait</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-normal">
                            @forelse ($banks as $bank)
                                <tr class="transition hover:bg-slate-50/50">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-[#322d2a]">{{ $bank->name }}</div>
                                        <div class="text-xs text-slate-500">{{ Str::limit($bank->description ?? 'Tidak ada deskripsi', 60) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="rounded-md bg-slate-100 px-2 py-1 font-mono text-xs font-bold text-slate-700">{{ $bank->code }}</span>
                                        <span class="ml-1 text-xs text-slate-500">v{{ $bank->version }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-slate-800">{{ $bank->questions_count }}</span>
                                        <span class="text-xs text-slate-500">/ 24 Butir</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($bank->status === 'PUBLISHED')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Published
                                            </span>
                                        @elseif ($bank->status === 'DRAFT')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Draft
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                                {{ $bank->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-600">
                                        {{ $bank->assessments_count }} Asesmen
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('admin.disc.banks.show', $bank) }}"
                                               class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-[#f97316] hover:text-[#f97316]"
                                               title="Preview Soal">
                                                Preview
                                            </a>
                                            <a href="{{ route('admin.disc.banks.scoring-keys', $bank) }}"
                                               class="rounded-xl border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-[#f97316] transition hover:bg-orange-100"
                                               title="Kunci Jawaban Scoring">
                                                Kunci Scoring
                                            </a>
                                            <a href="{{ route('admin.disc.banks.edit', $bank) }}"
                                               class="rounded-xl bg-[#322d2a] px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-black">
                                                Kelola
                                            </a>
                                            <form method="POST" action="{{ route('admin.disc.banks.duplicate', $bank) }}" onsubmit="return confirm('Duplikasi bank soal ini menjadi versi draft baru?')">
                                                @csrf
                                                <button type="submit" class="rounded-xl border border-slate-200 bg-slate-100 px-2.5 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-200" title="Duplikasi">
                                                    ⧉
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada data bank soal DISC yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($banks->hasPages())
                    <div class="border-t border-slate-100 p-4">
                        {{ $banks->links() }}
                    </div>
                @endif
            </div>

        </div>
    </section>
</x-layouts.app>
