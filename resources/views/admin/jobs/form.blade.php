@csrf

@php
    $inputClass = 'mt-2 w-full rounded-[24px] border border-white bg-[#f0efe9] px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition placeholder:text-[#6f6b67]/60 focus:border-[#f97316] focus:bg-white focus:ring-4 focus:ring-[#f97316]/20';
    $labelClass = 'text-xs font-normal uppercase tracking-[0.14em] text-[#6f6b67]';
    $sectionClass = 'rounded-[40px] border border-white bg-white p-5 ring-1 ring-[#322d2a]/5';
@endphp

<div class="space-y-6">
    @if ($errors->any())
        <div class="rounded-[28px] border border-[#f97316]/20 bg-[#fff3e8] p-5">
            <p class="font-normal text-[#f97316]">Ada data yang perlu diperbaiki.</p>
            <ul class="mt-3 list-inside list-disc space-y-1 text-sm font-normal text-[#322d2a]">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1fr_340px]">
        <div class="space-y-6">
            <div class="{{ $sectionClass }}">
                <div class="mb-5 flex items-start gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[16px] bg-[#fff3e8] text-[#f97316] ring-1 ring-[#f97316]/20">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 7h16"></path>
                            <path d="M7 7V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"></path>
                            <rect x="4" y="7" width="16" height="13" rx="2"></rect>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-normal text-[#322d2a]">Informasi Posisi</h2>
                        <p class="mt-1 text-sm font-normal text-[#6f6b67]">Data utama yang akan tampil di halaman career.</p>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="{{ $labelClass }}">Judul Lowongan</label>
                        <input name="title" value="{{ old('title', $job->title) }}" class="{{ $inputClass }}" placeholder="Contoh: Network Engineer" required>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Departemen</label>
                        <select name="department_id" class="{{ $inputClass }}">
                            <option value="">Pilih departemen</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @selected(old('department_id', $job->department_id) == $department->id)>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="rounded-[28px] border border-[#f97316]/20 bg-[#fff3e8] p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-normal uppercase tracking-[0.14em] text-[#f97316]">Departemen Baru</p>
                                <p class="mt-1 text-xs font-normal leading-5 text-[#6f6b67]">Isi bagian ini jika departemen belum ada di pilihan.</p>
                            </div>
                            <span class="rounded-full bg-white px-2.5 py-1 text-[10px] font-normal uppercase tracking-wide text-[#f97316] ring-1 ring-[#f97316]/20">Opsional</span>
                        </div>

                        <div class="mt-3 grid gap-3 sm:grid-cols-2 md:grid-cols-1 xl:grid-cols-2">
                            <div>
                                <label class="{{ $labelClass }}">Nama</label>
                                <input name="new_department_name" value="{{ old('new_department_name') }}" class="{{ $inputClass }} bg-white" placeholder="Contoh: Customer Support">
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Kode</label>
                                <input name="new_department_code" value="{{ old('new_department_code') }}" class="{{ $inputClass }} bg-white" placeholder="Contoh: CS">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Lokasi</label>
                        <input name="location" value="{{ old('location', $job->location) }}" class="{{ $inputClass }}" placeholder="Contoh: Jakarta" required>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Tipe Kerja</label>
                        <select name="employment_type" class="{{ $inputClass }}" required>
                            @foreach(['FULL_TIME' => 'Full Time', 'PART_TIME' => 'Part Time', 'CONTRACT' => 'Contract', 'INTERNSHIP' => 'Internship'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('employment_type', $job->employment_type ?? 'FULL_TIME') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Sistem Kerja</label>
                        <select name="work_arrangement" class="{{ $inputClass }}" required>
                            @foreach(['ONSITE' => 'Onsite', 'HYBRID' => 'Hybrid', 'REMOTE' => 'Remote'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('work_arrangement', $job->work_arrangement ?? 'ONSITE') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Jumlah Dibutuhkan</label>
                        <input name="number_of_openings" type="number" min="1" value="{{ old('number_of_openings', $job->number_of_openings ?? 1) }}" class="{{ $inputClass }}" required>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Status</label>
                        <select name="status" class="{{ $inputClass }}" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected(old('status', $job->status ?? 'DRAFT') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="{{ $sectionClass }}">
                <div class="mb-5 flex items-start gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[16px] bg-[#f0efe9] text-[#322d2a] ring-1 ring-[#322d2a]/10">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-normal text-[#322d2a]">Kualifikasi Singkat</h2>
                        <p class="mt-1 text-sm font-normal text-[#6f6b67]">Isi pendidikan, pengalaman, dan skill utama.</p>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="{{ $labelClass }}">Pendidikan Minimal</label>
                        <input name="minimum_education" value="{{ old('minimum_education', $job->minimum_education) }}" class="{{ $inputClass }}" placeholder="Contoh: S1 Teknik Informatika">
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Pengalaman Minimal</label>
                        <input name="minimum_experience" value="{{ old('minimum_experience', $job->minimum_experience) }}" class="{{ $inputClass }}" placeholder="Contoh: 2 tahun">
                    </div>

                    <div class="md:col-span-2">
                        <label class="{{ $labelClass }}">Skill</label>
                        <input name="required_skills" value="{{ old('required_skills', is_array($job->required_skills) ? implode(', ', $job->required_skills) : $job->required_skills) }}" class="{{ $inputClass }}" placeholder="Pisahkan dengan koma, contoh: Mikrotik, Fiber Optic, Troubleshooting">
                    </div>
                </div>
            </div>

            <div class="{{ $sectionClass }}">
                <div class="mb-5 flex items-start gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[16px] bg-[#f0efe9] text-[#322d2a] ring-1 ring-[#322d2a]/10">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-normal text-[#322d2a]">Deskripsi Lowongan</h2>
                        <p class="mt-1 text-sm font-normal text-[#6f6b67]">Tulis informasi yang mudah dipahami kandidat.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="{{ $labelClass }}">Deskripsi</label>
                        <textarea name="description" rows="5" class="{{ $inputClass }}" placeholder="Ringkasan posisi dan tujuan pekerjaan" required>{{ old('description', $job->description) }}</textarea>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="{{ $labelClass }}">Tanggung Jawab</label>
                            <textarea name="responsibilities" rows="5" class="{{ $inputClass }}" placeholder="Tuliskan tanggung jawab utama">{{ old('responsibilities', $job->responsibilities) }}</textarea>
                        </div>

                        <div>
                            <label class="{{ $labelClass }}">Persyaratan</label>
                            <textarea name="requirements" rows="5" class="{{ $inputClass }}" placeholder="Tuliskan persyaratan kandidat">{{ old('requirements', $job->requirements) }}</textarea>
                        </div>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Benefit</label>
                        <input name="benefits" value="{{ old('benefits', $job->benefits) }}" class="{{ $inputClass }}" placeholder="Contoh: BPJS, bonus performa, pelatihan">
                    </div>
                </div>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="rounded-[40px] border border-[#f97316]/20 bg-[#fff3e8] p-5">
                <div class="flex items-start gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[16px] bg-white text-[#f97316] ring-1 ring-[#f97316]/20">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-normal text-[#322d2a]">Periode Pendaftaran</h2>
                        <p class="mt-1 text-sm font-normal leading-6 text-[#6f6b67]">Atur kapan lowongan mulai dibuka dan kapan otomatis ditutup.</p>
                    </div>
                </div>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="{{ $labelClass }}">Mulai</label>
                        <input name="registration_start_at" type="datetime-local" value="{{ old('registration_start_at', optional($job->registration_start_at)->format('Y-m-d\TH:i')) }}" class="{{ $inputClass }} bg-white">
                        <p class="mt-2 text-xs font-normal text-[#6f6b67]">Kosongkan jika langsung dibuka saat publish.</p>
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Berakhir</label>
                        <input name="deadline_at" type="datetime-local" value="{{ old('deadline_at', optional($job->deadline_at)->format('Y-m-d\TH:i')) }}" class="{{ $inputClass }} bg-white">
                        <p class="mt-2 text-xs font-normal text-[#6f6b67]">Setelah tanggal ini kandidat tidak bisa melamar.</p>
                    </div>
                </div>
            </div>

            <div class="{{ $sectionClass }}">
                <h2 class="text-xl font-normal text-[#322d2a]">Informasi Gaji</h2>
                <p class="mt-1 text-sm font-normal text-[#6f6b67]">Opsional, dapat disembunyikan dari kandidat.</p>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="{{ $labelClass }}">Gaji Minimal</label>
                        <input name="salary_min" type="number" min="0" value="{{ old('salary_min', $job->salary_min) }}" class="{{ $inputClass }}" placeholder="0">
                    </div>

                    <div>
                        <label class="{{ $labelClass }}">Gaji Maksimal</label>
                        <input name="salary_max" type="number" min="0" value="{{ old('salary_max', $job->salary_max) }}" class="{{ $inputClass }}" placeholder="0">
                    </div>

                    <label class="flex items-start gap-3 rounded-[24px] bg-[#f0efe9] p-3 text-sm font-normal text-[#322d2a] ring-1 ring-[#322d2a]/10">
                        <input type="hidden" name="show_salary" value="0">
                        <input type="checkbox" name="show_salary" value="1" @checked(old('show_salary', $job->show_salary)) class="mt-1 rounded border-[#322d2a]/20 text-[#f97316] focus:ring-[#f97316]">
                        <span>Tampilkan gaji di halaman lowongan</span>
                    </label>
                </div>
            </div>

            <div class="sticky top-28 rounded-[40px] border border-white bg-white p-5 ring-1 ring-[#322d2a]/5">
                <p class="text-sm font-normal text-[#322d2a]">Simpan perubahan</p>
                <p class="mt-1 text-xs font-normal leading-5 text-[#6f6b67]">Pastikan data posisi dan periode pendaftaran sudah benar.</p>

                <div class="mt-5 grid gap-3">
                    <button class="rounded-full bg-[#f97316] px-5 py-3 text-sm font-normal text-white transition hover:bg-[#ea580c]">Simpan Lowongan</button>
                    <a href="{{ route('admin.jobs.index') }}" class="rounded-full border border-[#322d2a]/10 bg-[#f0efe9] px-5 py-3 text-center text-sm font-normal text-[#322d2a] transition hover:border-[#f97316]/30 hover:bg-[#fff3e8] hover:text-[#f97316]">Kembali</a>
                </div>
            </div>
        </aside>
    </div>
</div>
