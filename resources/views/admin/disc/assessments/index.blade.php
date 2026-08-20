<x-layouts.app title="Daftar Asesmen DISC - Career Indodata">
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
                            Assessment Lifecycle
                        </span>
                        <h1 class="mt-4 text-3xl font-light tracking-tight md:text-4xl lg:text-5xl">
                            Kelola <span class="text-[#f97316] font-normal">Jadwal & Hasil Asesmen DISC</span>
                        </h1>
                        <p class="mt-3 max-w-2xl text-sm text-white/70 md:text-base">
                            Buat jadwal asesmen, undang kandidat pelamar, pantau aktivitas pengerjaan real-time, dan telusuri profil kepribadian DISC lengkap.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.disc.banks.index') }}"
                           class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-5 py-3 text-sm font-medium text-white backdrop-blur transition hover:bg-white/20">
                            <span>📚 Bank Soal DISC</span>
                        </a>
                        <a href="{{ route('admin.disc.assessments.create') }}"
                           class="inline-flex items-center gap-2 rounded-full bg-[#f97316] px-6 py-3 text-sm font-medium text-white shadow-lg shadow-[#f97316]/25 transition hover:-translate-y-0.5 hover:bg-[#ea580c]">
                            <span>+ Buat Asesmen Baru</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- STATS OVERVIEW --}}
            <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
                <div class="rounded-3xl border border-white/80 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wider text-[#6f6b67]">Total Asesmen</p>
                    <p class="mt-2 text-3xl font-light text-[#322d2a]">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-3xl border border-white/80 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wider text-emerald-600">Asesmen Aktif</p>
                    <p class="mt-2 text-3xl font-light text-emerald-700">{{ $stats['published'] }}</p>
                </div>
                <div class="rounded-3xl border border-white/80 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wider text-blue-600">Total Peserta</p>
                    <p class="mt-2 text-3xl font-light text-blue-700">{{ $stats['total_participants'] }}</p>
                </div>
                <div class="rounded-3xl border border-white/80 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wider text-purple-600">Sesi Selesai (Completed)</p>
                    <p class="mt-2 text-3xl font-light text-purple-700">{{ $stats['completed_sessions'] }}</p>
                </div>
            </div>

            {{-- FILTER & SEARCH --}}
            <div class="mt-8 rounded-[36px] border border-white/80 bg-white p-6 shadow-sm">
                <form method="GET" action="{{ route('admin.disc.assessments.index') }}" class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('admin.disc.assessments.index') }}"
                           class="rounded-full px-4 py-2 text-xs font-semibold transition {{ empty($status) ? 'bg-[#322d2a] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Semua Status
                        </a>
                        <a href="{{ route('admin.disc.assessments.index', ['status' => 'PUBLISHED']) }}"
                           class="rounded-full px-4 py-2 text-xs font-semibold transition {{ $status === 'PUBLISHED' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Aktif (Published)
                        </a>
                        <a href="{{ route('admin.disc.assessments.index', ['status' => 'DRAFT']) }}"
                           class="rounded-full px-4 py-2 text-xs font-semibold transition {{ $status === 'DRAFT' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Draft
                        </a>
                        <a href="{{ route('admin.disc.assessments.index', ['status' => 'CLOSED']) }}"
                           class="rounded-full px-4 py-2 text-xs font-semibold transition {{ $status === 'CLOSED' ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Closed
                        </a>
                    </div>

                    <div class="flex items-center gap-3">
                        <input
                            type="text"
                            name="keyword"
                            value="{{ $keyword }}"
                            placeholder="Cari judul atau kode asesmen..."
                            class="w-full rounded-full border border-slate-200 bg-slate-50 px-5 py-2.5 text-xs text-slate-800 focus:border-[#f97316] focus:bg-white focus:outline-none md:w-72"
                        >
                        <button type="submit" class="rounded-full bg-[#322d2a] px-5 py-2.5 text-xs font-medium text-white transition hover:bg-black">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            {{-- ASSESSMENTS TABLE --}}
            <div class="mt-6 overflow-hidden rounded-[36px] border border-white/80 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700">
                        <thead class="border-b border-slate-100 bg-slate-50/60 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-6 py-4">Nama Asesmen</th>
                                <th class="px-6 py-4">Kode & Durasi</th>
                                <th class="px-6 py-4">Bank Soal</th>
                                <th class="px-6 py-4">Peserta & Sesi</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-normal">
                            @forelse ($assessments as $assess)
                                <tr class="transition hover:bg-slate-50/50">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-[#322d2a]">{{ $assess->title }}</div>
                                        <div class="text-xs text-slate-500">Dibuat oleh {{ $assess->creator->name ?? 'HRD' }} • {{ $assess->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="rounded-md bg-slate-100 px-2 py-1 font-mono text-xs font-bold text-slate-700">{{ $assess->code }}</span>
                                        <div class="mt-1 text-xs text-slate-500">⏱ {{ $assess->duration_minutes }} Menit</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <div class="font-medium text-slate-800">{{ $assess->questionBank->name ?? '-' }}</div>
                                        <div class="text-slate-500">Versi {{ $assess->question_bank_version }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <span class="font-bold text-slate-800">{{ $assess->participants_count }}</span> Peserta
                                        <div class="text-slate-500">{{ $assess->sessions_count }} Sesi Dibuat</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($assess->status === 'PUBLISHED')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                                            </span>
                                        @elseif ($assess->status === 'DRAFT')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Draft
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                                Closed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('admin.disc.assessments.monitor', $assess) }}"
                                               class="rounded-xl border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 transition hover:bg-blue-100"
                                               title="Live Security Monitoring">
                                                🛰 Monitor
                                            </a>
                                            <a href="{{ route('admin.disc.assessments.show', $assess) }}"
                                               class="rounded-xl bg-[#322d2a] px-3.5 py-1.5 text-xs font-semibold text-white transition hover:bg-black">
                                                Detail & Peserta
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada jadwal asesmen DISC. Silakan buat asesmen baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($assessments->hasPages())
                    <div class="border-t border-slate-100 p-4">
                        {{ $assessments->links() }}
                    </div>
                @endif
            </div>

        </div>
    </section>
</x-layouts.app>
