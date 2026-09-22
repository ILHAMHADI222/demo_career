<x-layouts.app :title="$application->application_number . ' - Kelola Lamaran, Interview & Offering'">
    @php
        $profile = $application->candidateProfile;
        $education = $profile?->educations?->first();
        $experience = $profile?->experiences?->first();
        $documents = $profile?->documents ?? collect();
        $interviews = $application->interviews ?? collect();
        $offer = $application->offer;
        $statusHistories = $application->statusHistories ?? collect();
        $notes = $application->hrNotes ?? collect();
        $status = strtolower($application->status);
    @endphp

    <section class="min-h-screen bg-[#f0efe9] px-4 pb-16 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1280px]">

            {{-- HEADER HERO BANNER --}}
            <div class="relative overflow-hidden rounded-[32px] sm:rounded-[36px] bg-[#322d2a] text-white shadow-xl">
                <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#f97316]/20 blur-3xl"></div>
                <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>

                <div class="relative z-10 p-6 sm:p-7 md:p-8">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            @if($profile?->photo)
                                <img src="{{ asset('storage/'.$profile->photo) }}" alt="Foto {{ $profile->full_name }}" class="h-16 w-16 sm:h-20 sm:w-20 rounded-[22px] object-cover ring-2 ring-white/20 shadow-md">
                            @else
                                <div class="flex h-16 w-16 sm:h-20 sm:w-20 items-center justify-center rounded-[22px] bg-[#fff3e8] text-2xl sm:text-3xl font-light text-[#f97316] ring-2 ring-white/20">
                                    {{ strtoupper(substr($profile?->full_name ?? 'K', 0, 1)) }}
                                </div>
                            @endif

                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-[11px] font-normal uppercase tracking-wider text-white backdrop-blur">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#f97316]"></span>
                                        {{ $application->application_number }}
                                    </span>

                                    @php
                                        $normalizedStatus = strtoupper(str_replace([' ', '-'], '_', $application->status ?? ''));
                                    @endphp
                                    @if(in_array($normalizedStatus, ['HIRED', 'ACCEPTED']))
                                        <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-[11px] font-medium text-emerald-300 border border-emerald-400/30">
                                            ✓ {{ $application->status_label }}
                                        </span>
                                    @elseif(in_array($normalizedStatus, ['REJECTED', 'DECLINED', 'WITHDRAWN', 'CANCELLED']))
                                        <span class="rounded-full bg-rose-500/20 px-3 py-1 text-[11px] font-medium text-rose-300 border border-rose-400/30">
                                            ✕ {{ $application->status_label }}
                                        </span>
                                    @elseif(in_array($normalizedStatus, ['OFFERING', 'OFFER']))
                                        <span class="rounded-full bg-blue-500/20 px-3 py-1 text-[11px] font-medium text-blue-300 border border-blue-400/30">
                                            💼 {{ $application->status_label }}
                                        </span>
                                    @elseif(in_array($normalizedStatus, ['HR_INTERVIEW', 'HRINTERVIEW', 'USER_INTERVIEW', 'USERINTERVIEW', 'FINAL_INTERVIEW', 'FINALINTERVIEW', 'TECHNICAL_TEST', 'TECHNICALTEST', 'MEDICAL_CHECK_UP', 'MEDICALCHECKUP']))
                                        <span class="rounded-full bg-amber-500/20 px-3 py-1 text-[11px] font-medium text-amber-300 border border-amber-400/30">
                                            📅 {{ $application->status_label }}
                                        </span>
                                    @else
                                        <span class="rounded-full bg-[#f97316]/20 px-3 py-1 text-[11px] font-medium text-[#f97316] border border-[#f97316]/30">
                                            ● {{ $application->status_label }}
                                        </span>
                                    @endif
                                </div>

                                <h1 class="mt-2 text-2xl sm:text-3xl md:text-4xl font-light tracking-tight text-white">
                                    {{ $profile?->full_name ?? 'Pelamar' }}
                                </h1>

                                <p class="mt-1 text-xs sm:text-sm font-normal text-white/80">
                                    Melamar: <strong class="text-white">{{ $application->job?->title ?? '-' }}</strong> • {{ $application->job?->division?->name ?? 'Divisi' }} ({{ $application->job?->department?->name ?? 'Dept' }})
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2.5">
                            <a href="{{ route('admin.applications.export.excel', ['keyword' => $profile?->email]) }}" class="inline-flex items-center gap-1.5 rounded-full bg-[#f97316] px-4 py-2.5 text-xs font-normal text-white shadow-sm transition hover:bg-[#ea580c]">
                                Export Excel <span>↓</span>
                            </a>
                            <a href="{{ route('admin.applications.index') }}" class="inline-flex items-center gap-1.5 rounded-full border border-white/20 bg-white/10 px-4 py-2.5 text-xs font-normal text-white transition hover:bg-white/20">
                                ← Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- NOTIFIKASI FLASH --}}
            @if(session('success'))
                <div class="mt-5 flex items-center gap-3 rounded-[22px] border border-emerald-200 bg-emerald-50 px-5 py-4 text-xs font-medium text-emerald-800 shadow-sm">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white font-bold">✓</span>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="mt-5 rounded-[22px] border border-rose-200 bg-rose-50 px-5 py-4 text-xs text-rose-800 shadow-sm">
                    <p class="font-semibold">Mohon periksa kembali form:</p>
                    <ul class="mt-1.5 list-inside list-disc space-y-0.5 text-rose-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- MAIN WORKSPACE GRID --}}
            <div class="mt-6 grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">

                {{-- LEFT COLUMN: ACTIONS (INTERVIEW, OFFERING, STATUS, NOTES) --}}
                <div class="space-y-6">

                    {{-- 1. SECTION: ATUR JADWAL INTERVIEW --}}
                    <div class="rounded-[32px] border border-white bg-white p-6 sm:p-7 shadow-sm">
                        <div class="flex items-start justify-between gap-4 border-b border-[#322d2a]/10 pb-4">
                            <div>
                                <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#f97316]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#f97316]"></span>
                                    Recruitment Interview
                                </p>
                                <h2 class="mt-1 text-2xl font-light text-[#322d2a]">
                                    Jadwal Interview
                                </h2>
                                <p class="mt-0.5 text-xs text-[#6f6b67]">
                                    Jadwal yang dibuat akan langsung tampil pada dashboard kandidat pelamar.
                                </p>
                            </div>
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[16px] bg-[#fff3e8] text-lg text-[#f97316]">
                                📅
                            </span>
                        </div>

                        {{-- DAFTAR INTERVIEW TERJADWAL --}}
                        @if($interviews->isNotEmpty())
                            <div class="mt-5 space-y-3">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-[#322d2a]">
                                    Riwayat & Jadwal Aktif ({{ $interviews->count() }}):
                                </p>
                                @foreach($interviews as $interview)
                                    <div class="rounded-[22px] border border-[#322d2a]/10 bg-[#fbfaf8] p-4 text-xs transition hover:border-[#f97316]/40">
                                        <div class="flex flex-wrap items-start justify-between gap-3 border-b border-[#322d2a]/10 pb-2.5">
                                            <div>
                                                <span class="font-semibold text-[#322d2a]">{{ $interview->type }}</span>
                                                <p class="mt-0.5 text-[11px] text-[#6f6b67]">
                                                    {{ $interview->scheduled_date?->translatedFormat('l, d F Y') ?? $interview->scheduled_date?->format('d M Y') }} • Pukul {{ substr($interview->start_time, 0, 5) }} {{ $interview->end_time ? '- ' . substr($interview->end_time, 0, 5) : '' }} WIB
                                                </p>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="rounded-full bg-orange-100 px-2.5 py-0.5 text-[10px] font-medium text-[#f97316]">
                                                    {{ $interview->status_label }}
                                                </span>

                                                <form method="POST" action="{{ route('admin.applications.interviews.destroy', [$application, $interview]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal interview {{ $interview->type }} ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-0.5 text-[10px] font-medium text-rose-600 ring-1 ring-rose-200 transition hover:bg-rose-100" title="Hapus Jadwal Interview">
                                                        🗑️ Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="mt-2.5 grid gap-2 sm:grid-cols-2 text-[11px]">
                                            <p><span class="text-[#6f6b67]">Metode:</span> <strong class="text-[#322d2a]">{{ $interview->method }}</strong></p>
                                            <p><span class="text-[#6f6b67]">Interviewer:</span> <strong class="text-[#322d2a]">{{ $interview->interviewer?->name ?? 'Tim HRD' }}</strong></p>
                                        </div>

                                        @if($interview->meeting_url)
                                            <div class="mt-2 flex items-center justify-between rounded-[14px] bg-white p-2.5 border border-[#322d2a]/10">
                                                <span class="truncate text-[11px] font-mono text-[#f97316]">{{ $interview->meeting_url }}</span>
                                                <a href="{{ $interview->meeting_url }}" target="_blank" class="shrink-0 text-[11px] font-medium text-[#f97316] hover:underline">Buka Link ↗</a>
                                            </div>
                                        @elseif($interview->location)
                                            <p class="mt-2 text-[11px] text-[#6f6b67]"><span class="font-medium text-[#322d2a]">Lokasi:</span> {{ $interview->location }}</p>
                                        @endif

                                        @if($interview->instruction)
                                            <p class="mt-2 rounded-[12px] bg-[#fff3e8] p-2 text-[11px] text-[#322d2a]"><strong class="text-[#f97316]">Instruksi:</strong> {{ $interview->instruction }}</p>
                                        @endif

                                        {{-- RESCHEDULE ACCORDION / EDIT FORM --}}
                                        <details class="mt-3 rounded-[16px] border border-[#322d2a]/10 bg-white p-3 text-xs">
                                            <summary class="cursor-pointer font-medium text-[#f97316] hover:text-[#ea580c] flex items-center justify-between">
                                                <span>✏️ Reschedule / Edit Jadwal Ini</span>
                                                <span class="text-[10px] text-[#6f6b67]">Klik untuk buka form ▼</span>
                                            </summary>

                                            <form method="POST" action="{{ route('admin.applications.interviews.update', [$application, $interview]) }}" class="mt-3.5 space-y-3 border-t border-[#322d2a]/10 pt-3">
                                                @csrf
                                                @method('PUT')

                                                <div class="grid gap-2.5 sm:grid-cols-2">
                                                    <div>
                                                        <label class="text-[10px] uppercase text-[#6f6b67]">Jenis Interview *</label>
                                                        <select name="type" required class="mt-1 w-full rounded-[10px] border border-[#322d2a]/15 bg-[#f0efe9] px-2.5 py-1.5 text-xs text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white">
                                                            @foreach(['HR Interview', 'User Interview', 'Final Interview', 'Interview Offline', 'Interview Online'] as $typeOption)
                                                                <option value="{{ $typeOption }}" @selected($interview->type === $typeOption)>{{ $typeOption }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="text-[10px] uppercase text-[#6f6b67]">Status Jadwal *</label>
                                                        <select name="status" required class="mt-1 w-full rounded-[10px] border border-[#322d2a]/15 bg-[#f0efe9] px-2.5 py-1.5 text-xs text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white">
                                                            @foreach(['SCHEDULED' => 'Scheduled (Terjadwal)', 'RESCHEDULED' => 'Rescheduled (Dijadwalkan Ulang)', 'CONFIRMED' => 'Confirmed (Dikonfirmasi)', 'COMPLETED' => 'Completed (Selesai)', 'CANCELLED' => 'Cancelled (Dibatalkan)'] as $val => $lbl)
                                                                <option value="{{ $val }}" @selected($interview->status === $val)>{{ $lbl }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="grid gap-2.5 sm:grid-cols-3">
                                                    <div>
                                                        <label class="text-[10px] uppercase text-[#6f6b67]">Tanggal Reschedule *</label>
                                                        <input type="date" name="scheduled_date" value="{{ $interview->scheduled_date?->format('Y-m-d') }}" required class="mt-1 w-full rounded-[10px] border border-[#322d2a]/15 bg-[#f0efe9] px-2.5 py-1.5 text-xs text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white">
                                                    </div>

                                                    <div>
                                                        <label class="text-[10px] uppercase text-[#6f6b67]">Jam Mulai *</label>
                                                        <input type="time" name="start_time" value="{{ substr($interview->start_time, 0, 5) }}" required class="mt-1 w-full rounded-[10px] border border-[#322d2a]/15 bg-[#f0efe9] px-2.5 py-1.5 text-xs text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white">
                                                    </div>

                                                    <div>
                                                        <label class="text-[10px] uppercase text-[#6f6b67]">Jam Selesai</label>
                                                        <input type="time" name="end_time" value="{{ substr($interview->end_time, 0, 5) }}" class="mt-1 w-full rounded-[10px] border border-[#322d2a]/15 bg-[#f0efe9] px-2.5 py-1.5 text-xs text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white">
                                                    </div>
                                                </div>

                                                <div class="grid gap-2.5 sm:grid-cols-2">
                                                    <div>
                                                        <label class="text-[10px] uppercase text-[#6f6b67]">Metode *</label>
                                                        <select name="method" required class="mt-1 w-full rounded-[10px] border border-[#322d2a]/15 bg-[#f0efe9] px-2.5 py-1.5 text-xs text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white">
                                                            @foreach(['Online (Google Meet / Zoom)', 'Tatap Muka di Kantor ISP (Offline)', 'Telepon / WhatsApp Call'] as $methodOpt)
                                                                <option value="{{ $methodOpt }}" @selected($interview->method === $methodOpt)>{{ $methodOpt }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="text-[10px] uppercase text-[#6f6b67]">Pewawancara</label>
                                                        <select name="interviewer_id" class="mt-1 w-full rounded-[10px] border border-[#322d2a]/15 bg-[#f0efe9] px-2.5 py-1.5 text-xs text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white">
                                                            <option value="">-- Tetapkan HR/Interviewer --</option>
                                                            @foreach($interviewers as $interviewer)
                                                                <option value="{{ $interviewer->id }}" @selected($interview->interviewer_id === $interviewer->id)>{{ $interviewer->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="grid gap-2.5 sm:grid-cols-2">
                                                    <div>
                                                        <label class="text-[10px] uppercase text-[#6f6b67]">Link Meeting (Google Meet/Zoom)</label>
                                                        <input type="url" name="meeting_url" value="{{ $interview->meeting_url }}" placeholder="https://meet.google.com/..." class="mt-1 w-full rounded-[10px] border border-[#322d2a]/15 bg-[#f0efe9] px-2.5 py-1.5 text-xs text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white">
                                                    </div>

                                                    <div>
                                                        <label class="text-[10px] uppercase text-[#6f6b67]">Lokasi Fisik (Jika Offline)</label>
                                                        <input type="text" name="location" value="{{ $interview->location }}" placeholder="Ruang Meeting Lantai 2" class="mt-1 w-full rounded-[10px] border border-[#322d2a]/15 bg-[#f0efe9] px-2.5 py-1.5 text-xs text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white">
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="text-[10px] uppercase text-[#6f6b67]">Instruksi / Catatan Reschedule</label>
                                                    <textarea name="instruction" rows="2" placeholder="Catatan informasi baru untuk kandidat..." class="mt-1 w-full rounded-[10px] border border-[#322d2a]/15 bg-[#f0efe9] p-2.5 text-xs text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white">{{ $interview->instruction }}</textarea>
                                                </div>

                                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-full bg-[#f97316] px-4 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-[#ea580c]">
                                                    Simpan Perubahan Jadwal <span>✓</span>
                                                </button>
                                            </form>
                                        </details>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- FORM BUAT JADWAL INTERVIEW --}}
                        <div class="mt-6 rounded-[24px] bg-[#f0efe9] p-5">
                            <h3 class="text-sm font-medium text-[#322d2a]">
                                + Jadwalkan Interview Baru
                            </h3>

                            <form method="POST" action="{{ route('admin.applications.interviews.store', $application) }}" class="mt-4 space-y-3.5 text-xs">
                                @csrf

                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label for="interview_type" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Jenis / Tahap Interview *</label>
                                        <select id="interview_type" name="type" required class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white px-3 py-2 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316]">
                                            <option value="HR Interview">HR Interview (Wawancara HRD)</option>
                                            <option value="User Interview">User Technical Interview</option>
                                            <option value="Final Interview">Final Interview Management</option>
                                            <option value="Interview Offline">Interview Tatap Muka di Kantor</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="interviewer_id" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Pewawancara (Interviewer)</label>
                                        <select id="interviewer_id" name="interviewer_id" class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white px-3 py-2 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316]">
                                            <option value="">-- Pilih Pewawancara / HR --</option>
                                            @foreach($interviewers as $interviewer)
                                                <option value="{{ $interviewer->id }}" @selected(auth()->id() === $interviewer->id)>
                                                    {{ $interviewer->name }} ({{ $interviewer->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-3">
                                    <div>
                                        <label for="scheduled_date" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Tanggal *</label>
                                        <input id="scheduled_date" type="date" name="scheduled_date" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white px-3 py-2 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316]">
                                    </div>

                                    <div>
                                        <label for="start_time" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Jam Mulai *</label>
                                        <input id="start_time" type="time" name="start_time" value="09:00" required class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white px-3 py-2 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316]">
                                    </div>

                                    <div>
                                        <label for="end_time" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Jam Selesai</label>
                                        <input id="end_time" type="time" name="end_time" value="10:00" class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white px-3 py-2 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316]">
                                    </div>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label for="method" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Metode Pelaksanaan *</label>
                                        <select id="method" name="method" required class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white px-3 py-2 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316]">
                                            <option value="Online (Google Meet / Zoom)">Online (Google Meet / Zoom)</option>
                                            <option value="Tatap Muka di Kantor">Tatap Muka di Kantor ISP (Offline)</option>
                                            <option value="Telepon / WhatsApp Call">Telepon / WhatsApp Call</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="meeting_url" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Link Google Meet / Zoom</label>
                                        <input id="meeting_url" type="url" name="meeting_url" placeholder="https://meet.google.com/xyz-abcd-efg" class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white px-3 py-2 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316]">
                                    </div>
                                </div>

                                <div>
                                    <label for="location" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Lokasi Fisik (Jika Tatap Muka Offline)</label>
                                    <input id="location" type="text" name="location" placeholder="Gedung ISP Lantai 2, Ruang Interview A" class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white px-3 py-2 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316]">
                                </div>

                                <div>
                                    <label for="instruction" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Instruksi / Catatan untuk Kandidat</label>
                                    <textarea id="instruction" name="instruction" rows="2" placeholder="Mohon hadir 10 menit sebelum waktu wawancara dan siapkan file portfolio..." class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white p-3 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316]"></textarea>
                                </div>

                                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#f97316] px-5 py-2.5 text-xs font-medium text-white shadow-sm transition hover:bg-[#ea580c]">
                                    Simpan & Terbitkan Jadwal Interview <span>→</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- 2. SECTION: JADWAL & SURAT PENAWARAN (OFFERING) --}}
                    <div class="rounded-[32px] border border-white bg-white p-6 sm:p-7 shadow-sm">
                        <div class="flex items-start justify-between gap-4 border-b border-[#322d2a]/10 pb-4">
                            <div>
                                <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-emerald-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Job Offer Management
                                </p>
                                <h2 class="mt-1 text-2xl font-light text-[#322d2a]">
                                    Jadwal & Surat Offering
                                </h2>
                                <p class="mt-0.5 text-xs text-[#6f6b67]">
                                    Rincian penawaran kerja ini akan muncul resmi di dashboard pelamar untuk ditinjau.
                                </p>
                            </div>
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[16px] bg-emerald-50 text-lg text-emerald-600">
                                💼
                            </span>
                        </div>

                        {{-- OFFER AKTIF JIKA SUDAH PERNAH DIBUAT --}}
                        @if($offer)
                            <div class="mt-5 rounded-[24px] border border-emerald-200 bg-emerald-50/50 p-5 text-xs">
                                <div class="flex items-start justify-between gap-3 border-b border-emerald-200 pb-3">
                                    <div>
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-600 px-2.5 py-0.5 text-[10px] font-semibold text-white">
                                            ✓ Penawaran Terdaftar
                                        </span>
                                        <h4 class="mt-2 text-base font-medium text-[#322d2a]">{{ $offer->position }}</h4>
                                        <p class="text-[11px] text-[#6f6b67]">{{ $offer->employment_type }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="rounded-full bg-emerald-200/60 px-3 py-1 text-xs font-semibold text-emerald-900">
                                            Status: {{ str($offer->status)->headline() }}
                                        </span>

                                        <form method="POST" action="{{ route('admin.applications.offers.destroy', [$application, $offer]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penawaran kerja (offering) ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-1 text-[10px] font-medium text-rose-600 ring-1 ring-rose-200 transition hover:bg-rose-100" title="Hapus Offering">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="mt-3.5 grid gap-3 sm:grid-cols-3 rounded-[16px] bg-white p-3.5 border border-emerald-100">
                                    <div>
                                        <p class="text-[10px] uppercase tracking-wider text-[#6f6b67]">Gaji / Kompensasi</p>
                                        <p class="mt-0.5 font-bold text-emerald-700">Rp {{ number_format($offer->salary ?: 0, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase tracking-wider text-[#6f6b67]">Tanggal Mulai Kerja</p>
                                        <p class="mt-0.5 font-semibold text-[#322d2a]">{{ $offer->start_date?->translatedFormat('d F Y') ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase tracking-wider text-[#6f6b67]">Batas Konfirmasi</p>
                                        <p class="mt-0.5 font-semibold text-rose-600">{{ $offer->expired_at?->translatedFormat('d F Y') ?? '-' }}</p>
                                    </div>
                                </div>

                                @if($offer->note)
                                    <p class="mt-3 rounded-[12px] bg-white p-3 text-[11px] leading-relaxed text-[#322d2a] border border-emerald-100">
                                        <strong class="text-emerald-800">Catatan Offering:</strong> {{ $offer->note }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        {{-- FORM BUAT / PERBARUI OFFERING --}}
                        <div class="mt-5 rounded-[24px] bg-[#f0efe9] p-5">
                            <h3 class="text-sm font-medium text-[#322d2a]">
                                {{ $offer ? 'Perbarui Data Offering' : '+ Buat Penawaran Kerja (Offering)' }}
                            </h3>

                            <form method="POST" action="{{ route('admin.applications.offers.store', $application) }}" class="mt-4 space-y-3.5 text-xs">
                                @csrf

                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label for="offer_position" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Posisi Yang Ditawarkan *</label>
                                        <input id="offer_position" type="text" name="position" value="{{ old('position', $offer?->position ?? $application->job?->title) }}" required class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white px-3 py-2 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316]">
                                    </div>

                                    <div>
                                        <label for="employment_type" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Jenis Hubungan Kerja *</label>
                                        <select id="employment_type" name="employment_type" required class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white px-3 py-2 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316]">
                                            @foreach(['Karyawan Tetap (PKWTT)', 'Karyawan Kontrak (PKWT)', 'Probation 3 Bulan', 'Magang / Internship'] as $type)
                                                <option value="{{ $type }}" @selected(old('employment_type', $offer?->employment_type ?? 'Karyawan Tetap (PKWTT)') === $type)>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-3">
                                    <div>
                                        <label for="salary" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Gaji Pokok / Bulan (Rp)</label>
                                        <input id="salary" type="number" name="salary" value="{{ old('salary', $offer?->salary) }}" placeholder="7500000" class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white px-3 py-2 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316]">
                                    </div>

                                    <div>
                                        <label for="start_date" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Tanggal Mulai Kerja</label>
                                        <input id="start_date" type="date" name="start_date" value="{{ old('start_date', $offer?->start_date?->format('Y-m-d')) }}" class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white px-3 py-2 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316]">
                                    </div>

                                    <div>
                                        <label for="expired_at" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Batas Konfirmasi</label>
                                        <input id="expired_at" type="date" name="expired_at" value="{{ old('expired_at', $offer?->expired_at?->format('Y-m-d')) }}" class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white px-3 py-2 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316]">
                                    </div>
                                </div>

                                <div>
                                    <label for="offer_note" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Catatan & Penjelasan Benefit / Penawaran</label>
                                    <textarea id="offer_note" name="note" rows="3" placeholder="Gaji pokok, tunjangan komunikasi, BPJS Kesehatan & Ketenagakerjaan, serta penempatan di kantor pusat ISP..." class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-white p-3 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316]">{{ old('note', $offer?->note) }}</textarea>
                                </div>

                                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-emerald-600 px-5 py-2.5 text-xs font-medium text-white shadow-sm transition hover:bg-emerald-700">
                                    Simpan & Publikasikan Offering ke Dashboard Pelamar <span>✓</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- 3. SECTION: CATATAN HR & LOG RIWAYAT --}}
                    <div class="rounded-[32px] border border-white bg-white p-6 sm:p-7 shadow-sm">
                        <div class="flex items-center justify-between border-b border-[#322d2a]/10 pb-4">
                            <div>
                                <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#322d2a]"></span>
                                    Internal HR Notes & Audit
                                </p>
                                <h2 class="mt-1 text-xl font-light text-[#322d2a]">
                                    Catatan HR & Riwayat Status
                                </h2>
                            </div>
                        </div>

                        {{-- Form Catatan HR --}}
                        <form method="POST" action="{{ route('admin.applications.notes.store', $application) }}" class="mt-4 flex gap-2">
                            @csrf
                            <input type="hidden" name="type" value="HR_GENERAL">
                            <input type="text" name="note" required placeholder="Tulis catatan internal rekrutmen di sini..." class="flex-1 rounded-full border border-[#322d2a]/15 bg-[#f0efe9] px-4 py-2 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white">
                            <button type="submit" class="shrink-0 rounded-full bg-[#322d2a] px-4 py-2 text-xs font-normal text-white transition hover:bg-[#f97316]">
                                Simpan Catatan
                            </button>
                        </form>

                        {{-- List Catatan --}}
                        @if($notes->isNotEmpty())
                            <div class="mt-4 space-y-2">
                                @foreach($notes as $item)
                                    <div class="rounded-[16px] bg-[#fbfaf8] p-3 text-xs border border-[#322d2a]/10">
                                        <p class="text-[#322d2a]">{{ $item->note }}</p>
                                        <p class="mt-1 text-[10px] text-[#6f6b67]">{{ $item->creator?->name ?? 'HR' }} • {{ $item->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Timeline Riwayat Status --}}
                        @if($statusHistories->isNotEmpty())
                            <div class="mt-5 border-t border-[#322d2a]/10 pt-4">
                                <p class="text-[11px] font-medium uppercase tracking-wider text-[#6f6b67]">Audit Trail Status:</p>
                                <div class="mt-3 space-y-2">
                                    @foreach($statusHistories->sortByDesc('changed_at') as $history)
                                        @php
                                            $histNorm = strtoupper(str_replace([' ', '-'], '_', $history->to_status ?? ''));
                                            $histLabel = match ($histNorm) {
                                                'DRAFT' => 'Draft',
                                                'SUBMITTED' => 'Lamaran Dikirim',
                                                'DOCUMENT_REVIEW', 'DOCUMENTREVIEW' => 'Review Berkas',
                                                'ADMINISTRATIVE_REVIEW', 'ADMINISTRATIVEREVIEW' => 'Lolos Administrasi',
                                                'TECHNICAL_TEST', 'TECHNICALTEST' => 'Tes Teknis / Psikotes',
                                                'HR_INTERVIEW', 'HRINTERVIEW' => 'Interview HRD',
                                                'USER_INTERVIEW', 'USERINTERVIEW' => 'Interview User',
                                                'FINAL_INTERVIEW', 'FINALINTERVIEW' => 'Interview Direksi',
                                                'MEDICAL_CHECK_UP', 'MEDICALCHECKUP' => 'Medical Check Up (MCU)',
                                                'OFFERING' => 'Offering (Penawaran Kerja)',
                                                'HIRED', 'ACCEPTED' => 'Diterima Bekerja',
                                                'REJECTED', 'DECLINED' => 'Tidak Lolos (Ditolak)',
                                                'WITHDRAWN' => 'Mengundurkan Diri',
                                                'CANCELLED' => 'Dibatalkan',
                                                default => str($history->to_status)->replace('_', ' ')->headline()->toString(),
                                            };
                                        @endphp
                                        <div class="flex items-start justify-between gap-3 text-xs">
                                            <div>
                                                <span class="font-medium text-[#322d2a]">{{ $histLabel }}</span>
                                                @if($history->note)
                                                    <p class="text-[11px] text-[#6f6b67]">{{ $history->note }}</p>
                                                @endif
                                            </div>
                                            <span class="shrink-0 text-[10px] text-[#6f6b67]">{{ $history->changed_at->format('d M Y H:i') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                </div>

                {{-- RIGHT COLUMN: STATUS UPDATER & CANDIDATE SUMMARY --}}
                <div class="space-y-6">

                    {{-- 1. CARD: PERBARUI STATUS LAMARAN --}}
                    <div class="rounded-[32px] border border-white bg-white p-6 sm:p-7 shadow-sm">
                        <div class="flex items-start justify-between gap-3 border-b border-[#322d2a]/10 pb-4">
                            <div>
                                <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#f97316]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#f97316]"></span>
                                    Status Selection
                                </p>
                                <h2 class="mt-1 text-xl font-light text-[#322d2a]">
                                    Kelola Tahap Seleksi
                                </h2>
                            </div>
                            <span class="rounded-full bg-[#fff3e8] px-3 py-1 text-xs font-medium text-[#f97316]">
                                {{ $application->status_label }}
                            </span>
                        </div>

                        <form method="POST" action="{{ route('admin.applications.status', $application) }}" class="mt-4 space-y-3.5 text-xs">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="status" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Ubah Status / Tahap Pelamar *</label>
                                <select id="status" name="status" required class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2.5 text-xs font-medium text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white">
                                    @foreach($statusOptions as $code => $label)
                                        <option value="{{ $code }}" @selected($application->status === $code)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="status_note" class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Catatan Perubahan Status</label>
                                <textarea id="status_note" name="note" rows="2" placeholder="Catatan opsional mengenai keputusan tahapan seleksi..." class="mt-1 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] p-3 text-xs font-normal text-[#322d2a] outline-none focus:border-[#f97316] focus:bg-white"></textarea>
                            </div>

                            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#322d2a] px-5 py-2.5 text-xs font-medium text-white shadow-sm transition hover:bg-[#f97316]">
                                Perbarui Tahap Lamaran <span>✓</span>
                            </button>
                        </form>
                    </div>

                    {{-- 2. CARD: RINGKASAN DATA KANDIDAT --}}
                    <div class="rounded-[32px] border border-white bg-white p-6 sm:p-7 shadow-sm">
                        <div class="flex items-center justify-between border-b border-[#322d2a]/10 pb-4">
                            <div>
                                <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#f97316]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#f97316]"></span>
                                    Candidate Snapshot
                                </p>
                                <h3 class="mt-1 text-xl font-light text-[#322d2a]">
                                    Profil & Berkas Pelamar
                                </h3>
                            </div>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                                {{ $profile?->profile_completion ?? 0 }}% Lengkap
                            </span>
                        </div>

                        {{-- Kontak Utama --}}
                        <div class="mt-5 space-y-3 text-xs">
                            <div class="rounded-[18px] bg-[#f0efe9] p-3.5">
                                <p class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Kontak & Komunikasi</p>
                                <p class="mt-1 font-medium text-[#322d2a]">{{ $profile?->email ?? '-' }}</p>
                                <p class="mt-0.5 text-[#6f6b67]">WA/Telp: <strong class="text-[#322d2a]">{{ $profile?->whatsapp ?? $profile?->phone ?? '-' }}</strong></p>
                            </div>

                            <div class="rounded-[18px] bg-[#f0efe9] p-3.5">
                                <p class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Domisili</p>
                                <p class="mt-1 font-medium text-[#322d2a]">{{ $profile?->city ?? '-' }}, {{ $profile?->province ?? '-' }}</p>
                                <p class="mt-0.5 text-[11px] text-[#6f6b67] line-clamp-2">{{ $profile?->address ?? '-' }}</p>
                            </div>

                            <div class="rounded-[18px] bg-[#f0efe9] p-3.5">
                                <p class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Pendidikan Terakhir</p>
                                @if($education)
                                    <p class="mt-1 font-medium text-[#322d2a]">{{ $education->education_level }} • {{ $education->major }}</p>
                                    <p class="mt-0.5 text-[11px] text-[#6f6b67]">{{ $education->institution }} (IPK: {{ $education->ipk ?: '-' }})</p>
                                @else
                                    <p class="mt-1 text-[#6f6b67]">-</p>
                                @endif
                            </div>

                            @if($experience)
                                <div class="rounded-[18px] bg-[#f0efe9] p-3.5">
                                    <p class="text-[10px] font-normal uppercase tracking-wider text-[#6f6b67]">Pengalaman Terakhir</p>
                                    <p class="mt-1 font-medium text-[#322d2a]">{{ $experience->position }} di {{ $experience->company_name }}</p>
                                    <p class="mt-0.5 text-[11px] text-[#6f6b67]">{{ $experience->work_years }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- Tautan Portofolio & Dokumen --}}
                        <div class="mt-5 space-y-2 border-t border-[#322d2a]/10 pt-4 text-xs">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-[#6f6b67]">Berkas & Tautan Dokumen:</p>

                            @if($profile?->linkedin_url)
                                <a href="{{ $profile->linkedin_url }}" target="_blank" class="flex items-center justify-between rounded-[14px] bg-[#f0efe9] px-3.5 py-2 text-[#322d2a] hover:bg-[#fff3e8] hover:text-[#f97316]">
                                    <span>🌐 LinkedIn Profile</span>
                                    <span>↗</span>
                                </a>
                            @endif

                            @if($profile?->portfolio_url)
                                <a href="{{ $profile->portfolio_url }}" target="_blank" class="flex items-center justify-between rounded-[14px] bg-[#f0efe9] px-3.5 py-2 text-[#322d2a] hover:bg-[#fff3e8] hover:text-[#f97316]">
                                    <span>📁 Portfolio Online</span>
                                    <span>↗</span>
                                </a>
                            @endif

                            @forelse($documents as $doc)
                                <div class="flex items-center justify-between rounded-[14px] bg-[#f0efe9] px-3.5 py-2 text-[#322d2a]">
                                    <span class="truncate pr-2">{{ $doc->documentType?->name ?? 'Dokumen' }}: {{ $doc->file_name }}</span>
                                    <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="shrink-0 text-xs font-medium text-[#f97316] hover:underline">
                                        Unduh ↗
                                    </a>
                                </div>
                            @empty
                                <p class="text-[11px] text-[#6f6b67]">Belum ada berkas lampiran tambahan.</p>
                            @endforelse
                        </div>

                        {{-- Detail Tambahan (Collapsible Accordion) --}}
                        <details class="mt-4 rounded-[18px] border border-[#322d2a]/10 bg-[#fbfaf8] p-3.5 text-xs">
                            <summary class="cursor-pointer font-medium text-[#322d2a] hover:text-[#f97316]">
                                Lihat Seluruh Data Diri Lengkap (NIK, RT/RW, dsb) ▼
                            </summary>
                            <div class="mt-3 grid gap-2 sm:grid-cols-2 text-[11px] border-t border-[#322d2a]/10 pt-3">
                                <p><span class="text-[#6f6b67]">NIK:</span> {{ $profile?->nik ?: '-' }}</p>
                                <p><span class="text-[#6f6b67]">Gender:</span> {{ $profile?->gender ?: '-' }}</p>
                                <p><span class="text-[#6f6b67]">Status Nikah:</span> {{ $profile?->marital_status ?: '-' }}</p>
                                <p><span class="text-[#6f6b67]">Kelahiran:</span> {{ $profile?->birth_place ?: '-' }}, {{ $profile?->birth_date?->format('d M Y') ?: '-' }}</p>
                                <p><span class="text-[#6f6b67]">Kel/Desa:</span> {{ $profile?->village ?: '-' }}</p>
                                <p><span class="text-[#6f6b67]">Kecamatan:</span> {{ $profile?->district ?: '-' }}</p>
                                <p><span class="text-[#6f6b67]">SIM:</span> {{ $profile?->driving_license ?: '-' }}</p>
                                <p><span class="text-[#6f6b67]">Tgl Melamar:</span> {{ $application->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </details>
                    </div>

                </div>

            </div>

        </div>
    </section>
</x-layouts.app>
