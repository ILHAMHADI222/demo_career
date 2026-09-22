<x-layouts.app title="{{ $assessment->title }} - Detail Asesmen">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-16 pt-6 sm:pt-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">

            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('admin.disc.assessments.index') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    ← Kembali ke Daftar Asesmen
                </a>

                <div class="flex flex-wrap items-center gap-2.5">
                    @if ($assessment->status === 'PUBLISHED')
                        <form action="{{ route('admin.disc.assessments.close', $assessment) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menutup asesmen ini? Peserta baru tidak dapat memulai sesi.')">
                            @csrf
                            <button type="submit" class="rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-100 shadow-sm">
                                ✕ Tutup Asesmen
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.disc.assessments.publish', $assessment) }}" method="POST" class="inline" onsubmit="return confirm('Buka asesmen ini sekarang? Kandidat yang diundang akan dapat langsung mengerjakan tes.')">
                            @csrf
                            <button type="submit" class="rounded-full border border-emerald-300 bg-emerald-50 px-4 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100 shadow-sm">
                                ✓ Buka Asesmen (Publish)
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.disc.assessments.edit', $assessment) }}"
                       class="rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-100 shadow-sm">
                        ✏️ Edit Asesmen
                    </a>

                    <a href="{{ route('admin.disc.assessments.monitor', $assessment) }}"
                       class="rounded-full border border-blue-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 shadow-sm">
                        🛰 Live Monitoring
                    </a>

                    <button type="button"
                            onclick="openDeleteModal()"
                            class="rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-xs font-semibold text-rose-600 transition hover:bg-rose-600 hover:text-white shadow-sm">
                        ✕ Hapus
                    </button>
                </div>
            </div>

            {{-- NOTIFIKASI FLASH --}}
            @if(session('success'))
                <div class="mb-5 flex items-center gap-2.5 rounded-[20px] border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-medium text-emerald-800 shadow-xs">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-[10px]">✓</span>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 flex items-center gap-2.5 rounded-[20px] border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-medium text-rose-800 shadow-xs">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-rose-600 text-white font-bold text-[10px]">✕</span>
                    <p>{{ $errors->first() }}</p>
                </div>
            @endif

            {{-- HEADER SUMMARY CARD --}}
            <div class="rounded-[40px] border border-white/80 bg-white p-8 shadow-sm">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-md bg-slate-100 px-2.5 py-1 font-mono text-xs font-bold text-slate-700">{{ $assessment->code }}</span>
                            <span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-medium text-[#f97316]">Bank: {{ $assessment->questionBank->name ?? '-' }} (v{{ $assessment->question_bank_version }})</span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-600">⏱ {{ $assessment->duration_minutes }} Menit</span>
                        </div>
                        <h1 class="mt-3 text-2xl font-light text-[#322d2a] md:text-3xl lg:text-4xl">
                            {{ $assessment->title }}
                        </h1>
                        <p class="mt-2 text-xs text-slate-500">
                            Visibilitas Hasil: <span class="font-semibold text-slate-700">{{ $assessment->result_visibility }}</span> • Status: <span class="font-semibold text-emerald-600">{{ $assessment->status }}</span>
                        </p>
                    </div>

                    <div class="flex items-center gap-4 rounded-3xl bg-slate-50 p-6">
                        <div class="text-center">
                            <p class="text-xs uppercase text-slate-500">Total Peserta</p>
                            <p class="text-3xl font-light text-[#322d2a]">{{ $assessment->participants->count() }}</p>
                        </div>
                        <div class="h-10 w-px bg-slate-200"></div>
                        <div class="text-center">
                            <p class="text-xs uppercase text-emerald-600">Selesai (Completed)</p>
                            <p class="text-3xl font-light text-emerald-700">{{ $assessment->sessions->where('status', 'COMPLETED')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ASSIGN CANDIDATES PANEL --}}
            <div class="mt-8 rounded-[36px] border border-white/80 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-[#322d2a]">Undang Peserta ke Asesmen Ini</h2>
                <form method="POST" action="{{ route('admin.disc.assessments.assign', $assessment) }}" class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center">
                    @csrf
                    <select name="user_ids[]" multiple required class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs text-slate-800 focus:border-[#f97316] focus:outline-none sm:w-80">
                        @foreach ($candidates as $cand)
                            <option value="{{ $cand->id }}">{{ $cand->name }} ({{ $cand->email }})</option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-full bg-[#322d2a] px-6 py-3 text-xs font-semibold text-white transition hover:bg-black">
                        + Tambahkan Peserta Terpilih
                    </button>
                </form>
            </div>

            {{-- PARTICIPANTS TABLE --}}
            <div class="mt-6 overflow-hidden rounded-[36px] border border-white/80 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-[#322d2a]">Daftar Peserta & Hasil Tes DISC</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700">
                        <thead class="bg-slate-50/60 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-4">Nama Peserta</th>
                                <th class="px-5 py-4">Status & Tahap</th>
                                <th class="px-5 py-4 text-center">Sub-Tes 1 (Deret)</th>
                                <th class="px-5 py-4 text-center">Sub-Tes 2 (Analogi)</th>
                                <th class="px-5 py-4">Tahap 3 (DISC)</th>
                                <th class="px-5 py-4">Kesempatan Tes</th>
                                <th class="px-5 py-4 text-center">Keamanan</th>
                                <th class="px-5 py-4 text-right">Laporan & Hasil</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-normal">
                            @forelse ($assessment->participants as $part)
                                @php
                                    $session = $part->latestSession;
                                    $completedSessionsCount = \App\Models\DiscAssessmentSession::where('disc_assessment_id', $assessment->id)->where('participant_id', $part->user_id)->where('status', 'COMPLETED')->count();
                                    $maxAttempts = $part->max_attempts ?: 1;
                                @endphp
                                <tr class="transition hover:bg-slate-50/50">
                                    <td class="px-5 py-4">
                                        <div class="font-medium text-[#322d2a]">{{ $part->user->name ?? '-' }}</div>
                                        <div class="text-xs text-slate-500">{{ $part->user->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if ($session && $session->status === 'COMPLETED')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> SELESAI (3/3)
                                            </span>
                                        @elseif ($session && $session->status === 'IN_PROGRESS')
                                            <div class="flex flex-col gap-1">
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                                    Tahap {{ $session->current_stage ?? 1 }}: {{ ($session->current_stage ?? 1) == 1 ? 'Deret Hitung' : (($session->current_stage ?? 1) == 2 ? 'Analogi Verbal' : 'Kepribadian') }}
                                                </span>
                                                <span class="text-[10px] text-slate-400">Aktif: {{ $session->last_activity_at?->diffForHumans() ?? 'Baru saja' }}</span>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                                BELUM MULAI
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        @if ($session && $session->stage_1_completed_at)
                                            <div class="inline-flex flex-col items-center">
                                                <span class="rounded-lg bg-blue-50 border border-blue-200 px-2.5 py-0.5 font-mono text-xs font-bold text-blue-700">
                                                    {{ number_format($session->stage_1_score ?? 0, 1) }}%
                                                </span>
                                                <span class="mt-0.5 text-[10px] text-slate-500 font-medium">
                                                    {{ $session->stage_1_correct_count ?? 0 }} / 25 Benar
                                                </span>
                                            </div>
                                        @elseif ($session && ($session->current_stage ?? 1) == 1)
                                            <span class="rounded-full bg-amber-50 px-2.5 py-0.5 text-[11px] font-medium text-amber-700 animate-pulse">
                                                Mengerjakan...
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        @if ($session && $session->stage_2_completed_at)
                                            <div class="inline-flex flex-col items-center">
                                                <span class="rounded-lg bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 font-mono text-xs font-bold text-emerald-700">
                                                    {{ number_format($session->stage_2_score ?? 0, 1) }}%
                                                </span>
                                                <span class="mt-0.5 text-[10px] text-slate-500 font-medium">
                                                    {{ $session->stage_2_correct_count ?? 0 }} / 50 Benar
                                                </span>
                                            </div>
                                        @elseif ($session && ($session->current_stage ?? 1) == 2)
                                            <span class="rounded-full bg-amber-50 px-2.5 py-0.5 text-[11px] font-medium text-amber-700 animate-pulse">
                                                Mengerjakan...
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @if ($session && $session->disc_profile_code)
                                            <div class="flex items-center gap-1.5">
                                                <span class="rounded-lg bg-orange-100 px-2.5 py-1 font-mono text-xs font-bold text-[#f97316]">
                                                    {{ $session->disc_profile_code }}
                                                </span>
                                                <span class="text-xs text-slate-700">{{ Str::limit($session->disc_profile_name, 18) }}</span>
                                            </div>
                                        @elseif ($session && ($session->current_stage ?? 1) == 3)
                                            <span class="rounded-full bg-purple-50 px-2.5 py-0.5 text-[11px] font-medium text-purple-700 animate-pulse">
                                                Sedang Tahap 3...
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $candidateName = $part->user->name ?? 'Bapak/Ibu/Saudara/i';
                                            $candidateEmail = $part->user->email ?? '';
                                            $rawPhone = $part->user->candidateProfile?->phone ?? '';
                                            $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                                            if (str_starts_with($cleanPhone, '0')) {
                                                $cleanPhone = '62' . substr($cleanPhone, 1);
                                            }

                                            $daysMap = [
                                                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                                                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
                                            ];
                                            $monthsMap = [
                                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                            ];

                                            $nowDt = now();
                                            $dayIndo = $daysMap[$nowDt->format('l')] ?? $nowDt->format('l');
                                            $monthIndo = $monthsMap[(int)$nowDt->format('n')] ?? $nowDt->format('F');
                                            $hariTanggal = "{$dayIndo}, " . $nowDt->format('d') . " {$monthIndo} " . $nowDt->format('Y');

                                            $assessmentUrl = route('disc.assessment.instruction', $assessment);
                                            $waktuTeks = "Batas waktu 5 jam sejak undangan ini dikirim (Waktu pengerjaan: Sub-Tes 1 & 2 masing-masing 10 menit)";

                                            $waMessage = "Yth. " . $candidateName . ",\n\nSelamat! 🎉\n\nKami dari PT Indodata Solusi Persada menginformasikan bahwa Anda lolos ke tahap Psikotes.\n\nSilakan mengerjakan soal yang telah kami siapkan dengan detail sebagai berikut:\n\n📅 Hari/Tanggal: " . $hariTanggal . "\n⏰ Waktu: " . $waktuTeks . "\n🔗 Link Akses Soal: " . $assessmentUrl . "\n\nMohon mengerjakan soal sesuai dengan waktu dan ketentuan yang telah ditentukan.\n\nApabila terdapat kendala dalam mengakses soal, silakan menghubungi kami.\n\nTerima kasih atas perhatian dan kerja samanya.\n\nHormat kami,\nPT Indodata Solusi Persada";

                                            $waUrl = "https://api.whatsapp.com/send?phone=" . $cleanPhone . "&text=" . rawurlencode($waMessage);

                                            $mailSubject = "Undangan Tahap Psikotes - PT Indodata Solusi Persada";
                                            $mailBody = "Yth. " . $candidateName . ",\n\nSelamat!\n\nKami dari PT Indodata Solusi Persada menginformasikan bahwa Anda lolos ke tahap Psikotes.\n\nSilakan mengerjakan soal yang telah kami siapkan dengan detail sebagai berikut:\n\nHari/Tanggal: " . $hariTanggal . "\nWaktu: " . $waktuTeks . "\nLink Akses Soal: " . $assessmentUrl . "\n\nMohon mengerjakan soal sesuai dengan waktu dan ketentuan yang telah ditentukan.\n\nApabila terdapat kendala dalam mengakses soal, silakan menghubungi kami.\n\nTerima kasih.\n\nHormat kami,\nPT Indodata Solusi Persada";

                                            $gmailUrl = "https://mail.google.com/mail/?view=cm&fs=1&to=" . urlencode($candidateEmail) . "&su=" . rawurlencode($mailSubject) . "&body=" . rawurlencode($mailBody);
                                        @endphp

                                        <div class="flex flex-col gap-1.5">
                                            <span class="text-xs font-semibold {{ $completedSessionsCount >= $maxAttempts ? 'text-slate-600' : 'text-emerald-700' }}">
                                                {{ $completedSessionsCount }}/{{ $maxAttempts }} Selesai
                                            </span>
                                            <form action="{{ route('admin.disc.assessments.participants.attempts', [$assessment, $part]) }}" method="POST" class="flex items-center gap-1">
                                                @csrf
                                                @method('PUT')
                                                <div class="flex items-center rounded-xl border border-slate-200 bg-white p-0.5 shadow-xs">
                                                    <input type="number" name="max_attempts" value="{{ $maxAttempts }}" min="1" max="20" class="w-10 border-0 bg-transparent px-1 py-0.5 text-center font-mono text-xs font-bold text-slate-800 focus:outline-none" title="Ubah batas kesempatan tes">
                                                </div>
                                                <button type="submit" class="rounded-xl border border-orange-200 bg-orange-50 px-2 py-1 text-[10px] font-bold text-[#f97316] transition hover:bg-[#f97316] hover:text-white" title="Simpan batas kesempatan">
                                                    Simpan
                                                </button>
                                            </form>

                                            {{-- BROADCAST BUTTONS --}}
                                            <div class="flex items-center gap-1 pt-1 border-t border-slate-100">
                                                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                                                   class="inline-flex items-center gap-1 rounded-lg border border-emerald-300 bg-emerald-50 px-1.5 py-0.5 text-[10px] font-bold text-emerald-700 transition hover:bg-emerald-600 hover:text-white shadow-xs"
                                                   title="Kirim Undangan via WhatsApp">
                                                    <span>💬 WA</span>
                                                </a>
                                                <a href="{{ $gmailUrl }}" target="_blank" rel="noopener noreferrer"
                                                   class="inline-flex items-center gap-1 rounded-lg border border-rose-300 bg-rose-50 px-1.5 py-0.5 text-[10px] font-bold text-rose-700 transition hover:bg-rose-600 hover:text-white shadow-xs"
                                                   title="Kirim Undangan via Gmail">
                                                    <span>✉️ Mail</span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        @if ($session)
                                            <span class="font-mono text-xs font-bold {{ ($session->security_score ?? 100) >= 80 ? 'text-emerald-600' : 'text-amber-600' }}">
                                                {{ $session->security_score ?? 100 }}/100
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        @if ($session)
                                            <div class="inline-flex items-center gap-1.5">
                                                @if ($session->isCompleted())
                                                    <a href="{{ route('admin.disc.sessions.export-csv', $session) }}"
                                                       class="rounded-xl border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                                                       title="Unduh CSV">
                                                        CSV
                                                    </a>
                                                @endif
                                                <a href="{{ route('admin.disc.results.show', $session) }}"
                                                   class="rounded-xl bg-[#322d2a] px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-black whitespace-nowrap shadow-xs">
                                                    Lihat Hasil & Progres →
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400">Belum mulai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada peserta yang diundang ke asesmen ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- MODAL KONFIRMASI HAPUS ASESMEN --}}
        <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-delete-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative w-full max-w-md transform overflow-hidden rounded-[28px] border border-white bg-white p-6 text-left shadow-2xl transition-all">
                    <div class="flex items-center gap-3.5">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 ring-1 ring-rose-200">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-[#322d2a]" id="modal-delete-title">Hapus Jadwal Asesmen</h3>
                            <p class="text-xs text-[#6f6b67]">Konfirmasi penghapusan data asesmen</p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl bg-rose-50/70 p-3.5 border border-rose-100 text-xs text-rose-900 leading-relaxed">
                        <p class="mb-1">Apakah Anda yakin ingin menghapus asesmen <span class="font-bold text-[#322d2a]">"{{ $assessment->title }}"</span>?</p>
                        @if ($assessment->sessions->count() > 0)
                            <div class="mt-2 text-[11px] text-rose-700">
                                <span class="font-bold">⚠️ Peringatan:</span> Asesmen ini telah memiliki <span class="font-bold underline">{{ $assessment->sessions->count() }} sesi pengerjaan</span> oleh kandidat.<br>
                                <span class="mt-1 block text-rose-800">Sistem memproteksi penghapusan asesmen dengan riwayat sesi untuk menjaga integritas data evaluasi kandidat. Silakan tutup asesmen dengan mengubah status menjadi <b>Closed</b>.</span>
                            </div>
                        @else
                            <div class="mt-2 text-[11px] text-rose-700">
                                Undangan peserta yang belum mulai mengerjakan tes akan otomatis dibatalkan.
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin.disc.assessments.destroy', $assessment) }}" class="mt-5 flex items-center justify-end gap-2.5">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="closeDeleteModal()" class="rounded-full bg-[#f0efe9] px-4 py-2 text-xs font-medium text-[#6f6b67] hover:bg-[#e4e2dc] hover:text-[#322d2a] transition">
                            Batal
                        </button>
                        <button type="submit" {{ $assessment->sessions->count() > 0 ? 'disabled' : '' }} class="rounded-full bg-rose-600 px-5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-rose-700 transition {{ $assessment->sessions->count() > 0 ? 'opacity-50 cursor-not-allowed' : '' }}">
                            Ya, Hapus Asesmen
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openDeleteModal() {
                document.getElementById('deleteModal').classList.remove('hidden');
            }

            function closeDeleteModal() {
                document.getElementById('deleteModal').classList.add('hidden');
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeDeleteModal();
                }
            });
        </script>
    </section>
</x-layouts.app>
