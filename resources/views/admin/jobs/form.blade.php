@csrf

@php
    $inputClass = 'mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2.5 text-xs font-normal text-[#322d2a] outline-none transition placeholder:text-[#6f6b67]/60 focus:border-[#f97316] focus:bg-white';
    $labelClass = 'text-[10px] font-normal uppercase tracking-[0.14em] text-[#6f6b67]';
    $sectionClass = 'rounded-[24px] sm:rounded-[28px] border border-white bg-white p-5 shadow-sm';
@endphp

<div class="space-y-5">
    @if ($errors->any())
        <div class="rounded-[20px] border border-rose-200 bg-rose-50 p-4 text-xs">
            <p class="font-medium text-rose-700">Ada data yang perlu diperbaiki:</p>
            <ul class="mt-1.5 list-inside list-disc space-y-0.5 text-rose-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-5 lg:grid-cols-[1fr_320px]">
        <div class="space-y-5">
            {{-- 1. INFORMASI POSISI --}}
            <div class="{{ $sectionClass }}">
                <div class="mb-4 flex items-center gap-3 border-b border-[#322d2a]/10 pb-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[12px] bg-[#fff3e8] text-[#f97316] ring-1 ring-[#f97316]/20 text-sm">
                        💼
                    </div>
                    <div>
                        <h2 class="text-base font-medium text-[#322d2a]">Informasi Posisi</h2>
                        <p class="text-[11px] text-[#6f6b67]">Data utama lowongan yang akan tampil pada portal karir.</p>
                    </div>
                </div>

                <div class="grid gap-3.5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="{{ $labelClass }}">Judul Lowongan *</label>
                        <input name="title" value="{{ old('title', $job->title) }}" class="{{ $inputClass }}" placeholder="Contoh: Network Engineer" required>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Departemen</label>
                        <select name="department_id" class="{{ $inputClass }}">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @selected(old('department_id', $job->department_id) == $department->id)>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="rounded-[18px] border border-[#f97316]/20 bg-[#fff8f3] p-3.5">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-[10px] font-medium uppercase tracking-wider text-[#f97316]">Departemen Baru</p>
                            <span class="rounded-full bg-white px-2 py-0.5 text-[9px] font-medium uppercase text-[#f97316] ring-1 ring-[#f97316]/20">Opsional</span>
                        </div>

                        <div class="mt-2 grid gap-2 sm:grid-cols-2">
                            <div>
                                <label class="{{ $labelClass }}">Nama</label>
                                <input name="new_department_name" value="{{ old('new_department_name') }}" class="{{ $inputClass }} bg-white" placeholder="Customer Support">
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Kode</label>
                                <input name="new_department_code" value="{{ old('new_department_code') }}" class="{{ $inputClass }} bg-white" placeholder="CS">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Lokasi Penempatan *</label>
                        <input name="location" value="{{ old('location', $job->location) }}" class="{{ $inputClass }}" placeholder="Contoh: Jakarta Selatan" required>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Tipe Pekerjaan *</label>
                        <select name="employment_type" class="{{ $inputClass }}" required>
                            @foreach(['FULL_TIME' => 'Full Time', 'PART_TIME' => 'Part Time', 'CONTRACT' => 'Contract', 'INTERNSHIP' => 'Internship'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('employment_type', $job->employment_type ?? 'FULL_TIME') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Sistem Kerja *</label>
                        <select name="work_arrangement" class="{{ $inputClass }}" required>
                            @foreach(['ONSITE' => 'Onsite (Kantor)', 'HYBRID' => 'Hybrid', 'REMOTE' => 'Remote (WFH)'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('work_arrangement', $job->work_arrangement ?? 'ONSITE') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Jumlah Kuota Lowongan *</label>
                        <input name="number_of_openings" type="number" min="1" value="{{ old('number_of_openings', $job->number_of_openings ?? 1) }}" class="{{ $inputClass }}" required>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Status Publikasi *</label>
                        <select name="status" class="{{ $inputClass }}" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected(old('status', $job->status ?? 'DRAFT') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 2. KUALIFIKASI --}}
            <div class="{{ $sectionClass }}">
                <div class="mb-4 flex items-center gap-3 border-b border-[#322d2a]/10 pb-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[12px] bg-[#f0efe9] text-[#322d2a] ring-1 ring-[#322d2a]/10 text-sm">
                        🎓
                    </div>
                    <div>
                        <h2 class="text-base font-medium text-[#322d2a]">Kualifikasi Pelamar</h2>
                        <p class="text-[11px] text-[#6f6b67]">Pendidikan, pengalaman kerja, dan keahlian yang disyaratkan.</p>
                    </div>
                </div>

                <div class="grid gap-3.5 md:grid-cols-2">
                    <div>
                        <label class="{{ $labelClass }}">Pendidikan Minimal</label>
                        <input name="minimum_education" value="{{ old('minimum_education', $job->minimum_education) }}" class="{{ $inputClass }}" placeholder="Contoh: D3 / S1 Teknik Informatika">
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Pengalaman Minimal</label>
                        <input name="minimum_experience" value="{{ old('minimum_experience', $job->minimum_experience) }}" class="{{ $inputClass }}" placeholder="Contoh: 1-2 tahun">
                    </div>

                    <div class="md:col-span-2">
                        <label class="{{ $labelClass }}">Keahlian Utama (Skills)</label>
                        <input name="required_skills" value="{{ old('required_skills', is_array($job->required_skills) ? implode(', ', $job->required_skills) : $job->required_skills) }}" class="{{ $inputClass }}" placeholder="Pisahkan dengan koma, contoh: Mikrotik, Fiber Optic, Cisco, Troubleshooting">
                    </div>
                </div>
            </div>

            {{-- 3. DESKRIPSI & PERSYARATAN --}}
            <div class="{{ $sectionClass }}">
                <div class="mb-4 flex items-center gap-3 border-b border-[#322d2a]/10 pb-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[12px] bg-[#f0efe9] text-[#322d2a] ring-1 ring-[#322d2a]/10 text-sm">
                        📝
                    </div>
                    <div>
                        <h2 class="text-base font-medium text-[#322d2a]">Deskripsi & Persyaratan</h2>
                        <p class="text-[11px] text-[#6f6b67]">Rincian tugas, kriteria, dan benefit yang didapatkan kandidat.</p>
                    </div>
                </div>

                <div class="space-y-3.5">
                    <div>
                        <label class="{{ $labelClass }}">Deskripsi Pekerjaan *</label>
                        <textarea name="description" rows="4" class="{{ $inputClass }}" placeholder="Ringkasan tugas dan tujuan posisi pekerjaan..." required>{{ old('description', $job->description) }}</textarea>
                    </div>

                    <div class="grid gap-3.5 md:grid-cols-2">
                        <div>
                            <label class="{{ $labelClass }}">Tanggung Jawab Utama</label>
                            <textarea name="responsibilities" rows="4" class="{{ $inputClass }}" placeholder="Daftar poin tanggung jawab utama...">{{ old('responsibilities', $job->responsibilities) }}</textarea>
                        </div>

                        <div>
                            <label class="{{ $labelClass }}">Persyaratan Kandidat</label>
                            <textarea name="requirements" rows="4" class="{{ $inputClass }}" placeholder="Daftar kriteria dan syarat kualifikasi...">{{ old('requirements', $job->requirements) }}</textarea>
                        </div>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Benefit & Fasilitas</label>
                        <input name="benefits" value="{{ old('benefits', $job->benefits) }}" class="{{ $inputClass }}" placeholder="Contoh: BPJS Ketenagakerjaan & Kesehatan, Tunjangan Transport, Bonus Performa">
                    </div>
                </div>
            </div>
        </div>

        {{-- ASIDE / SIDE PANEL --}}
        <aside class="space-y-5">
            {{-- PERIODE PENDAFTARAN --}}
            <div class="rounded-[24px] sm:rounded-[28px] border border-[#f97316]/20 bg-[#fff8f3] p-5 shadow-sm">
                <div class="flex items-center gap-2.5 border-b border-[#f97316]/20 pb-3">
                    <span class="text-base">📅</span>
                    <div>
                        <h3 class="text-sm font-medium text-[#322d2a]">Periode Pendaftaran</h3>
                        <p class="text-[10px] text-[#6f6b67]">Jadwal buka & tutup lowongan.</p>
                    </div>
                </div>

                <div class="mt-3.5 space-y-3">
                    <div>
                        <label class="{{ $labelClass }}">Tanggal Mulai Dibuka</label>
                        <input name="registration_start_at" type="datetime-local" value="{{ old('registration_start_at', optional($job->registration_start_at)->format('Y-m-d\TH:i')) }}" class="{{ $inputClass }} bg-white">
                        <p class="mt-1 text-[10px] text-[#6f6b67]">Kosongkan bila langsung dibuka saat publish.</p>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Batas Waktu Berakhir (Deadline)</label>
                        <input name="deadline_at" type="datetime-local" value="{{ old('deadline_at', optional($job->deadline_at)->format('Y-m-d\TH:i')) }}" class="{{ $inputClass }} bg-white">
                        <p class="mt-1 text-[10px] text-[#6f6b67]">Setelah tanggal ini lowongan ditutup otomatis.</p>
                    </div>
                </div>
            </div>

            {{-- INFORMASI GAJI --}}
            <div class="{{ $sectionClass }}">
                <div class="flex items-center gap-2.5 border-b border-[#322d2a]/10 pb-3">
                    <span class="text-base">💰</span>
                    <div>
                        <h3 class="text-sm font-medium text-[#322d2a]">Rentang Gaji</h3>
                        <p class="text-[10px] text-[#6f6b67]">Opsional, dapat disembunyikan.</p>
                    </div>
                </div>

                <div class="mt-3.5 space-y-3">
                    <div>
                        <label class="{{ $labelClass }}">Gaji Minimal (Rp)</label>
                        <input name="salary_min" type="number" min="0" value="{{ old('salary_min', $job->salary_min) }}" class="{{ $inputClass }}" placeholder="Contoh: 5000000">
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Gaji Maksimal (Rp)</label>
                        <input name="salary_max" type="number" min="0" value="{{ old('salary_max', $job->salary_max) }}" class="{{ $inputClass }}" placeholder="Contoh: 8000000">
                    </div>

                    <label class="flex items-start gap-2.5 rounded-[14px] bg-[#f0efe9] p-3 text-xs text-[#322d2a] cursor-pointer">
                        <input type="hidden" name="show_salary" value="0">
                        <input type="checkbox" name="show_salary" value="1" @checked(old('show_salary', $job->show_salary)) class="mt-0.5 rounded border-[#322d2a]/20 text-[#f97316] focus:ring-[#f97316]">
                        <span>Tampilkan nominal gaji di halaman karir</span>
                    </label>
                </div>
            </div>

            {{-- TOMBOL SIMPAN (STICKY) --}}
            <div class="sticky top-24 rounded-[24px] sm:rounded-[28px] border border-white bg-white p-5 shadow-sm space-y-2.5">
                <p class="text-xs font-medium text-[#322d2a]">Konfirmasi Simpan</p>
                <p class="text-[11px] text-[#6f6b67]">Pastikan informasi lowongan sudah sesuai.</p>

                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#f97316] px-5 py-2.5 text-xs font-medium text-white shadow-sm transition hover:bg-[#ea580c]">
                    Simpan Lowongan <span>✓</span>
                </button>
                <a href="{{ route('admin.jobs.index') }}" class="inline-flex w-full items-center justify-center rounded-full border border-[#322d2a]/15 bg-[#f0efe9] px-5 py-2 text-xs font-normal text-[#322d2a] transition hover:bg-[#fff3e8] hover:text-[#f97316]">
                    Batal / Kembali
                </a>
            </div>
        </aside>
    </div>
</div>
