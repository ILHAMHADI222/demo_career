<x-layouts.app title="Profil Kandidat - Career Indodata">
    <section class="bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">
            <div class="rounded-[40px] border border-white bg-white p-6 sm:p-7 lg:p-8">
                <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                    <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                    Candidate Onboarding
                </div>
                <h1 class="mt-4 max-w-3xl text-[36px] font-light leading-none tracking-[0.4px] text-[#322d2a] md:text-[46px]">Data Profil Recruitment</h1>
                <p class="mt-3 max-w-3xl text-sm font-normal leading-[1.6] tracking-[0.14px] text-[#6f6b67]">Kelola data profil, riwayat pendidikan, dan pengalaman kerja Anda untuk proses recruitment.</p>
            </div>

            @php
                $profileStepDone = $profile->profile_completion >= 100;
                $education = $profile->educations->first();
                $experiences = $profile->experiences;
                // If profile is 100% complete and no validation errors/edit query, show preview mode by default
                $isEditing = request()->has('edit') || $errors->any() || !$profileStepDone;
            @endphp

            @if (session('success'))
                <div class="mt-6 rounded-[24px] border border-[#f97316]/30 bg-[#fff3e8] p-4 text-sm font-normal text-[#322d2a]">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 rounded-[24px] border border-[#f97316]/40 bg-[#fff3e8] p-4 text-sm font-normal text-[#322d2a]">
                    Mohon lengkapi seluruh field wajib pada formulir profil kandidat.
                </div>
            @endif

            {{-- 1. PREVIEW / RINGKASAN RECRUITMENT (Tampil saat data sudah lengkap) --}}
            <div id="previewSection" class="{{ $isEditing ? 'hidden' : '' }} mt-5 space-y-5">
                <div class="rounded-[32px] border border-white bg-white p-5 sm:p-6 lg:p-7">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-[#322d2a]/10 pb-4">
                        <div>
                            <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                <span class="h-1.5 w-1.5 bg-[#f97316]"></span>Preview Output
                            </div>
                            <h2 class="mt-2 text-[26px] font-light leading-none tracking-[0.28px] text-[#322d2a]">Ringkasan Recruitment</h2>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5">
                            <span class="rounded-full bg-[#fff3e8] px-3.5 py-1.5 text-xs font-medium text-[#f97316] ring-1 ring-[#f97316]/20">
                                ✓ Profil 100% Lengkap
                            </span>
                            <button type="button" id="btnOpenEdit" class="inline-flex items-center gap-1.5 rounded-full bg-[#f97316] px-4 py-2 text-xs font-normal text-white shadow-sm transition hover:bg-[#ea580c]">
                                <span>✏️ Edit Data Profil</span>
                            </button>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-6 lg:grid-cols-[200px_1fr]">
                        {{-- Pass Foto Box --}}
                        <div class="flex flex-col items-center sm:items-start">
                            <div class="h-52 w-40 overflow-hidden rounded-[20px] border border-[#322d2a]/10 bg-[#f0efe9] shadow-sm">
                                @if($profile->photo_url)
                                    <img src="{{ $profile->photo_url }}" alt="Pass Foto {{ $profile->full_name }}" class="h-full w-full object-cover" onerror="this.onerror=null; this.src='{{ $profile->avatar_url }}';">
                                @else
                                    <img src="{{ $profile->avatar_url }}" alt="Avatar {{ $profile->full_name }}" class="h-full w-full object-cover">
                                @endif
                            </div>
                            <span class="mt-2 text-center sm:text-left text-[11px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Pass Foto Resmi</span>
                        </div>

                        {{-- Details Grid --}}
                        <div class="space-y-4">
                            {{-- Section 1: Data Diri --}}
                            <div class="rounded-[22px] border border-[#322d2a]/10 bg-[#f0efe9] p-4 sm:p-5">
                                <h3 class="text-xs font-normal uppercase tracking-[0.12em] text-[#f97316]">Data Diri & Kontak</h3>
                                <div class="mt-3 grid gap-3 sm:grid-cols-2 xl:grid-cols-3 text-xs sm:text-sm">
                                    <div>
                                        <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">Nama Lengkap</p>
                                        <p class="mt-0.5 font-medium text-[#322d2a]">{{ $profile->full_name ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">NIK</p>
                                        <p class="mt-0.5 font-medium text-[#322d2a]">{{ $profile->nik ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">Gender</p>
                                        <p class="mt-0.5 font-medium text-[#322d2a]">{{ $profile->gender ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">Tempat / Tanggal Lahir</p>
                                        <p class="mt-0.5 font-medium text-[#322d2a]">{{ $profile->birth_place ?: '-' }} / {{ $profile->birth_date?->format('d-m-Y') ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">Status Pernikahan</p>
                                        <p class="mt-0.5 font-medium text-[#322d2a]">{{ $profile->marital_status ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">SIM</p>
                                        <p class="mt-0.5 font-medium text-[#322d2a]">{{ $profile->driving_license ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">Email</p>
                                        <p class="mt-0.5 font-medium text-[#322d2a] break-all">{{ $profile->email ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">Telepon / No WA</p>
                                        <p class="mt-0.5 font-medium text-[#322d2a]">{{ $profile->phone ?: '-' }} / {{ $profile->whatsapp ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">Kode Pos</p>
                                        <p class="mt-0.5 font-medium text-[#322d2a]">{{ $profile->postal_code ?: '-' }}</p>
                                    </div>
                                    <div class="sm:col-span-2 xl:col-span-3">
                                        <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">Alamat Lengkap</p>
                                        <p class="mt-0.5 font-medium text-[#322d2a] leading-relaxed">{{ $profile->address ?: '-' }} (RT/RW: {{ $profile->rt_rw ?: '-' }}, Kel: {{ $profile->village ?: '-' }}, Kec: {{ $profile->district ?: '-' }}, {{ $profile->city ?: '-' }}, {{ $profile->province ?: '-' }})</p>
                                    </div>
                                    @if($profile->linkedin_url)
                                        <div class="sm:col-span-2">
                                            <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">LinkedIn</p>
                                            <a href="{{ $profile->linkedin_url }}" target="_blank" class="mt-0.5 block text-orange-600 hover:underline break-all">{{ $profile->linkedin_url }}</a>
                                        </div>
                                    @endif
                                    @if($profile->portfolio_url)
                                        <div class="sm:col-span-2">
                                            <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">Portfolio</p>
                                            <a href="{{ $profile->portfolio_url }}" target="_blank" class="mt-0.5 block text-orange-600 hover:underline break-all">{{ $profile->portfolio_url }}</a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Section 2: Riwayat Pendidikan --}}
                            <div class="rounded-[22px] border border-[#322d2a]/10 bg-[#f0efe9] p-4 sm:p-5">
                                <h3 class="text-xs font-normal uppercase tracking-[0.12em] text-[#f97316]">Riwayat Pendidikan Terakhir</h3>
                                <div class="mt-3 grid gap-3 sm:grid-cols-2 xl:grid-cols-4 text-xs sm:text-sm">
                                    <div>
                                        <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">Jenjang</p>
                                        <p class="mt-0.5 font-medium text-[#322d2a]">{{ $education?->education_level ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">Institusi / Sekolah</p>
                                        <p class="mt-0.5 font-medium text-[#322d2a]">{{ $education?->institution ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">Jurusan</p>
                                        <p class="mt-0.5 font-medium text-[#322d2a]">{{ $education?->major ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">IPK / Nilai Rata2</p>
                                        <p class="mt-0.5 font-medium text-[#322d2a]">{{ $education?->ipk ?: '-' }}</p>
                                    </div>
                                    <div class="sm:col-span-2 xl:col-span-4">
                                        <p class="text-[10px] uppercase text-[#6f6b67] tracking-[0.1em]">Keahlian / Sertifikasi</p>
                                        <p class="mt-0.5 font-medium text-[#322d2a] whitespace-pre-line">{{ $education?->certification ?: '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Section 3: Riwayat Pekerjaan --}}
                            <div class="rounded-[22px] border border-[#322d2a]/10 bg-[#f0efe9] p-4 sm:p-5">
                                <h3 class="text-xs font-normal uppercase tracking-[0.12em] text-[#f97316]">Riwayat Pekerjaan</h3>
                                @if($experiences->count() > 0)
                                    <div class="mt-3 space-y-2.5">
                                        @foreach($experiences as $exp)
                                            <div class="rounded-[16px] border border-[#322d2a]/10 bg-white p-3.5 text-xs sm:text-sm">
                                                <div class="flex flex-wrap items-center justify-between gap-2">
                                                    <p class="font-medium text-[#322d2a]">{{ $exp->company_name ?: '-' }}</p>
                                                    <span class="rounded-full bg-[#f0efe9] px-2.5 py-0.5 text-[11px] text-[#6f6b67]">{{ $exp->work_years ?: '-' }}</span>
                                                </div>
                                                <p class="mt-1 text-xs text-[#6f6b67]">Jabatan: <span class="text-[#322d2a] font-medium">{{ $exp->position ?: '-' }}</span></p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="mt-2 text-xs text-[#6f6b67]">Belum ada riwayat pekerjaan yang ditambahkan (Opsional).</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Action Footer --}}
                    <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-[#322d2a]/10 pt-4">
                        <button type="button" id="btnOpenEditBottom" class="inline-flex items-center gap-2 rounded-full bg-[#f97316] px-5 py-2.5 text-xs font-normal text-white shadow-sm transition hover:bg-[#ea580c]">
                            <span>✏️ Edit Data Profil</span>
                        </button>
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-1.5 rounded-full border border-[#322d2a]/20 bg-transparent px-5 py-2.5 text-xs font-normal text-[#322d2a] transition hover:bg-[#f0efe9]">
                            <span>Lihat Lowongan Kerja →</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- 2. FORM PENGISIAN PROFIL (Tampil penuh tanpa kolom preview/info samping) --}}
            <div id="formSection" class="{{ !$isEditing ? 'hidden' : '' }} mt-5">
                <form method="POST" action="{{ route('candidate.profile.update') }}" enctype="multipart/form-data" class="rounded-[32px] border border-white bg-white p-5 sm:p-6 lg:p-7">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-[#322d2a]/10 pb-4">
                        <div>
                            <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                                <span class="h-1.5 w-1.5 bg-[#f97316]"></span>Formulir Profil
                            </div>
                            <h2 class="mt-2 text-[26px] font-light leading-none tracking-[0.28px] text-[#322d2a]">Form Recruitment Kandidat</h2>
                            <p class="mt-1.5 text-xs text-[#6f6b67]">Field opsional: Kode Pos, SIM, LinkedIn, Portfolio, dan Riwayat Pekerjaan.</p>
                        </div>
                        @if($profileStepDone)
                            <button type="button" id="btnCancelEdit" class="inline-flex items-center gap-1.5 rounded-full border border-[#322d2a]/20 bg-transparent px-4 py-2 text-xs font-normal text-[#322d2a] transition hover:bg-[#f0efe9]">
                                <span>← Kembali ke Ringkasan</span>
                            </button>
                        @endif
                    </div>

                    {{-- Pass Foto Section --}}
                    <div class="mt-5 rounded-[24px] border border-[#f97316]/20 bg-[#fff3e8] p-4 sm:p-5">
                        <div>
                            <span class="text-xs sm:text-sm font-normal tracking-[0.14px] text-[#322d2a]">Pass Foto <span class="text-[#f97316]">*</span></span>
                            <div class="mt-3 flex flex-col gap-4 md:flex-row md:items-center">
                                <div class="h-28 w-24 shrink-0 overflow-hidden rounded-[18px] border border-white bg-white shadow-sm flex items-center justify-center">
                                    <img id="photoPreview" src="{{ $profile->photo_url ?? $profile->avatar_url }}" alt="Preview pass foto" class="h-full w-full object-cover" onerror="this.onerror=null; this.src='{{ $profile->avatar_url }}';">
                                    <div id="photoEmpty" class="hidden flex h-full w-full items-center justify-center text-center text-[11px] font-normal text-[#6f6b67]">Belum ada foto</div>
                                </div>
                                <div class="flex-1 space-y-2.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <label class="cursor-pointer inline-flex items-center gap-1.5 rounded-full bg-[#f97316] px-3.5 py-2 text-xs font-normal text-white shadow-sm transition hover:bg-[#ea580c]">
                                            <span>📁 Pilih File Foto</span>
                                            <input id="photoInput" type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="hidden">
                                        </label>
                                        <button type="button" id="openCameraButton" class="inline-flex items-center gap-1.5 rounded-full bg-[#322d2a] px-3.5 py-2 text-xs font-normal text-white transition hover:bg-[#0f0e12]">
                                            <span>📷 Buka Kamera</span>
                                        </button>
                                        <button type="button" id="capturePhotoButton" class="hidden rounded-full bg-[#f97316] px-3.5 py-2 text-xs font-normal text-white transition hover:bg-[#ea580c]">Ambil Foto</button>
                                        <button type="button" id="closeCameraButton" class="hidden rounded-full border border-[#322d2a]/20 bg-white px-3.5 py-2 text-xs font-normal text-[#322d2a] transition hover:border-[#f97316] hover:text-[#f97316]">Tutup Kamera</button>
                                    </div>
                                    <input id="photoCapture" type="hidden" name="photo_capture" value="">
                                    <video id="cameraVideo" autoplay playsinline class="hidden max-h-48 w-full rounded-[18px] bg-[#0f0e12]"></video>
                                    <canvas id="cameraCanvas" class="hidden"></canvas>
                                    <p class="text-[11px] font-normal leading-[1.5] text-[#6f6b67]">Gunakan foto formal. Anda dapat mengunggah file dari komputer/HP atau mengambil foto langsung dari kamera.</p>
                                    @error('photo')<p class="text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Data Diri / Recruitment Section --}}
                    <div class="mt-5 rounded-[24px] border border-[#322d2a]/10 bg-[#f0efe9] p-4 sm:p-5">
                        <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]"><span class="h-1.5 w-1.5 bg-[#f97316]"></span>Data Diri & Alamat</p>
                        <div class="mt-3.5 grid gap-3.5 md:grid-cols-2 xl:grid-cols-3">
                            @foreach([
                                ['full_name', 'Nama Kandidat', 'text', true],
                                ['nik', 'NIK (16 digit)', 'text', true],
                                ['birth_place', 'Tempat Lahir', 'text', true],
                                ['birth_date', 'Tanggal Lahir', 'date', true],
                                ['phone', 'No. Telepon', 'text', true],
                                ['whatsapp', 'No WA Aktif', 'text', true],
                                ['email', 'Email', 'email', true],
                                ['rt_rw', 'RT / RW', 'text', true],
                                ['village', 'Kel/Desa', 'text', true],
                                ['district', 'Kecamatan', 'text', true],
                                ['city', 'Kab/Kota', 'text', true],
                                ['province', 'Provinsi', 'text', true],
                                ['postal_code', 'Kode Pos (Opsional)', 'text', false],
                                ['linkedin_url', 'LinkedIn URL', 'url', false],
                                ['portfolio_url', 'Portfolio URL', 'url', false],
                            ] as [$field, $label, $type, $isRequired])
                                <label class="block {{ in_array($field, ['address']) ? 'md:col-span-2 xl:col-span-3' : '' }}">
                                    <span class="text-xs font-normal tracking-[0.12em] text-[#6f6b67] uppercase">{{ $label }} @if($isRequired)<span class="text-[#f97316]">*</span>@endif</span>
                                    <input type="{{ $type }}" name="{{ $field }}" value="{{ old($field, optional($profile->{$field})->format('Y-m-d') ?? $profile->{$field}) }}" @if($isRequired) required @endif class="mt-1.5 w-full {{ $type === 'text' ? 'uppercase' : '' }} rounded-[14px] border @error($field) border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-3.5 py-2.5 text-sm font-normal text-[#322d2a] outline-none transition placeholder:text-[#6f6b67]/60 focus:border-[#f97316] focus:bg-white">
                                    @error($field)<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror
                                </label>
                            @endforeach

                            <label class="block">
                                <span class="text-xs font-normal tracking-[0.12em] text-[#6f6b67] uppercase">Gender <span class="text-[#f97316]">*</span></span>
                                <select name="gender" required class="mt-1.5 w-full rounded-[14px] border @error('gender') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-3.5 py-2.5 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                                    <option value="">Pilih Gender</option>
                                    @foreach(['Laki-laki', 'Perempuan'] as $gender)
                                        <option value="{{ $gender }}" @selected(old('gender', $profile->gender) === $gender)>{{ $gender }}</option>
                                    @endforeach
                                </select>
                                @error('gender')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror
                            </label>

                            <label class="block">
                                <span class="text-xs font-normal tracking-[0.12em] text-[#6f6b67] uppercase">Status Pernikahan <span class="text-[#f97316]">*</span></span>
                                <select name="marital_status" required class="mt-1.5 w-full rounded-[14px] border @error('marital_status') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-3.5 py-2.5 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                                    <option value="">Pilih Status Pernikahan</option>
                                    @foreach(['Belum Menikah', 'Menikah', 'Cerai Hidup', 'Cerai Mati'] as $status)
                                        <option value="{{ $status }}" @selected(old('marital_status', $profile->marital_status) === $status)>{{ $status }}</option>
                                    @endforeach
                                </select>
                                @error('marital_status')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror
                            </label>

                            <label class="block">
                                <span class="text-xs font-normal tracking-[0.12em] text-[#6f6b67] uppercase">SIM (Opsional)</span>
                                <select name="driving_license" class="mt-1.5 w-full rounded-[14px] border @error('driving_license') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-3.5 py-2.5 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                                    <option value="">Pilih SIM (Opsional)</option>
                                    @foreach(['A', 'B1', 'B2', 'C', 'Tidak Ada'] as $license)
                                        <option value="{{ $license }}" @selected(old('driving_license', $profile->driving_license) === $license)>{{ $license }}</option>
                                    @endforeach
                                </select>
                                @error('driving_license')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror
                            </label>

                            <label class="block md:col-span-2 xl:col-span-3">
                                <span class="text-xs font-normal tracking-[0.12em] text-[#6f6b67] uppercase">Alamat <span class="text-[#f97316]">*</span></span>
                                <textarea name="address" rows="3" required class="mt-1.5 w-full uppercase rounded-[14px] border @error('address') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-3.5 py-2.5 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">{{ old('address', $profile->address) }}</textarea>
                                @error('address')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror
                            </label>
                        </div>
                    </div>

                    {{-- Riwayat Pendidikan Section --}}
                    <div class="mt-5 rounded-[24px] border border-[#322d2a]/10 bg-[#f0efe9] p-4 sm:p-5">
                        <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]"><span class="h-1.5 w-1.5 bg-[#f97316]"></span>Riwayat Pendidikan Terakhir</p>
                        <div class="mt-3.5 grid gap-3.5 md:grid-cols-2 xl:grid-cols-5">
                            <label class="block"><span class="text-xs font-normal tracking-[0.12em] text-[#6f6b67] uppercase">Jenjang <span class="text-[#f97316]">*</span></span><select name="education_level" required class="mt-1.5 w-full rounded-[14px] border @error('education_level') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-3.5 py-2.5 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"><option value="">Pilih</option>@foreach(['SMA/SMK', 'D3', 'D4', 'S1', 'S2', 'S3'] as $level)<option value="{{ $level }}" @selected(old('education_level', $education?->education_level) === $level)>{{ $level }}</option>@endforeach</select>@error('education_level')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror</label>
                            <label class="block xl:col-span-2"><span class="text-xs font-normal tracking-[0.12em] text-[#6f6b67] uppercase">Nama Sekolah / Universitas <span class="text-[#f97316]">*</span></span><input type="text" name="institution" value="{{ old('institution', $education?->institution) }}" required class="mt-1.5 w-full uppercase rounded-[14px] border @error('institution') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-3.5 py-2.5 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">@error('institution')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror</label>
                            <label class="block xl:col-span-2"><span class="text-xs font-normal tracking-[0.12em] text-[#6f6b67] uppercase">Jurusan <span class="text-[#f97316]">*</span></span><input type="text" name="major" value="{{ old('major', $education?->major) }}" required class="mt-1.5 w-full uppercase rounded-[14px] border @error('major') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-3.5 py-2.5 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">@error('major')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror</label>
                            <label class="block"><span class="text-xs font-normal tracking-[0.12em] text-[#6f6b67] uppercase">IPK / Rata2 <span class="text-[#f97316]">*</span></span><input type="text" name="ipk" value="{{ old('ipk', $education?->ipk ?? $education?->gpa) }}" required class="mt-1.5 w-full uppercase rounded-[14px] border @error('ipk') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-3.5 py-2.5 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">@error('ipk')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror</label>
                            <label class="block xl:col-span-4"><span class="text-xs font-normal tracking-[0.12em] text-[#6f6b67] uppercase">Keahlian / Sertifikasi (Opsional)</span><textarea name="certification" rows="2" class="mt-1.5 w-full uppercase rounded-[14px] border @error('certification') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-3.5 py-2.5 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">{{ old('certification', $education?->certification) }}</textarea>@error('certification')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror</label>
                        </div>
                    </div>

                    {{-- Riwayat Pekerjaan Section (Opsional & Multi-Item) --}}
                    <div class="mt-5 rounded-[24px] border border-[#322d2a]/10 bg-[#f0efe9] p-4 sm:p-5">
                        <div class="flex flex-wrap items-center justify-between gap-2.5">
                            <div>
                                <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]"><span class="h-1.5 w-1.5 bg-[#f97316]"></span>Riwayat Pekerjaan</p>
                                <span class="ml-2 rounded-full bg-[#322d2a]/10 px-2 py-0.5 text-[10px] font-normal text-[#6f6b67]">Opsional</span>
                            </div>
                            <button type="button" id="addExperienceBtn" class="inline-flex items-center gap-1.5 rounded-full bg-[#f97316] px-3.5 py-1.5 text-xs font-normal text-white shadow-sm transition hover:bg-[#ea580c]">
                                <span>+ Tambah Pekerjaan</span>
                            </button>
                        </div>

                        <div id="experiencesContainer" class="space-y-3 mt-3.5">
                            @php
                                $displayExperiences = old('experiences', $experiences->count() > 0 ? $experiences->toArray() : [['company_name' => '', 'position' => '', 'work_years' => '']]);
                            @endphp

                            @foreach($displayExperiences as $index => $exp)
                                <div class="experience-row relative rounded-[18px] border border-[#322d2a]/15 bg-white p-3.5">
                                    <div class="flex items-center justify-between border-b border-[#322d2a]/10 pb-2 mb-2.5">
                                        <span class="experience-index text-[11px] font-medium uppercase tracking-[0.1em] text-[#f97316]">Pekerjaan #{{ $loop->iteration }}</span>
                                        <button type="button" class="remove-exp-btn text-xs text-red-500 hover:text-red-700 font-normal transition">Hapus</button>
                                    </div>
                                    <div class="grid gap-3 md:grid-cols-3">
                                        <label class="block">
                                            <span class="text-[10px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Nama Perusahaan</span>
                                            <input type="text" name="experiences[{{ $index }}][company_name]" value="{{ $exp['company_name'] ?? '' }}" placeholder="PT Contoh Nama" class="mt-1 w-full uppercase rounded-[12px] border border-[#322d2a]/15 bg-[#f0efe9] px-3 py-2 text-xs sm:text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                                        </label>
                                        <label class="block">
                                            <span class="text-[10px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Jabatan</span>
                                            <input type="text" name="experiences[{{ $index }}][position]" value="{{ $exp['position'] ?? '' }}" placeholder="Staff / Supervisor" class="mt-1 w-full uppercase rounded-[12px] border border-[#322d2a]/15 bg-[#f0efe9] px-3 py-2 text-xs sm:text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                                        </label>
                                        <label class="block">
                                            <span class="text-[10px] font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Tahun Bekerja</span>
                                            <input type="text" name="experiences[{{ $index }}][work_years]" value="{{ $exp['work_years'] ?? '' }}" placeholder="2023 - 2025" class="mt-1 w-full uppercase rounded-[12px] border border-[#322d2a]/15 bg-[#f0efe9] px-3 py-2 text-xs sm:text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-[#322d2a]/10 pt-4">
                        <button type="submit" class="rounded-full bg-[#f97316] px-6 py-3 text-xs font-normal tracking-[0.14px] text-white shadow-sm transition hover:bg-[#ea580c]">Simpan Data Recruitment</button>
                        @if($profileStepDone)
                            <button type="button" id="btnCancelEditBottom" class="rounded-full border border-[#322d2a]/30 bg-transparent px-5 py-3 text-xs font-normal tracking-[0.14px] text-[#322d2a] transition hover:bg-[#f0efe9]">Batal</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        // Toggle view between Summary and Form
        const previewSection = document.getElementById('previewSection');
        const formSection = document.getElementById('formSection');
        const btnOpenEdit = document.getElementById('btnOpenEdit');
        const btnOpenEditBottom = document.getElementById('btnOpenEditBottom');
        const btnCancelEdit = document.getElementById('btnCancelEdit');
        const btnCancelEditBottom = document.getElementById('btnCancelEditBottom');

        function showForm() {
            if (previewSection) previewSection.classList.add('hidden');
            if (formSection) {
                formSection.classList.remove('hidden');
                formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function showPreview() {
            if (formSection) formSection.classList.add('hidden');
            if (previewSection) {
                previewSection.classList.remove('hidden');
                previewSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        btnOpenEdit?.addEventListener('click', showForm);
        btnOpenEditBottom?.addEventListener('click', showForm);
        btnCancelEdit?.addEventListener('click', showPreview);
        btnCancelEditBottom?.addEventListener('click', showPreview);

        // Auto Uppercase Logic
        function bindAutoUppercase() {
            document.querySelectorAll('input[type="text"], textarea').forEach(el => {
                if (el.dataset.uppercaseBound) return;
                el.dataset.uppercaseBound = 'true';
                el.addEventListener('input', () => {
                    const start = el.selectionStart;
                    const end = el.selectionEnd;
                    el.value = el.value.toUpperCase();
                    if (start !== null && end !== null) {
                        try {
                            el.setSelectionRange(start, end);
                        } catch (err) {}
                    }
                });
            });
        }
        bindAutoUppercase();

        // Photo Upload & Camera Logic
        const photoInput = document.getElementById('photoInput');
        const photoCapture = document.getElementById('photoCapture');
        const photoPreview = document.getElementById('photoPreview');
        const photoEmpty = document.getElementById('photoEmpty');
        const openCameraButton = document.getElementById('openCameraButton');
        const capturePhotoButton = document.getElementById('capturePhotoButton');
        const closeCameraButton = document.getElementById('closeCameraButton');
        const cameraVideo = document.getElementById('cameraVideo');
        const cameraCanvas = document.getElementById('cameraCanvas');
        let cameraStream = null;

        function setPhotoPreviewSrc(src) {
            if (photoPreview) {
                photoPreview.src = src;
                photoPreview.classList.remove('hidden');
            }
            if (photoEmpty) photoEmpty.classList.add('hidden');
        }

        function stopCamera() {
            if (cameraStream) {
                cameraStream.getTracks().forEach((track) => track.stop());
                cameraStream = null;
            }
            if (cameraVideo) cameraVideo.classList.add('hidden');
            if (capturePhotoButton) capturePhotoButton.classList.add('hidden');
            if (closeCameraButton) closeCameraButton.classList.add('hidden');
            if (openCameraButton) openCameraButton.classList.remove('hidden');
        }

        photoInput?.addEventListener('change', (event) => {
            const file = event.target.files?.[0];
            if (!file) return;
            if (photoCapture) photoCapture.value = '';
            setPhotoPreviewSrc(URL.createObjectURL(file));
        });

        openCameraButton?.addEventListener('click', async () => {
            try {
                cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
                if (cameraVideo) {
                    cameraVideo.srcObject = cameraStream;
                    cameraVideo.classList.remove('hidden');
                }
                if (capturePhotoButton) capturePhotoButton.classList.remove('hidden');
                if (closeCameraButton) closeCameraButton.classList.remove('hidden');
                if (openCameraButton) openCameraButton.classList.add('hidden');
            } catch (error) {
                alert('Kamera tidak dapat dibuka. Pastikan izin kamera sudah diberikan atau gunakan upload file.');
            }
        });

        capturePhotoButton?.addEventListener('click', () => {
            if (!cameraVideo || !cameraCanvas) return;
            cameraCanvas.width = cameraVideo.videoWidth;
            cameraCanvas.height = cameraVideo.videoHeight;
            cameraCanvas.getContext('2d').drawImage(cameraVideo, 0, 0);
            const dataUrl = cameraCanvas.toDataURL('image/jpeg', 0.9);
            if (photoCapture) photoCapture.value = dataUrl;
            if (photoInput) photoInput.value = '';
            setPhotoPreviewSrc(dataUrl);
            stopCamera();
        });

        closeCameraButton?.addEventListener('click', stopCamera);

        // Multiple Experiences Logic
        const expContainer = document.getElementById('experiencesContainer');
        const addExpBtn = document.getElementById('addExperienceBtn');
        let expCount = {{ max(1, count($displayExperiences)) }};

        function reindexExperiences() {
            if (!expContainer) return;
            const rows = expContainer.querySelectorAll('.experience-row');
            rows.forEach((row, idx) => {
                const label = row.querySelector('.experience-index');
                if (label) label.textContent = `Pekerjaan #${idx + 1}`;
            });
        }

        addExpBtn?.addEventListener('click', () => {
            if (!expContainer) return;
            const newIndex = expCount++;
            const row = document.createElement('div');
            row.className = 'experience-row relative rounded-[24px] border border-[#322d2a]/15 bg-white p-4';
            row.innerHTML = `
                <div class="flex items-center justify-between border-b border-[#322d2a]/10 pb-2.5 mb-3">
                    <span class="experience-index text-xs font-medium uppercase tracking-[0.1em] text-[#f97316]">Pekerjaan #${expContainer.querySelectorAll('.experience-row').length + 1}</span>
                    <button type="button" class="remove-exp-btn text-xs text-red-500 hover:text-red-700 font-normal transition">Hapus</button>
                </div>
                <div class="grid gap-4 md:grid-cols-3">
                    <label class="block">
                        <span class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Nama Perusahaan</span>
                        <input type="text" name="experiences[${newIndex}][company_name]" placeholder="PT Contoh Nama" class="mt-1.5 w-full uppercase rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2.5 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                    </label>
                    <label class="block">
                        <span class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Jabatan</span>
                        <input type="text" name="experiences[${newIndex}][position]" placeholder="Staff / Supervisor" class="mt-1.5 w-full uppercase rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2.5 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                    </label>
                    <label class="block">
                        <span class="text-xs font-normal uppercase tracking-[0.12em] text-[#6f6b67]">Tahun Bekerja</span>
                        <input type="text" name="experiences[${newIndex}][work_years]" placeholder="2023 - 2025" class="mt-1.5 w-full uppercase rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2.5 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                    </label>
                </div>
            `;
            expContainer.appendChild(row);
            bindAutoUppercase();
            reindexExperiences();
        });

        expContainer?.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-exp-btn')) {
                const row = e.target.closest('.experience-row');
                if (expContainer.querySelectorAll('.experience-row').length > 1) {
                    row.remove();
                } else {
                    row.querySelectorAll('input').forEach(inp => inp.value = '');
                }
                reindexExperiences();
            }
        });
    </script>
</x-layouts.app>
