<x-layouts.app title="Profil Kandidat - Career Indodata">
    <section class="bg-[#f0efe9] px-4 pb-12 pt-28 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1240px]">
            <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr] lg:items-stretch">
                <div class="rounded-[56px] border border-white bg-white p-6 sm:p-8 lg:p-10">
                    <div class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]">
                        <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                        Candidate Onboarding
                    </div>
                    <h1 class="mt-8 max-w-3xl text-[42px] font-light leading-none tracking-[0.52px] text-[#322d2a] md:text-[56px]">Lengkapi data profil sesuai format recruitment.</h1>
                    <p class="mt-6 max-w-3xl text-sm font-normal leading-[1.6] tracking-[0.14px] text-[#6f6b67] md:text-base">Bagian profil kini dibagi menjadi Recruitment, Riwayat Pendidikan, dan Riwayat Pekerjaan agar hasil data mengikuti format output yang Anda tunjukkan.</p>
                </div>
                <div class="relative overflow-hidden rounded-[56px] bg-[#322d2a] p-8 text-white sm:p-10">
                    <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-[#f97316]/25 blur-3xl"></div>
                    <div class="absolute -bottom-24 left-8 h-56 w-56 rounded-full bg-[#f97316]/10 blur-3xl"></div>
                    <div class="relative z-10 flex h-full min-h-[300px] flex-col justify-between">
                        <div class="inline-flex w-fit items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-normal uppercase tracking-[0.12em] text-white backdrop-blur-md">
                            <span class="h-1.5 w-1.5 bg-[#f97316]"></span>
                            Career Hallonet
                        </div>
                        <div class="rounded-[32px] border border-white/15 bg-white/10 p-6 backdrop-blur-md">
                            <p class="text-xs font-normal uppercase tracking-[0.12em] text-white/60">Profile Completion</p>
                            <p class="mt-3 text-[44px] font-light leading-none tracking-[0.44px] text-white">{{ $profile->profile_completion }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $profileStepDone = $profile->profile_completion >= 100;
                $education = $profile->educations->first();
                $experience = $profile->experiences->first();
            @endphp

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @foreach([
                    ['step' => '01', 'title' => 'Lengkapi Profil Recruitment', 'text' => 'Isi identitas, alamat detail, pendidikan, dan pekerjaan terakhir.', 'done' => $profileStepDone],
                    ['step' => '02', 'title' => 'Kirim Lamaran', 'text' => $profileStepDone ? 'Profil lengkap, Anda sudah bisa melamar.' : 'Lengkapi profil sampai 100% terlebih dahulu.', 'done' => $profileStepDone],
                ] as $item)
                    <div class="rounded-[32px] border {{ $item['done'] ? 'border-[#f97316]/25 bg-[#fff3e8]' : 'border-white bg-white' }} p-5">
                        <div class="flex items-start gap-4">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[18px] {{ $item['done'] ? 'bg-[#f97316] text-white' : 'bg-[#fff3e8] text-[#f97316]' }} text-sm font-normal">{{ $item['done'] ? '✓' : $item['step'] }}</div>
                            <div>
                                <h2 class="font-normal tracking-[0.16px] text-[#322d2a]">{{ $item['title'] }}</h2>
                                <p class="mt-1 text-sm font-normal leading-[1.6] text-[#6f6b67]">{{ $item['text'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if (session('success'))
                <div class="mt-5 rounded-[24px] border border-[#f97316]/30 bg-[#fff3e8] p-4 text-sm font-normal text-[#322d2a]">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-5 rounded-[24px] border border-[#f97316]/40 bg-[#fff3e8] p-4 text-sm font-normal text-[#322d2a]">
                    Mohon lengkapi seluruh field wajib pada bagian recruitment, pendidikan, dan pekerjaan.
                </div>
            @endif

            <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_0.72fr]">
                <form method="POST" action="{{ route('candidate.profile.update') }}" enctype="multipart/form-data" class="rounded-[40px] border border-white bg-white p-5 md:p-7">
                    @csrf
                    @method('PUT')

                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]"><span class="h-1.5 w-1.5 bg-[#f97316]"></span>Step 01</p>
                            <h2 class="mt-4 text-[32px] font-light leading-none tracking-[0.32px] text-[#322d2a]">Form Recruitment Kandidat</h2>
                            <p class="mt-3 text-sm font-normal leading-[1.6] text-[#6f6b67]">Field opsional hanya LinkedIn URL dan Portfolio URL.</p>
                        </div>
                        <span class="rounded-full bg-[#fff3e8] px-4 py-2 text-sm font-normal text-[#f97316]">{{ $profile->profile_completion }}%</span>
                    </div>

                    <div class="mt-6 rounded-[32px] border border-[#f97316]/20 bg-[#fff3e8] p-5">
                        <label class="block">
                            <span class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Pass Foto <span class="text-[#f97316]">*</span></span>
                            <div class="mt-3 flex flex-col gap-4 md:flex-row md:items-center">
                                <div class="h-32 w-28 overflow-hidden rounded-[24px] border border-white bg-white">
                                    <img id="photoPreview" src="{{ $profile->photo ? asset('storage/'.$profile->photo) : '' }}" alt="Preview pass foto" class="{{ $profile->photo ? '' : 'hidden' }} h-full w-full object-cover">
                                    <div id="photoEmpty" class="{{ $profile->photo ? 'hidden' : '' }} flex h-full w-full items-center justify-center text-center text-xs font-normal text-[#6f6b67]">Belum ada foto</div>
                                </div>
                                <div class="flex-1 space-y-3">
                                    <input id="photoInput" type="file" name="photo" accept="image/jpeg,image/png,image/webp" capture="user" class="w-full rounded-[18px] border border-dashed border-[#322d2a]/20 bg-white px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316]">
                                    <input id="photoCapture" type="hidden" name="photo_capture" value="">
                                    <div class="flex flex-wrap gap-2">
                                        <button type="button" id="openCameraButton" class="rounded-full bg-[#322d2a] px-4 py-2 text-xs font-normal text-white transition hover:bg-[#0f0e12]">Open Kamera</button>
                                        <button type="button" id="capturePhotoButton" class="hidden rounded-full bg-[#f97316] px-4 py-2 text-xs font-normal text-white transition hover:bg-[#ea580c]">Ambil Foto</button>
                                        <button type="button" id="closeCameraButton" class="hidden rounded-full border border-[#322d2a]/20 bg-white px-4 py-2 text-xs font-normal text-[#322d2a] transition hover:border-[#f97316] hover:text-[#f97316]">Tutup Kamera</button>
                                    </div>
                                    <video id="cameraVideo" autoplay playsinline class="hidden max-h-56 w-full rounded-[24px] bg-[#0f0e12]"></video>
                                    <canvas id="cameraCanvas" class="hidden"></canvas>
                                    <p class="text-xs font-normal leading-[1.6] text-[#6f6b67]">Gunakan foto formal. Bisa upload file atau ambil langsung dari kamera perangkat.</p>
                                    @error('photo')<p class="text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="mt-6 rounded-[32px] border border-[#322d2a]/10 bg-[#f0efe9] p-5">
                        <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]"><span class="h-1.5 w-1.5 bg-[#f97316]"></span>Recruitment</p>
                        <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                            @foreach([
                                ['full_name', 'Nama Kandidat', 'text'], ['nik', 'NIK (16 digit)', 'text'], ['birth_place', 'Tempat Lahir', 'text'], ['birth_date', 'Tanggal Lahir', 'date'], ['phone', 'No. Telepon', 'text'], ['whatsapp', 'No WA Aktif', 'text'], ['email', 'Email', 'email'], ['rt_rw', 'RT / RW', 'text'], ['village', 'Kel/Desa', 'text'], ['district', 'Kecamatan', 'text'], ['city', 'Kab/Kota', 'text'], ['province', 'Provinsi', 'text'], ['postal_code', 'Kode Pos', 'text'], ['linkedin_url', 'LinkedIn URL', 'url'], ['portfolio_url', 'Portfolio URL', 'url'],
                            ] as [$field, $label, $type])
                                <label class="block {{ in_array($field, ['address']) ? 'md:col-span-2 xl:col-span-3' : '' }}">
                                    <span class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">{{ $label }} @if(! in_array($field, ['linkedin_url', 'portfolio_url']))<span class="text-[#f97316]">*</span>@endif</span>
                                    <input type="{{ $type }}" name="{{ $field }}" value="{{ old($field, optional($profile->{$field})->format('Y-m-d') ?? $profile->{$field}) }}" @if(! in_array($field, ['linkedin_url', 'portfolio_url'])) required @endif class="mt-2 w-full rounded-[18px] border @error($field) border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition placeholder:text-[#6f6b67]/60 focus:border-[#f97316] focus:bg-white">
                                    @error($field)<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror
                                </label>
                            @endforeach

                            <label class="block">
                                <span class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Gender <span class="text-[#f97316]">*</span></span>
                                <select name="gender" required class="mt-2 w-full rounded-[18px] border @error('gender') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                                    <option value="">Pilih Gender</option>
                                    @foreach(['Laki-laki', 'Perempuan'] as $gender)
                                        <option value="{{ $gender }}" @selected(old('gender', $profile->gender) === $gender)>{{ $gender }}</option>
                                    @endforeach
                                </select>
                                @error('gender')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror
                            </label>

                            <label class="block">
                                <span class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Status Pernikahan <span class="text-[#f97316]">*</span></span>
                                <select name="marital_status" required class="mt-2 w-full rounded-[18px] border @error('marital_status') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                                    <option value="">Pilih Status Pernikahan</option>
                                    @foreach(['Belum Menikah', 'Menikah', 'Cerai Hidup', 'Cerai Mati'] as $status)
                                        <option value="{{ $status }}" @selected(old('marital_status', $profile->marital_status) === $status)>{{ $status }}</option>
                                    @endforeach
                                </select>
                                @error('marital_status')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror
                            </label>

                            <label class="block">
                                <span class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">SIM <span class="text-[#f97316]">*</span></span>
                                <select name="driving_license" required class="mt-2 w-full rounded-[18px] border @error('driving_license') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">
                                    <option value="">Pilih SIM</option>
                                    @foreach(['A', 'B1', 'B2', 'C', 'Tidak Ada'] as $license)
                                        <option value="{{ $license }}" @selected(old('driving_license', $profile->driving_license) === $license)>{{ $license }}</option>
                                    @endforeach
                                </select>
                                @error('driving_license')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror
                            </label>

                            <label class="block md:col-span-2 xl:col-span-3">
                                <span class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Alamat <span class="text-[#f97316]">*</span></span>
                                <textarea name="address" rows="4" required class="mt-2 w-full rounded-[18px] border @error('address') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">{{ old('address', $profile->address) }}</textarea>
                                @error('address')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 rounded-[32px] border border-[#322d2a]/10 bg-[#f0efe9] p-5">
                        <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]"><span class="h-1.5 w-1.5 bg-[#f97316]"></span>Riwayat Pendidikan</p>
                        <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                            <label class="block"><span class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Educ <span class="text-[#f97316]">*</span></span><select name="education_level" required class="mt-2 w-full rounded-[18px] border @error('education_level') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white"><option value="">Pilih</option>@foreach(['SMA/SMK', 'D3', 'D4', 'S1', 'S2', 'S3'] as $level)<option value="{{ $level }}" @selected(old('education_level', $education?->education_level) === $level)>{{ $level }}</option>@endforeach</select>@error('education_level')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror</label>
                            <label class="block xl:col-span-2"><span class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Nama Sekolah / Universitas <span class="text-[#f97316]">*</span></span><input type="text" name="institution" value="{{ old('institution', $education?->institution) }}" required class="mt-2 w-full rounded-[18px] border @error('institution') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">@error('institution')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror</label>
                            <label class="block xl:col-span-2"><span class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Jurusan <span class="text-[#f97316]">*</span></span><input type="text" name="major" value="{{ old('major', $education?->major) }}" required class="mt-2 w-full rounded-[18px] border @error('major') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">@error('major')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror</label>
                            <label class="block"><span class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">IPK / Rata2 <span class="text-[#f97316]">*</span></span><input type="text" name="ipk" value="{{ old('ipk', $education?->ipk ?? $education?->gpa) }}" required class="mt-2 w-full rounded-[18px] border @error('ipk') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">@error('ipk')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror</label>
                            <label class="block xl:col-span-4"><span class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Keahlian / Sertifikasi <span class="text-[#f97316]">*</span></span><textarea name="certification" rows="3" required class="mt-2 w-full rounded-[18px] border @error('certification') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">{{ old('certification', $education?->certification) }}</textarea>@error('certification')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror</label>
                        </div>
                    </div>

                    <div class="mt-6 rounded-[32px] border border-[#322d2a]/10 bg-[#f0efe9] p-5">
                        <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]"><span class="h-1.5 w-1.5 bg-[#f97316]"></span>Riwayat Pekerjaan</p>
                        <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                            <label class="block xl:col-span-1"><span class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Nama Perusahaan Terakhir <span class="text-[#f97316]">*</span></span><input type="text" name="company_name" value="{{ old('company_name', $experience?->company_name) }}" required class="mt-2 w-full rounded-[18px] border @error('company_name') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">@error('company_name')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror</label>
                            <label class="block xl:col-span-1"><span class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Jabatan <span class="text-[#f97316]">*</span></span><input type="text" name="position" value="{{ old('position', $experience?->position) }}" required class="mt-2 w-full rounded-[18px] border @error('position') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">@error('position')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror</label>
                            <label class="block xl:col-span-1"><span class="text-sm font-normal tracking-[0.14px] text-[#322d2a]">Tahun Bekerja <span class="text-[#f97316]">*</span></span><input type="text" name="work_years" value="{{ old('work_years', $experience?->work_years) }}" required placeholder="2024 - 2026" class="mt-2 w-full rounded-[18px] border @error('work_years') border-[#f97316] bg-[#fff3e8] @else border-[#322d2a]/15 bg-white @enderror px-4 py-3 text-sm font-normal text-[#322d2a] outline-none transition focus:border-[#f97316] focus:bg-white">@error('work_years')<p class="mt-1 text-xs font-normal text-[#f97316]">{{ $message }}</p>@enderror</label>
                        </div>
                    </div>

                    <button class="mt-6 rounded-full bg-[#f97316] px-[22px] py-[16px] text-sm font-normal tracking-[0.14px] text-white transition hover:bg-[#ea580c]">Simpan Data Recruitment</button>
                </form>

                <div class="space-y-6">
                    <div class="rounded-[40px] border border-white bg-white p-5 md:p-7">
                        <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]"><span class="h-1.5 w-1.5 bg-[#f97316]"></span>Info</p>
                        <h2 class="mt-4 text-[32px] font-light leading-none tracking-[0.32px] text-[#322d2a]">Status Profil</h2>
                        <div class="mt-4 rounded-[28px] {{ $profileStepDone ? 'bg-[#fff3e8] text-[#322d2a] ring-[#f97316]/25' : 'bg-[#fff3e8] text-[#322d2a] ring-[#f97316]/25' }} p-5 ring-1">
                            <p class="text-sm font-normal">{{ $profileStepDone ? 'Profil recruitment sudah lengkap.' : 'Profil recruitment belum lengkap.' }}</p>
                            <p class="mt-1 text-xs font-normal leading-[1.6] text-[#6f6b67]">Kelengkapan saat ini {{ $profile->profile_completion }}%. Dashboard kandidat terbuka setelah semua bagian wajib terisi.</p>
                        </div>
                    </div>

                    <div class="rounded-[40px] border border-white bg-white p-5 md:p-7">
                        <p class="inline-flex items-center gap-2 text-xs font-normal uppercase tracking-[0.12em] text-[#322d2a]"><span class="h-1.5 w-1.5 bg-[#f97316]"></span>Preview Output</p>
                        <h2 class="mt-4 text-[32px] font-light leading-none tracking-[0.32px] text-[#322d2a]">Ringkasan Recruitment</h2>
                        <div class="mt-4 space-y-3 text-sm">
                            <div class="rounded-[28px] bg-[#f0efe9] p-4 ring-1 ring-[#322d2a]/10"><p class="font-normal text-[#322d2a]">Recruitment</p><ul class="mt-3 space-y-2 text-[#6f6b67]"><li><span class="text-[#322d2a]">Nama:</span> {{ $profile->full_name ?: '-' }}</li><li><span class="text-[#322d2a]">Gender:</span> {{ $profile->gender ?: '-' }}</li><li><span class="text-[#322d2a]">NIK:</span> {{ $profile->nik ?: '-' }}</li><li><span class="text-[#322d2a]">Tempat / Tanggal Lahir:</span> {{ $profile->birth_place ?: '-' }} / {{ $profile->birth_date?->format('d-m-Y') ?: '-' }}</li><li><span class="text-[#322d2a]">Alamat:</span> {{ $profile->address ?: '-' }}</li><li><span class="text-[#322d2a]">RT/RW:</span> {{ $profile->rt_rw ?: '-' }}</li><li><span class="text-[#322d2a]">Kel/Desa:</span> {{ $profile->village ?: '-' }}</li><li><span class="text-[#322d2a]">Kecamatan:</span> {{ $profile->district ?: '-' }}</li><li><span class="text-[#322d2a]">Kab/Kota:</span> {{ $profile->city ?: '-' }}</li><li><span class="text-[#322d2a]">Provinsi:</span> {{ $profile->province ?: '-' }}</li><li><span class="text-[#322d2a]">Telepon / WA:</span> {{ $profile->whatsapp ?: '-' }}</li><li><span class="text-[#322d2a]">Email:</span> {{ $profile->email ?: '-' }}</li></ul></div>
                            <div class="rounded-[28px] bg-[#f0efe9] p-4 ring-1 ring-[#322d2a]/10"><p class="font-normal text-[#322d2a]">Riwayat Pendidikan</p><ul class="mt-3 space-y-2 text-[#6f6b67]"><li><span class="text-[#322d2a]">Educ:</span> {{ $education?->education_level ?: '-' }}</li><li><span class="text-[#322d2a]">Institusi:</span> {{ $education?->institution ?: '-' }}</li><li><span class="text-[#322d2a]">Jurusan:</span> {{ $education?->major ?: '-' }}</li><li><span class="text-[#322d2a]">IPK/Rata2:</span> {{ $education?->ipk ?: '-' }}</li><li><span class="text-[#322d2a]">Keahlian/Sertifikasi:</span> {{ $education?->certification ?: '-' }}</li><li><span class="text-[#322d2a]">SIM:</span> {{ $profile->driving_license ?: '-' }}</li></ul></div>
                            <div class="rounded-[28px] bg-[#f0efe9] p-4 ring-1 ring-[#322d2a]/10"><p class="font-normal text-[#322d2a]">Riwayat Pekerjaan</p><ul class="mt-3 space-y-2 text-[#6f6b67]"><li><span class="text-[#322d2a]">Perusahaan Terakhir:</span> {{ $experience?->company_name ?: '-' }}</li><li><span class="text-[#322d2a]">Jabatan:</span> {{ $experience?->position ?: '-' }}</li><li><span class="text-[#322d2a]">Tahun Bekerja:</span> {{ $experience?->work_years ?: '-' }}</li></ul></div>
                        </div>
                    </div>

                    {{--
                    STEP DOKUMEN DINONAKTIFKAN SEMENTARA.
                    Blok ini sengaja dikomentari agar bisa dipakai lagi sewaktu-waktu.
                    --}}
                </div>
            </div>
        </div>
    </section>

    <script>
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

        function showPreview(src) {
            photoPreview.src = src;
            photoPreview.classList.remove('hidden');
            photoEmpty.classList.add('hidden');
        }

        function stopCamera() {
            if (cameraStream) {
                cameraStream.getTracks().forEach((track) => track.stop());
                cameraStream = null;
            }
            cameraVideo.classList.add('hidden');
            capturePhotoButton.classList.add('hidden');
            closeCameraButton.classList.add('hidden');
            openCameraButton.classList.remove('hidden');
        }

        photoInput?.addEventListener('change', (event) => {
            const file = event.target.files?.[0];
            if (!file) return;
            photoCapture.value = '';
            showPreview(URL.createObjectURL(file));
        });

        openCameraButton?.addEventListener('click', async () => {
            try {
                cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
                cameraVideo.srcObject = cameraStream;
                cameraVideo.classList.remove('hidden');
                capturePhotoButton.classList.remove('hidden');
                closeCameraButton.classList.remove('hidden');
                openCameraButton.classList.add('hidden');
            } catch (error) {
                alert('Kamera tidak dapat dibuka. Pastikan izin kamera sudah diberikan atau gunakan upload file.');
            }
        });

        capturePhotoButton?.addEventListener('click', () => {
            cameraCanvas.width = cameraVideo.videoWidth;
            cameraCanvas.height = cameraVideo.videoHeight;
            cameraCanvas.getContext('2d').drawImage(cameraVideo, 0, 0);
            const dataUrl = cameraCanvas.toDataURL('image/jpeg', 0.9);
            photoCapture.value = dataUrl;
            photoInput.value = '';
            showPreview(dataUrl);
            stopCamera();
        });

        closeCameraButton?.addEventListener('click', stopCamera);
    </script>
</x-layouts.app>
