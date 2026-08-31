<x-layouts.app title="{{ $assessment->title }} - Detail Asesmen">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-16 pt-6 sm:pt-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">

            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('admin.disc.assessments.index') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    ← Kembali ke Daftar Asesmen
                </a>

                <div class="flex items-center gap-3">
                    @if ($assessment->status === 'PUBLISHED')
                        <form action="{{ route('admin.disc.assessments.close', $assessment) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menutup asesmen ini? Peserta baru tidak dapat memulai sesi.')">
                            @csrf
                            <button type="submit" class="rounded-full border border-rose-200 bg-rose-50 px-5 py-2.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-100 shadow-sm">
                                ✕ Tutup Asesmen
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.disc.assessments.publish', $assessment) }}" method="POST" class="inline" onsubmit="return confirm('Buka asesmen ini sekarang? Kandidat yang diundang akan dapat langsung mengerjakan tes.')">
                            @csrf
                            <button type="submit" class="rounded-full border border-emerald-300 bg-emerald-50 px-5 py-2.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100 shadow-sm">
                                ✓ Buka Asesmen (Publish)
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.disc.assessments.monitor', $assessment) }}"
                       class="rounded-full border border-blue-200 bg-blue-50 px-5 py-2.5 text-xs font-semibold text-blue-700 transition hover:bg-blue-100 shadow-sm">
                        🛰 Live Monitoring Sesi & Keamanan
                    </a>
                </div>
            </div>

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
                                <th class="px-6 py-4">Nama Peserta</th>
                                <th class="px-6 py-4">Status Sesi</th>
                                <th class="px-6 py-4">Kesempatan Tes (HRD)</th>
                                <th class="px-6 py-4">Profil DISC</th>
                                <th class="px-6 py-4">Indeks Keamanan</th>
                                <th class="px-6 py-4">Waktu Selesai</th>
                                <th class="px-6 py-4 text-right">Laporan & Hasil</th>
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
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-[#322d2a]">{{ $part->user->name ?? '-' }}</div>
                                        <div class="text-xs text-slate-500">{{ $part->user->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($session && $session->status === 'COMPLETED')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> COMPLETED
                                            </span>
                                        @elseif ($session && $session->status === 'IN_PROGRESS')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span> IN PROGRESS
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                                BELUM MULAI
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
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
                                            $waktuTeks = "Batas waktu 5 jam sejak undangan ini dikirim (Waktu pengerjaan soal: 10 menit)";

                                            $waMessage = "Yth. " . $candidateName . ",\n\nSelamat! 🎉\n\nKami dari PT Indodata Solusi Persada menginformasikan bahwa Anda lolos ke tahap Psikotes.\n\nSilakan mengerjakan soal yang telah kami siapkan dengan detail sebagai berikut:\n\n📅 Hari/Tanggal: " . $hariTanggal . "\n⏰ Waktu: " . $waktuTeks . "\n🔗 Link Akses Soal: " . $assessmentUrl . "\n\nMohon mengerjakan soal sesuai dengan waktu dan ketentuan yang telah ditentukan.\n\nApabila terdapat kendala dalam mengakses soal, silakan menghubungi kami.\n\nTerima kasih atas perhatian dan kerja samanya.\n\nHormat kami,\nPT Indodata Solusi Persada";

                                            $waUrl = "https://api.whatsapp.com/send?phone=" . $cleanPhone . "&text=" . rawurlencode($waMessage);

                                            $mailSubject = "Undangan Tahap Psikotes - PT Indodata Solusi Persada";
                                            $mailBody = "Yth. " . $candidateName . ",\n\nSelamat!\n\nKami dari PT Indodata Solusi Persada menginformasikan bahwa Anda lolos ke tahap Psikotes.\n\nSilakan mengerjakan soal yang telah kami siapkan dengan detail sebagai berikut:\n\nHari/Tanggal: " . $hariTanggal . "\nWaktu: " . $waktuTeks . "\nLink Akses Soal: " . $assessmentUrl . "\n\nMohon mengerjakan soal sesuai dengan waktu dan ketentuan yang telah ditentukan.\n\nApabila terdapat kendala dalam mengakses soal, silakan menghubungi kami.\n\nTerima kasih.\n\nHormat kami,\nPT Indodata Solusi Persada";

                                            $gmailUrl = "https://mail.google.com/mail/?view=cm&fs=1&to=" . urlencode($candidateEmail) . "&su=" . rawurlencode($mailSubject) . "&body=" . rawurlencode($mailBody);
                                        @endphp

                                        <div class="flex flex-col gap-2">
                                            <span class="text-xs font-semibold {{ $completedSessionsCount >= $maxAttempts ? 'text-slate-600' : 'text-emerald-700' }}">
                                                {{ $completedSessionsCount }} dari {{ $maxAttempts }} Kesempatan Terpakai
                                            </span>
                                            <form action="{{ route('admin.disc.assessments.participants.attempts', [$assessment, $part]) }}" method="POST" class="flex items-center gap-1.5">
                                                @csrf
                                                @method('PUT')
                                                <div class="flex items-center rounded-xl border border-slate-200 bg-white p-0.5 shadow-xs">
                                                    <input type="number" name="max_attempts" value="{{ $maxAttempts }}" min="1" max="20" class="w-12 border-0 bg-transparent px-2 py-1 text-center font-mono text-xs font-bold text-slate-800 focus:outline-none" title="Ubah batas kesempatan tes">
                                                    <span class="pr-2 text-[10px] text-slate-400 font-medium">kali</span>
                                                </div>
                                                <button type="submit" class="rounded-xl border border-orange-200 bg-orange-50 px-2.5 py-1.5 text-[11px] font-bold text-[#f97316] transition hover:bg-[#f97316] hover:text-white" title="Simpan batas kesempatan">
                                                    Simpan
                                                </button>
                                            </form>

                                            {{-- BROADCAST BUTTONS --}}
                                            <div class="flex items-center gap-1.5 pt-1 border-t border-slate-100">
                                                <span class="text-[10px] uppercase font-bold text-slate-400">Undang:</span>
                                                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                                                   class="inline-flex items-center gap-1 rounded-lg border border-emerald-300 bg-emerald-50 px-2 py-1 text-[11px] font-bold text-emerald-700 transition hover:bg-emerald-600 hover:text-white shadow-xs"
                                                   title="Kirim Undangan Soal via WhatsApp">
                                                    <span>💬 WhatsApp</span>
                                                </a>
                                                <a href="{{ $gmailUrl }}" target="_blank" rel="noopener noreferrer"
                                                   class="inline-flex items-center gap-1 rounded-lg border border-rose-300 bg-rose-50 px-2 py-1 text-[11px] font-bold text-rose-700 transition hover:bg-rose-600 hover:text-white shadow-xs"
                                                   title="Kirim Undangan Soal via Gmail">
                                                    <span>✉️ Gmail</span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($session && $session->disc_profile_code)
                                            <span class="rounded-lg bg-orange-100 px-2.5 py-1 font-mono text-xs font-bold text-[#f97316]">
                                                {{ $session->disc_profile_code }}
                                            </span>
                                            <span class="ml-1 text-xs text-slate-700">{{ $session->disc_profile_name }}</span>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($session)
                                            <span class="font-mono text-xs font-bold {{ $session->security_score >= 80 ? 'text-emerald-600' : 'text-amber-600' }}">
                                                {{ $session->security_score }}/100
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500">
                                        {{ $session?->completed_at ? $session->completed_at->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if ($session && $session->isCompleted())
                                            <div class="inline-flex items-center gap-2">
                                                <a href="{{ route('admin.disc.sessions.export-csv', $session) }}"
                                                   class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                                                   title="Unduh CSV">
                                                    CSV
                                                </a>
                                                <a href="{{ route('admin.disc.results.show', $session) }}"
                                                   class="rounded-xl bg-[#322d2a] px-3.5 py-1.5 text-xs font-semibold text-white transition hover:bg-black">
                                                    Lihat Hasil & Grafik →
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400">Menunggu peserta</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada peserta yang diundang ke asesmen ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</x-layouts.app>
