<x-layouts.app title="Dashboard Kandidat - Career Indodata">

    <section class="bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">

            {{-- HERO --}}
            <div class="rounded-[40px] border border-white bg-white p-6 sm:p-7 lg:p-8">
                <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                    <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                    Candidate Dashboard
                </div>

                <h1 class="mt-4 max-w-3xl text-[36px] font-light leading-none tracking-[0.4px] text-[#322d2a] md:text-[46px]">
                    Halo,
                    <span class="text-[#f97316]">
                        {{ $profile?->full_name ?? auth()->user()->name }}
                    </span>
                </h1>

                <p class="mt-3 max-w-2xl text-sm font-normal leading-[1.6] tracking-[0.14px] text-[#6f6b67]">
                    Kelola profil kandidat, pantau progres lamaran, jadwal interview,
                    serta informasi offering dalam satu dashboard terpusat.
                </p>

                <div class="mt-5 flex flex-wrap gap-3">
                    <a
                        href="{{ route('candidate.profile') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-full bg-[#f97316] px-5 py-3 text-xs font-normal text-white transition hover:bg-[#ea580c]"
                    >
                        Lengkapi Profil
                        <span>→</span>
                    </a>

                    <a
                        href="{{ route('jobs.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-full border border-[#322d2a]/30 bg-transparent px-5 py-3 text-xs font-normal text-[#322d2a] transition hover:border-[#f97316] hover:bg-[#fff3e8] hover:text-[#f97316]"
                    >
                        Cari Lowongan
                    </a>
                </div>
            </div>

            @php
                $activeDisc = $discAssessments->first(function($p) {
                    $completedCount = \App\Models\DiscAssessmentSession::where('disc_assessment_id', $p->disc_assessment_id)
                        ->where('participant_id', $p->user_id)
                        ->where('status', 'COMPLETED')
                        ->count();
                    $max = (int) ($p->max_attempts ?: 1);
                    return $completedCount < $max;
                });
            @endphp

            @if ($technicalTestApp || $activeDisc)
                @php
                    $targetDisc = $activeDisc?->assessment ?? \App\Models\DiscAssessment::query()->where('status', 'PUBLISHED')->latest()->first();
                @endphp
                <div class="mt-6 overflow-hidden rounded-[40px] border-2 border-[#f97316] bg-gradient-to-br from-[#fff3e8] via-white to-orange-50/50 p-6 md:p-8 shadow-xl shadow-orange-500/10">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div class="space-y-2">
                            <div class="inline-flex items-center gap-2 rounded-full bg-[#f97316] px-3.5 py-1 text-xs font-semibold uppercase tracking-wider text-white shadow-sm">
                                <span class="h-2 w-2 rounded-full bg-white animate-ping"></span>
                                Tahap Psikotes Terbuka
                            </div>
                            <h2 class="text-2xl md:text-3xl font-light text-[#322d2a]">
                                Tes Psikotes Sudah Siap Dikerjakan
                            </h2>
                            <p class="max-w-2xl text-sm leading-relaxed text-[#6f6b67]">
                                Status lamaran Anda telah memasuki tahap <strong>Tes Psikotes</strong>{{ $technicalTestApp ? ' untuk posisi ' . $technicalTestApp->job->title : '' }}. 
                                Tes terdiri dari <strong>3 Sub-Tes Berurutan</strong>: Sub-Tes 1 (Deret Hitung {{ $targetDisc?->duration_stage_1 ?: 10 }} Menit), Sub-Tes 2 (Analogi Verbal {{ $targetDisc?->duration_stage_2 ?: 10 }} Menit), dan Sub-Tes 3 (Tes Kepribadian tanpa batas waktu).
                            </p>
                        </div>
                        <div class="shrink-0 flex flex-col sm:flex-row gap-3 items-center">
                            @if ($targetDisc)
                                <a href="{{ route('disc.assessment.instruction', $targetDisc) }}"
                                   class="inline-flex items-center justify-center gap-2 rounded-full bg-[#f97316] px-7 py-4 text-sm font-semibold tracking-wide text-white shadow-lg shadow-orange-500/30 transition hover:bg-[#ea580c] hover:scale-105 active:scale-95">
                                    <span>Mulai Tes Psikotes Sekarang</span>
                                    <span>→</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- MAIN GRID --}}
            <div class="mt-8 grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">

                {{-- LEFT COLUMN --}}
                <div class="space-y-6">

                    {{-- RELEVANT CANDIDATE PROFILE CARD --}}
                    <div class="rounded-[40px] border border-white bg-white p-6 md:p-8">
                        <div class="flex items-start justify-between gap-4 border-b border-[#322d2a]/10 pb-5">
                            <div>
                                <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                    <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                                    Candidate Profile
                                </p>
                                <h2 class="mt-2 text-[28px] font-light leading-none tracking-[0.28px] text-[#322d2a]">
                                    Profil Kandidat
                                </h2>
                            </div>

                            <a
                                href="{{ route('candidate.profile') }}"
                                class="inline-flex items-center gap-1.5 rounded-full bg-[#fff3e8] px-4 py-2 text-xs font-normal text-[#f97316] ring-1 ring-[#f97316]/20 transition hover:bg-[#f97316] hover:text-white"
                            >
                                <span>✏️ Edit Profil</span>
                            </a>
                        </div>

                        {{-- Candidate Snapshot --}}
                        <div class="mt-6 flex flex-col items-center gap-4 sm:flex-row sm:items-center rounded-[28px] bg-[#f0efe9] p-5">
                            <div class="h-20 w-16 shrink-0 overflow-hidden rounded-[18px] border border-[#322d2a]/10 bg-white shadow-sm">
                                @if($profile?->photo)
                                    <img src="{{ asset('storage/'.$profile->photo) }}" alt="Foto {{ $profile->full_name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-[#fff3e8] text-lg font-bold text-[#f97316]">
                                        {{ strtoupper(substr($profile?->full_name ?? auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 text-center sm:text-left">
                                <h3 class="text-lg font-medium text-[#322d2a]">{{ $profile?->full_name ?? auth()->user()->name }}</h3>
                                <p class="mt-0.5 text-xs text-[#6f6b67] tracking-wider">{{ $profile?->candidate_number ?? 'Candidate' }}</p>
                                <div class="mt-2 flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                    <span class="inline-flex items-center gap-1 rounded-full {{ ($profile?->profile_completion ?? 0) >= 100 ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' }} px-3 py-0.5 text-[11px] font-medium">
                                        {{ ($profile?->profile_completion ?? 0) >= 100 ? '✓ Profil 100% Lengkap' : 'Profil ' . ($profile?->profile_completion ?? 0) . '% Terisi' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Relevant Details List --}}
                        @php
                            $education = $profile?->educations?->first();
                        @endphp
                        <div class="mt-5 space-y-3 text-sm">
                            <div class="rounded-[20px] border border-[#322d2a]/10 bg-[#f0efe9] p-4">
                                <p class="text-[11px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Kontak Utama</p>
                                <p class="mt-1 font-medium text-[#322d2a] break-all">{{ $profile?->email ?? auth()->user()->email }}</p>
                                <p class="mt-0.5 text-xs text-[#6f6b67]">WA / Telepon: <span class="text-[#322d2a] font-medium">{{ $profile?->whatsapp ?? $profile?->phone ?? '-' }}</span></p>
                            </div>

                            <div class="rounded-[20px] border border-[#322d2a]/10 bg-[#f0efe9] p-4">
                                <p class="text-[11px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Pendidikan Terakhir</p>
                                @if($education)
                                    <p class="mt-1 font-medium text-[#322d2a]">{{ $education->education_level }} • {{ $education->major }}</p>
                                    <p class="mt-0.5 text-xs text-[#6f6b67]">{{ $education->institution }} (IPK: {{ $education->ipk ?: '-' }})</p>
                                @else
                                    <p class="mt-1 text-xs text-[#6f6b67]">Belum mengisi data pendidikan</p>
                                @endif
                            </div>

                            <div class="rounded-[20px] border border-[#322d2a]/10 bg-[#f0efe9] p-4">
                                <p class="text-[11px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Domisili</p>
                                <p class="mt-1 font-medium text-[#322d2a]">{{ $profile?->city ?? '-' }}, {{ $profile?->province ?? '-' }}</p>
                                <p class="mt-0.5 text-xs text-[#6f6b67] leading-relaxed line-clamp-2">{{ $profile?->address ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a
                                href="{{ route('candidate.profile') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-full bg-[#f97316] px-5 py-3 text-xs font-normal text-white shadow-sm transition hover:bg-[#ea580c]"
                            >
                                Kelola Data Profil
                                <span>→</span>
                            </a>

                            <a
                                href="{{ route('jobs.index') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-full border border-[#322d2a]/20 bg-transparent px-5 py-3 text-xs font-normal text-[#322d2a] transition hover:bg-[#f0efe9]"
                            >
                                Jelajahi Lowongan
                            </a>
                        </div>
                    </div>

                    {{-- INTERVIEW SCHEDULE (HANYA MUNCUL JIKA ADA JADWAL DARI HRD) --}}
                    @if($upcomingInterviews->isNotEmpty())
                        <div class="rounded-[40px] border border-white bg-white p-6 md:p-8">
                            <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                                Interview Schedule
                            </p>

                            <h2 class="mt-4 text-[32px] font-light leading-none tracking-[0.32px] text-[#322d2a]">
                                Interview Mendatang
                            </h2>

                            <p class="mt-4 text-sm font-normal leading-[1.6] tracking-[0.14px] text-[#6f6b67]">
                                Jadwal wawancara resmi yang telah diagendakan oleh tim HRD Rekrutmen.
                            </p>

                            <div class="mt-6 space-y-4">
                                @foreach($upcomingInterviews as $interview)
                                    <div class="rounded-[28px] border border-[#f97316]/25 bg-[#fff8f3] p-5 sm:p-6 shadow-sm">
                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 border-b border-[#322d2a]/10 pb-4">
                                            <div>
                                                <div class="inline-flex items-center gap-2 rounded-full bg-[#f97316] px-3 py-1 text-[11px] font-medium uppercase tracking-wider text-white">
                                                    <span>📅</span> {{ $interview->type ?? 'Interview' }}
                                                </div>
                                                <h3 class="mt-2 text-lg font-medium text-[#322d2a]">
                                                    {{ $interview->application?->job?->title ?? 'Posisi Rekrutmen' }}
                                                </h3>
                                                <p class="text-xs text-[#6f6b67]">
                                                    No. Lamaran: {{ $interview->application?->application_number ?? '-' }}
                                                </p>
                                            </div>
                                            <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-[#f97316] ring-1 ring-[#f97316]/20">
                                                <span class="h-1.5 w-1.5 rounded-full bg-[#f97316]"></span>
                                                {{ $interview->status_label }}
                                            </span>
                                        </div>

                                        <div class="mt-4 grid gap-3 sm:grid-cols-2 rounded-[20px] bg-white p-4 text-xs border border-[#322d2a]/10">
                                            <div>
                                                <p class="text-[10px] uppercase tracking-wider text-[#6f6b67]">Waktu Pelaksanaan</p>
                                                <p class="mt-1 font-semibold text-[#322d2a]">
                                                    {{ $interview->scheduled_date?->translatedFormat('l, d F Y') ?? $interview->scheduled_date?->format('d M Y') }}
                                                </p>
                                                <p class="mt-0.5 text-xs text-[#f97316] font-medium">
                                                    Pukul {{ substr($interview->start_time, 0, 5) }} {{ $interview->end_time ? '- ' . substr($interview->end_time, 0, 5) : '' }} WIB
                                                </p>
                                            </div>

                                            <div>
                                                <p class="text-[10px] uppercase tracking-wider text-[#6f6b67]">Metode & Pewawancara</p>
                                                <p class="mt-1 font-semibold text-[#322d2a]">
                                                    {{ str_contains(strtolower($interview->method), 'online') ? '🌐 Online (Daring)' : '🏢 Tatap Muka (Offline)' }}
                                                </p>
                                                <p class="mt-0.5 text-xs text-[#6f6b67]">
                                                    Interviewer: {{ $interview->interviewer?->name ?? 'Tim HRD' }}
                                                </p>
                                            </div>
                                        </div>

                                        @if($interview->meeting_url)
                                            <div class="mt-3 flex items-center justify-between rounded-[18px] bg-white p-3.5 border border-[#322d2a]/10">
                                                <div class="min-w-0 pr-3">
                                                    <p class="text-[10px] uppercase tracking-wider text-[#6f6b67]">Tautan Pertemuan Online</p>
                                                    <p class="truncate text-xs font-mono text-[#f97316]">{{ $interview->meeting_url }}</p>
                                                </div>
                                                <a href="{{ $interview->meeting_url }}" target="_blank" rel="noopener noreferrer" class="shrink-0 inline-flex items-center gap-1.5 rounded-full bg-[#f97316] px-4 py-2 text-xs font-medium text-white shadow-sm transition hover:bg-[#ea580c]">
                                                    Buka Link ↗
                                                </a>
                                            </div>
                                        @elseif($interview->location)
                                            <div class="mt-3 rounded-[18px] bg-white p-3.5 text-xs border border-[#322d2a]/10">
                                                <p class="text-[10px] uppercase tracking-wider text-[#6f6b67]">Lokasi / Ruangan</p>
                                                <p class="mt-0.5 font-medium text-[#322d2a]">{{ $interview->location }}</p>
                                            </div>
                                        @endif

                                        @if($interview->instruction)
                                            <div class="mt-3 rounded-[18px] bg-[#fff3e8] p-3.5 text-xs border border-[#f97316]/20">
                                                <p class="font-semibold text-[#322d2a]">Catatan & Instruksi:</p>
                                                <p class="mt-1 leading-relaxed text-[#6f6b67]">{{ $interview->instruction }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>


                {{-- RIGHT COLUMN --}}
                <div class="space-y-6">

                    {{-- ASESMEN PSIKOTES (DI SEBELAH KANAN, TANPA KATA DISC) --}}
                    @if($discAssessments->isNotEmpty())
                        <div class="rounded-[40px] border border-white bg-white p-6 md:p-8">
                            <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                                Psychometric Test
                            </p>

                            <h2 class="mt-4 text-[32px] font-light leading-none tracking-[0.32px] text-[#322d2a]">
                                Asesmen Psikotes
                            </h2>

                            <p class="mt-4 text-sm font-normal leading-[1.6] tracking-[0.14px] text-[#6f6b67]">
                                Uji kepribadian dan potensi kerja untuk melengkapi profil seleksi Anda.
                            </p>

                            <div class="mt-6 space-y-4">
                                @foreach($discAssessments as $discPart)
                                    @php
                                        $assess = $discPart->assessment;
                                        $session = $discPart->latestSession;
                                        $completedCount = \App\Models\DiscAssessmentSession::where('disc_assessment_id', $assess->id)
                                            ->where('participant_id', $discPart->user_id)
                                            ->where('status', 'COMPLETED')
                                            ->count();
                                        $maxAttempts = (int) ($discPart->max_attempts ?: 1);
                                        $canTake = $completedCount < $maxAttempts;
                                    @endphp
                                    <div class="rounded-[28px] border {{ !$canTake ? 'border-emerald-200 bg-emerald-50/50' : 'border-[#f97316]/30 bg-[#fff3e8]' }} p-5">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="rounded-md bg-white px-2 py-0.5 font-mono text-[11px] font-bold text-slate-700">{{ $assess->code }}</span>
                                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">
                                                        Kesempatan: {{ $completedCount }}/{{ $maxAttempts }} Selesai
                                                    </span>
                                                </div>
                                                <p class="mt-2 font-normal tracking-[0.16px] text-[#322d2a]">
                                                    {{ $assess->title }}
                                                </p>
                                                <p class="mt-1 text-xs text-[#6f6b67]">
                                                    Durasi: {{ $assess->duration_minutes }} Menit • Status: 
                                                    <strong class="{{ !$canTake ? 'text-emerald-700' : 'text-[#f97316]' }}">
                                                        {{ !$canTake ? 'SELESAI (COMPLETED)' : ($session && $session->isInProgress() ? 'SEDANG DIKERJAKAN' : 'SIAP DIKERJAKAN') }}
                                                    </strong>
                                                </p>

                                                <div class="mt-4 flex flex-wrap items-center gap-2">
                                                    @if ($canTake)
                                                        <a href="{{ route('disc.assessment.instruction', $assess) }}"
                                                           class="inline-flex rounded-full bg-[#f97316] px-5 py-2.5 text-xs font-semibold text-white shadow-md shadow-orange-500/20 transition hover:bg-[#ea580c]">
                                                            {{ $completedCount > 0 ? "Mulai Percobaan ke-" . ($completedCount + 1) . " →" : ($session && $session->isInProgress() ? 'Lanjutkan Tes Psikotes →' : 'Mulai Kerjakan Tes Psikotes →') }}
                                                        </a>
                                                    @else
                                                        @if ($session)
                                                            <a href="{{ route('disc.assessment.completed', $session->session_token) }}"
                                                               class="inline-flex rounded-full bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                                                Lihat Konfirmasi Selesai ✓
                                                            </a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[16px] {{ !$canTake ? 'bg-emerald-100 text-emerald-600' : 'bg-[#fff3e8] text-[#f97316]' }} text-xs font-bold">
                                                TEST
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- APPLICATIONS TRACKING --}}
                    <div class="rounded-[40px] border border-white bg-white p-6 md:p-8">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between border-b border-[#322d2a]/10 pb-5">
                            <div>
                                <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                    <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                                    Application Tracking
                                </p>
                                <h2 class="mt-2 text-[28px] font-light leading-none tracking-[0.28px] text-[#322d2a]">
                                    Lamaran Terbaru
                                </h2>
                            </div>
                            <a
                                href="{{ route('jobs.index') }}"
                                class="text-sm font-normal tracking-[0.14px] text-[#f97316] transition hover:text-[#ea580c]"
                            >
                                Cari Lowongan →
                            </a>
                        </div>

                        <div class="mt-6 space-y-4">
                            @forelse($applications as $application)
                                <div class="group rounded-[28px] border border-[#322d2a]/10 bg-[#f0efe9] p-5 transition hover:border-[#f97316]/40 hover:bg-[#fff3e8]">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                        <div>
                                            <p class="text-lg font-normal tracking-[0.18px] text-[#322d2a] transition group-hover:text-[#f97316]">
                                                {{ $application->job?->title ?? 'Posisi tidak tersedia' }}
                                            </p>
                                            <p class="mt-1 text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">
                                                {{ $application->application_number }}
                                            </p>
                                        </div>
                                        @php
                                            $candNormStatus = strtoupper(str_replace([' ', '-'], '_', $application->status ?? ''));
                                        @endphp
                                        @if(in_array($candNormStatus, ['HIRED', 'ACCEPTED']))
                                            <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-emerald-50 px-3.5 py-1.5 text-xs font-medium text-emerald-700 ring-1 ring-emerald-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                {{ $application->status_label }}
                                            </span>
                                        @elseif(in_array($candNormStatus, ['REJECTED', 'DECLINED', 'WITHDRAWN', 'CANCELLED']))
                                            <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-red-50 px-3.5 py-1.5 text-xs font-medium text-red-600 ring-1 ring-red-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                {{ $application->status_label }}
                                            </span>
                                        @elseif(in_array($candNormStatus, ['OFFERING', 'OFFER']))
                                            <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-blue-50 px-3.5 py-1.5 text-xs font-medium text-blue-700 ring-1 ring-blue-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                                {{ $application->status_label }}
                                            </span>
                                        @elseif(in_array($candNormStatus, ['HR_INTERVIEW', 'HRINTERVIEW', 'USER_INTERVIEW', 'USERINTERVIEW', 'FINAL_INTERVIEW', 'FINALINTERVIEW', 'TECHNICAL_TEST', 'TECHNICALTEST', 'MEDICAL_CHECK_UP', 'MEDICALCHECKUP']))
                                            <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-violet-50 px-3.5 py-1.5 text-xs font-medium text-violet-700 ring-1 ring-violet-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-violet-500"></span>
                                                {{ $application->status_label }}
                                            </span>
                                        @else
                                            <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-[#fff3e8] px-3.5 py-1.5 text-xs font-medium text-[#f97316] ring-1 ring-[#f97316]/20">
                                                <span class="h-1.5 w-1.5 rounded-full bg-[#f97316]"></span>
                                                {{ $application->status_label }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mt-5 flex items-center justify-between gap-4 border-t border-[#322d2a]/10 pt-4">
                                        <span class="text-xs font-normal leading-[1.5] text-[#6f6b67]">
                                            Status lamaran diperbarui oleh tim HRD
                                        </span>
                                        @if($application->job)
                                            <a
                                                href="{{ route('jobs.show', $application->job) }}"
                                                class="shrink-0 text-sm font-normal tracking-[0.14px] text-[#f97316] transition hover:text-[#ea580c]"
                                            >
                                                Detail →
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-[28px] border border-dashed border-[#322d2a]/25 bg-[#f0efe9] p-8 text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-[20px] bg-[#fff3e8] text-xl text-[#f97316]">
                                        📄
                                    </div>
                                    <p class="mt-4 font-normal tracking-[0.16px] text-[#322d2a]">
                                        Belum ada lamaran aktif
                                    </p>
                                    <p class="mx-auto mt-2 max-w-md text-sm font-normal leading-[1.6] text-[#6f6b67]">
                                        Temukan posisi yang sesuai dan mulai kirim lamaran melalui Career Indodata.
                                    </p>
                                    <a
                                        href="{{ route('jobs.index') }}"
                                        class="mt-5 inline-flex rounded-full bg-[#f97316] px-[22px] py-[14px] text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#ea580c]"
                                    >
                                        Lihat Lowongan
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>


                    {{-- OFFER MANAGEMENT (HANYA MUNCUL JIKA ADA OFFER DARI HRD) --}}
                    @if($offers->isNotEmpty())
                        <div class="rounded-[40px] border border-white bg-white p-6 md:p-8">
                            <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                                Offer Management
                            </p>

                            <h2 class="mt-4 text-[32px] font-light leading-none tracking-[0.32px] text-[#322d2a]">
                                Penawaran Kerja (Offering)
                            </h2>

                            <p class="mt-4 text-sm font-normal leading-[1.6] tracking-[0.14px] text-[#6f6b67]">
                                Informasi penawaran kerja resmi yang diberikan oleh manajemen ISP.
                            </p>

                            <div class="mt-6 space-y-4">
                                @foreach($offers as $offer)
                                    <div class="rounded-[28px] border border-emerald-500/30 bg-gradient-to-br from-emerald-50/50 via-white to-orange-50/30 p-6 shadow-sm">
                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 border-b border-[#322d2a]/10 pb-4">
                                            <div>
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-600 px-3 py-1 text-xs font-semibold text-white">
                                                    ✓ Surat Penawaran Kerja Resmi
                                                </span>
                                                <h3 class="mt-2 text-xl font-medium text-[#322d2a]">
                                                    {{ $offer->position }}
                                                </h3>
                                                <p class="text-xs text-[#6f6b67]">
                                                    {{ $offer->employment_type }} • No. Lamaran: {{ $offer->application?->application_number ?? '-' }}
                                                </p>
                                            </div>
                                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                                                {{ str($offer->status)->headline() }}
                                            </span>
                                        </div>

                                        <div class="mt-4 grid gap-3 sm:grid-cols-2 rounded-[20px] bg-white p-4 text-xs border border-emerald-100 shadow-inner">
                                            @if($offer->salary)
                                                <div>
                                                    <p class="text-[10px] uppercase tracking-wider text-[#6f6b67]">Gaji / Kompensasi</p>
                                                    <p class="mt-0.5 text-base font-bold text-emerald-700">
                                                        Rp {{ number_format($offer->salary, 0, ',', '.') }} <span class="text-xs font-normal text-[#6f6b67]">/ bulan</span>
                                                    </p>
                                                </div>
                                            @endif

                                            @if($offer->start_date)
                                                <div>
                                                    <p class="text-[10px] uppercase tracking-wider text-[#6f6b67]">Tanggal Mulai Bekerja</p>
                                                    <p class="mt-0.5 text-sm font-semibold text-[#322d2a]">
                                                        {{ $offer->start_date->translatedFormat('d F Y') ?? $offer->start_date->format('d M Y') }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if($offer->expired_at)
                                                <div>
                                                    <p class="text-[10px] uppercase tracking-wider text-[#6f6b67]">Batas Waktu Konfirmasi</p>
                                                    <p class="mt-0.5 text-xs font-medium text-rose-600">
                                                        {{ $offer->expired_at->translatedFormat('d F Y') ?? $offer->expired_at->format('d M Y') }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        @if($offer->note)
                                            <div class="mt-3 rounded-[18px] bg-white p-4 text-xs text-[#322d2a] border border-emerald-100">
                                                <p class="font-semibold text-[#322d2a]">Catatan & Penjelasan Penawaran:</p>
                                                <p class="mt-1 leading-relaxed text-[#6f6b67] whitespace-pre-line">{{ $offer->note }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </section>

</x-layouts.app>
