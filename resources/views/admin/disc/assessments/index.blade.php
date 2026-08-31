<x-layouts.app title="Kelola Asesmen Psikotes - Career Indodata">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">

            {{-- HERO HEADER --}}
            <div class="relative overflow-hidden rounded-[32px] sm:rounded-[36px] bg-[#322d2a] p-6 sm:p-7 md:p-8 text-white shadow-lg">
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#f97316]/20 blur-3xl"></div>
                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/15 blur-3xl"></div>

                <div class="relative z-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3.5 py-1 text-xs font-medium uppercase tracking-[0.16em] text-white backdrop-blur">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#f97316]"></span>
                            Psychometric Lifecycle
                        </span>
                        <h1 class="mt-2.5 text-2xl sm:text-3xl md:text-4xl font-light tracking-tight text-white">
                            Jadwal & Hasil <span class="text-[#f97316]">Asesmen</span>
                        </h1>
                        <p class="mt-1.5 max-w-2xl text-xs sm:text-sm font-normal text-white/75">
                            Kelola jadwal asesmen 3 sub-tes, undang kandidat, pantau pengerjaan secara real-time, dan evaluasi hasil tes.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <a href="{{ route('admin.disc.banks.index') }}"
                           class="inline-flex items-center gap-1.5 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold text-white backdrop-blur transition hover:bg-white/20">
                            <span>📚 Bank Soal</span>
                        </a>
                        <a href="{{ route('admin.disc.assessments.create') }}"
                           class="inline-flex items-center gap-1.5 rounded-full bg-[#f97316] px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#ea580c]">
                            <span>+ Buat Asesmen Baru</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- STATS OVERVIEW --}}
            <div class="mt-5 grid grid-cols-2 gap-3.5 sm:grid-cols-4">
                <div class="rounded-[22px] border border-white bg-white p-4 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-[#6f6b67]">Total Asesmen</p>
                    <p class="mt-1.5 text-2xl sm:text-3xl font-light text-[#322d2a]">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-[22px] border border-white bg-white p-4 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Asesmen Aktif</p>
                    <p class="mt-1.5 text-2xl sm:text-3xl font-light text-emerald-700">{{ $stats['published'] }}</p>
                </div>
                <div class="rounded-[22px] border border-white bg-white p-4 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Total Peserta</p>
                    <p class="mt-1.5 text-2xl sm:text-3xl font-light text-blue-700">{{ $stats['total_participants'] }}</p>
                </div>
                <div class="rounded-[22px] border border-white bg-white p-4 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-purple-600">Selesai (Completed)</p>
                    <p class="mt-1.5 text-2xl sm:text-3xl font-light text-purple-700">{{ $stats['completed_sessions'] }}</p>
                </div>
            </div>

            {{-- FILTER & SEARCH --}}
            <div class="mt-4 rounded-[24px] sm:rounded-[28px] border border-white bg-white p-4 sm:p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.disc.assessments.index') }}" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div class="flex flex-wrap items-center gap-1.5">
                        <a href="{{ route('admin.disc.assessments.index') }}"
                           class="rounded-full px-3.5 py-1.5 text-xs font-medium transition {{ empty($status) ? 'bg-[#322d2a] text-white' : 'bg-[#f0efe9] text-[#6f6b67] hover:bg-[#fff3e8] hover:text-[#f97316]' }}">
                            Semua Status
                        </a>
                        <a href="{{ route('admin.disc.assessments.index', ['status' => 'PUBLISHED']) }}"
                           class="rounded-full px-3.5 py-1.5 text-xs font-medium transition {{ $status === 'PUBLISHED' ? 'bg-emerald-600 text-white' : 'bg-[#f0efe9] text-[#6f6b67] hover:bg-emerald-50 hover:text-emerald-700' }}">
                            Aktif (Published)
                        </a>
                        <a href="{{ route('admin.disc.assessments.index', ['status' => 'DRAFT']) }}"
                           class="rounded-full px-3.5 py-1.5 text-xs font-medium transition {{ $status === 'DRAFT' ? 'bg-amber-600 text-white' : 'bg-[#f0efe9] text-[#6f6b67] hover:bg-amber-50 hover:text-amber-700' }}">
                            Draft
                        </a>
                        <a href="{{ route('admin.disc.assessments.index', ['status' => 'CLOSED']) }}"
                           class="rounded-full px-3.5 py-1.5 text-xs font-medium transition {{ $status === 'CLOSED' ? 'bg-slate-800 text-white' : 'bg-[#f0efe9] text-[#6f6b67] hover:bg-slate-200' }}">
                            Closed
                        </a>
                    </div>

                    <div class="flex items-center gap-2">
                        <input
                            type="text"
                            name="keyword"
                            value="{{ $keyword }}"
                            placeholder="Cari judul atau kode asesmen..."
                            class="w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2 text-xs font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white md:w-64"
                        >
                        <button type="submit" class="rounded-full bg-[#322d2a] px-4 py-2 text-xs font-medium text-white transition hover:bg-[#f97316]">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            {{-- ASSESSMENTS TABLE --}}
            <div class="mt-4 overflow-hidden rounded-[24px] sm:rounded-[28px] border border-white bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#322d2a]/10 text-xs">
                        <thead class="bg-[#322d2a] text-left text-[11px] font-normal uppercase tracking-[0.14em] text-white">
                            <tr>
                                <th class="px-4 py-3">Nama Asesmen</th>
                                <th class="px-4 py-3">Durasi Sub-Tes</th>
                                <th class="px-4 py-3">Bank Soal</th>
                                <th class="px-4 py-3">Peserta & Sesi</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#322d2a]/10 bg-white">
                            @forelse ($assessments as $assess)
                                <tr class="transition hover:bg-[#fff8f3]">
                                    <td class="px-4 py-3.5 align-top">
                                        <div class="font-medium text-[#322d2a]">{{ $assess->title }}</div>
                                        <div class="mt-0.5 text-[10px] text-[#6f6b67]">{{ $assess->creator->name ?? 'HRD' }} • {{ $assess->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-4 py-3.5 align-top">
                                        <span class="rounded-md bg-[#f0efe9] px-2 py-0.5 font-mono text-[11px] font-bold text-[#322d2a]">{{ $assess->code }}</span>
                                        <div class="mt-1 flex items-center gap-1.5 text-[10px] font-mono">
                                            <span class="text-blue-700 font-bold">Deret: {{ $assess->duration_stage_1 ?: 10 }}m</span>
                                            <span class="text-[#6f6b67]">•</span>
                                            <span class="text-emerald-700 font-bold">Analogi: {{ $assess->duration_stage_2 ?: 10 }}m</span>
                                            <span class="text-[#6f6b67]">•</span>
                                            <span class="text-purple-700 font-bold">Kepribadian: Untimed</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 align-top">
                                        <div class="font-medium text-[#322d2a]">{{ $assess->questionBank->name ?? '-' }}</div>
                                        <div class="text-[10px] text-[#6f6b67]">Versi {{ $assess->question_bank_version }}</div>
                                    </td>
                                    <td class="px-4 py-3.5 align-top">
                                        <span class="font-semibold text-[#322d2a]">{{ $assess->participants_count }}</span> Peserta
                                        <div class="text-[10px] text-[#6f6b67]">{{ $assess->sessions_count }} Sesi</div>
                                    </td>
                                    <td class="px-4 py-3.5 align-top">
                                        @if ($assess->status === 'PUBLISHED')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-medium text-emerald-700 ring-1 ring-emerald-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                                            </span>
                                        @elseif ($assess->status === 'DRAFT')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-0.5 text-[10px] font-medium text-amber-700 ring-1 ring-amber-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Draft
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-medium text-slate-600">
                                                Closed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 align-top text-right">
                                        <div class="inline-flex flex-wrap items-center justify-end gap-1.5">
                                            @if ($assess->status === 'PUBLISHED')
                                                <form action="{{ route('admin.disc.assessments.close', $assess) }}" method="POST" class="inline" onsubmit="return confirm('Tutup asesmen ini?')">
                                                    @csrf
                                                    <button type="submit" class="rounded-full bg-rose-50 px-2.5 py-1 text-[11px] font-medium text-rose-700 ring-1 ring-rose-200 hover:bg-rose-100">
                                                        ✕ Tutup
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.disc.assessments.publish', $assess) }}" method="POST" class="inline" onsubmit="return confirm('Buka asesmen sekarang?')">
                                                    @csrf
                                                    <button type="submit" class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-medium text-emerald-700 ring-1 ring-emerald-200 hover:bg-emerald-100">
                                                        ✓ Buka
                                                    </button>
                                                </form>
                                            @endif

                                            <a href="{{ route('admin.disc.assessments.monitor', $assess) }}"
                                               class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-medium text-blue-700 ring-1 ring-blue-200 hover:bg-blue-100">
                                                🛰 Monitor
                                            </a>
                                            <a href="{{ route('admin.disc.assessments.show', $assess) }}"
                                               class="rounded-full bg-[#322d2a] px-3 py-1 text-[11px] font-medium text-white hover:bg-[#f97316]">
                                                Detail →
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-xs text-[#6f6b67]">
                                        Belum ada jadwal asesmen psikotes. Silakan buat asesmen baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($assessments->hasPages())
                    <div class="border-t border-[#322d2a]/10 bg-[#f0efe9] p-3 text-xs">
                        {{ $assessments->links() }}
                    </div>
                @endif
            </div>

        </div>
    </section>
</x-layouts.app>
