<x-layouts.app title="Profil Kandidat - Career Indodata">
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-3xl bg-slate-950 text-white shadow-xl shadow-slate-300/20">
            <div class="bg-[radial-gradient(circle_at_top_left,_rgba(249,115,22,0.45),_transparent_30%),radial-gradient(circle_at_bottom_right,_rgba(251,191,36,0.28),_transparent_28%)] p-7 md:p-9">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-orange-300">Candidate Onboarding</p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight md:text-4xl">Lengkapi data profil sesuai format recruitment.</h1>
                <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-300 md:text-base">Bagian profil kini dibagi menjadi Recruitment, Riwayat Pendidikan, dan Riwayat Pekerjaan agar hasil data mengikuti format output yang Anda tunjukkan.</p>
            </div>
        </div>

        @php
            $profileStepDone = $profile->profile_completion >= 100;
            $education = $profile->educations->first();
            $experience = $profile->experiences->first();
        @endphp

        <div class="mt-6 grid gap-3 md:grid-cols-2">
            @foreach([
                ['step' => '01', 'title' => 'Lengkapi Profil Recruitment', 'text' => 'Isi identitas, alamat detail, pendidikan, dan pekerjaan terakhir.', 'done' => $profileStepDone],
                ['step' => '02', 'title' => 'Kirim Lamaran', 'text' => $profileStepDone ? 'Profil lengkap, Anda sudah bisa melamar.' : 'Lengkapi profil sampai 100% terlebih dahulu.', 'done' => $profileStepDone],
            ] as $item)
                <div class="rounded-2xl border {{ $item['done'] ? 'border-emerald-200 bg-emerald-50' : 'border-slate-200 bg-white' }} p-4 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $item['done'] ? 'bg-emerald-500 text-white' : 'bg-orange-100 text-orange-600' }} text-sm font-bold">{{ $item['done'] ? '✓' : $item['step'] }}</div>
                        <div>
                            <h2 class="font-bold text-slate-950">{{ $item['title'] }}</h2>
                            <p class="mt-1 text-sm font-medium leading-5 text-slate-500">{{ $item['text'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if (session('success'))
            <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-700">
                Mohon lengkapi seluruh field wajib pada bagian recruitment, pendidikan, dan pekerjaan.
            </div>
        @endif

        <div class="mt-6 grid gap-5 lg:grid-cols-[1fr_0.72fr]">
            <form method="POST" action="{{ route('candidate.profile.update') }}" enctype="multipart/form-data" class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm shadow-slate-200/60 md:p-7">
                @csrf
                @method('PUT')

                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-orange-500">Step 01</p>
                        <h2 class="mt-1.5 text-2xl font-bold text-slate-950">Form Recruitment Kandidat</h2>
                        <p class="mt-2 text-sm font-medium leading-6 text-slate-500">Field opsional hanya LinkedIn URL dan Portfolio URL.</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">{{ $profile->profile_completion }}%</span>
                </div>

                <div class="mt-6 rounded-2xl border border-orange-100 bg-orange-50/60 p-4">
                    <label class="block">
                        <span class="text-sm font-semibold text-slate-700">Pass Foto <span class="text-red-500">*</span></span>
                        <div class="mt-3 flex flex-col gap-4 md:flex-row md:items-center">
                            <div class="h-32 w-28 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                <img id="photoPreview" src="{{ $profile->photo ? asset('storage/'.$profile->photo) : '' }}" alt="Preview pass foto" class="{{ $profile->photo ? '' : 'hidden' }} h-full w-full object-cover">
                                <div id="photoEmpty" class="{{ $profile->photo ? 'hidden' : '' }} flex h-full w-full items-center justify-center text-center text-xs font-semibold text-slate-400">Belum ada foto</div>
                            </div>
                            <div class="flex-1 space-y-3">
                                <input id="photoInput" type="file" name="photo" accept="image/jpeg,image/png,image/webp" capture="user" class="w-full rounded-xl border border-dashed border-slate-300 bg-white px-3.5 py-2.5 text-sm font-medium">
                                <input id="photoCapture" type="hidden" name="photo_capture" value="">
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" id="openCameraButton" class="rounded-xl bg-slate-950 px-4 py-2 text-xs font-bold text-white transition hover:bg-orange-500">Open Kamera</button>
                                    <button type="button" id="capturePhotoButton" class="hidden rounded-xl bg-orange-500 px-4 py-2 text-xs font-bold text-white transition hover:bg-orange-600">Ambil Foto</button>
                                    <button type="button" id="closeCameraButton" class="hidden rounded-xl bg-slate-100 px-4 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-200">Tutup Kamera</button>
                                </div>
                                <video id="cameraVideo" autoplay playsinline class="hidden max-h-56 w-full rounded-2xl bg-slate-950"></video>
                                <canvas id="cameraCanvas" class="hidden"></canvas>
                                <p class="text-xs font-medium leading-5 text-slate-500">Gunakan foto formal. Bisa upload file atau ambil langsung dari kamera perangkat.</p>
                                @error('photo')<p class="text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </label>
                </div>

                <div class="mt-6 rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm font-black uppercase tracking-[0.16em] text-slate-500">Recruitment</p>
                    <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach([
                            ['full_name', 'Nama Kandidat', 'text'],
                            ['nik', 'NIK (16 digit)', 'text'],
                            ['birth_place', 'Tempat Lahir', 'text'],
                            ['birth_date', 'Tanggal Lahir', 'date'],
                            ['phone', 'No. Telepon', 'text'],
                            ['whatsapp', 'No WA Aktif', 'text'],
                            ['email', 'Email', 'email'],
                            ['rt_rw', 'RT / RW', 'text'],
                            ['village', 'Kel/Desa', 'text'],
                            ['district', 'Kecamatan', 'text'],
                            ['city', 'Kab/Kota', 'text'],
                            ['province', 'Provinsi', 'text'],
                            ['postal_code', 'Kode Pos', 'text'],
                            ['linkedin_url', 'LinkedIn URL', 'url'],
                            ['portfolio_url', 'Portfolio URL', 'url'],
                        ] as [$field, $label, $type])
                            <label class="block {{ in_array($field, ['address']) ? 'md:col-span-2 xl:col-span-3' : '' }}">
                                <span class="text-sm font-semibold text-slate-700">{{ $label }} @if(! in_array($field, ['linkedin_url', 'portfolio_url']))<span class="text-red-500">*</span>@endif</span>
                                <input type="{{ $type }}" name="{{ $field }}" value="{{ old($field, optional($profile->{$field})->format('Y-m-d') ?? $profile->{$field}) }}" @if(! in_array($field, ['linkedin_url', 'portfolio_url'])) required @endif class="mt-2 w-full rounded-xl border @error($field) border-red-300 bg-red-50 @else border-slate-200 bg-slate-50 @enderror px-3.5 py-2.5 text-sm font-medium outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">
                                @error($field)<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                            </label>
                        @endforeach

                        <label class="block">
                            <span class="text-sm font-semibold text-slate-700">Gender <span class="text-red-500">*</span></span>
                            <select name="gender" required class="mt-2 w-full rounded-xl border @error('gender') border-red-300 bg-red-50 @else border-slate-200 bg-slate-50 @enderror px-3.5 py-2.5 text-sm font-medium outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">
                                <option value="">Pilih Gender</option>
                                @foreach(['Laki-laki', 'Perempuan'] as $gender)
                                    <option value="{{ $gender }}" @selected(old('gender', $profile->gender) === $gender)>{{ $gender }}</option>
                                @endforeach
                            </select>
                            @error('gender')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </label>

                        <label class="block">
                            <span class="text-sm font-semibold text-slate-700">Status Pernikahan <span class="text-red-500">*</span></span>
                            <select name="marital_status" required class="mt-2 w-full rounded-xl border @error('marital_status') border-red-300 bg-red-50 @else border-slate-200 bg-slate-50 @enderror px-3.5 py-2.5 text-sm font-medium outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">
                                <option value="">Pilih Status Pernikahan</option>
                                @foreach(['Belum Menikah', 'Menikah', 'Cerai Hidup', 'Cerai Mati'] as $status)
                                    <option value="{{ $status }}" @selected(old('marital_status', $profile->marital_status) === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                            @error('marital_status')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </label>

                        <label class="block">
                            <span class="text-sm font-semibold text-slate-700">SIM <span class="text-red-500">*</span></span>
                            <select name="driving_license" required class="mt-2 w-full rounded-xl border @error('driving_license') border-red-300 bg-red-50 @else border-slate-200 bg-slate-50 @enderror px-3.5 py-2.5 text-sm font-medium outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">
                                <option value="">Pilih SIM</option>
                                @foreach(['A', 'B1', 'B2', 'C', 'Tidak Ada'] as $license)
                                    <option value="{{ $license }}" @selected(old('driving_license', $profile->driving_license) === $license)>{{ $license }}</option>
                                @endforeach
                            </select>
                            @error('driving_license')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </label>

                        <label class="block md:col-span-2 xl:col-span-3">
                            <span class="text-sm font-semibold text-slate-700">Alamat <span class="text-red-500">*</span></span>
                            <textarea name="address" rows="4" required class="mt-2 w-full rounded-xl border @error('address') border-red-300 bg-red-50 @else border-slate-200 bg-slate-50 @enderror px-3.5 py-2.5 text-sm font-medium outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">{{ old('address', $profile->address) }}</textarea>
                            @error('address')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </label>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm font-black uppercase tracking-[0.16em] text-slate-500">Riwayat Pendidikan</p>
                    <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                        <label class="block">
                            <span class="text-sm font-semibold text-slate-700">Educ <span class="text-red-500">*</span></span>
                            <select name="education_level" required class="mt-2 w-full rounded-xl border @error('education_level') border-red-300 bg-red-50 @else border-slate-200 bg-slate-50 @enderror px-3.5 py-2.5 text-sm font-medium outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">
                                <option value="">Pilih</option>
                                @foreach(['SMA/SMK', 'D3', 'D4', 'S1', 'S2', 'S3'] as $level)
                                    <option value="{{ $level }}" @selected(old('education_level', $education?->education_level) === $level)>{{ $level }}</option>
                                @endforeach
                            </select>
                            @error('education_level')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block xl:col-span-2">
                            <span class="text-sm font-semibold text-slate-700">Nama Sekolah / Universitas <span class="text-red-500">*</span></span>
                            <input type="text" name="institution" value="{{ old('institution', $education?->institution) }}" required class="mt-2 w-full rounded-xl border @error('institution') border-red-300 bg-red-50 @else border-slate-200 bg-slate-50 @enderror px-3.5 py-2.5 text-sm font-medium outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">
                            @error('institution')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block xl:col-span-2">
                            <span class="text-sm font-semibold text-slate-700">Jurusan <span class="text-red-500">*</span></span>
                            <input type="text" name="major" value="{{ old('major', $education?->major) }}" required class="mt-2 w-full rounded-xl border @error('major') border-red-300 bg-red-50 @else border-slate-200 bg-slate-50 @enderror px-3.5 py-2.5 text-sm font-medium outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">
                            @error('major')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block">
                            <span class="text-sm font-semibold text-slate-700">IPK / Rata2 <span class="text-red-500">*</span></span>
                            <input type="text" name="ipk" value="{{ old('ipk', $education?->ipk ?? $education?->gpa) }}" required class="mt-2 w-full rounded-xl border @error('ipk') border-red-300 bg-red-50 @else border-slate-200 bg-slate-50 @enderror px-3.5 py-2.5 text-sm font-medium outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">
                            @error('ipk')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block xl:col-span-4">
                            <span class="text-sm font-semibold text-slate-700">Keahlian / Sertifikasi <span class="text-red-500">*</span></span>
                            <textarea name="certification" rows="3" required class="mt-2 w-full rounded-xl border @error('certification') border-red-300 bg-red-50 @else border-slate-200 bg-slate-50 @enderror px-3.5 py-2.5 text-sm font-medium outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">{{ old('certification', $education?->certification) }}</textarea>
                            @error('certification')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </label>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm font-black uppercase tracking-[0.16em] text-slate-500">Riwayat Pekerjaan</p>
                    <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        <label class="block xl:col-span-1">
                            <span class="text-sm font-semibold text-slate-700">Nama Perusahaan Terakhir <span class="text-red-500">*</span></span>
                            <input type="text" name="company_name" value="{{ old('company_name', $experience?->company_name) }}" required class="mt-2 w-full rounded-xl border @error('company_name') border-red-300 bg-red-50 @else border-slate-200 bg-slate-50 @enderror px-3.5 py-2.5 text-sm font-medium outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">
                            @error('company_name')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block xl:col-span-1">
                            <span class="text-sm font-semibold text-slate-700">Jabatan <span class="text-red-500">*</span></span>
                            <input type="text" name="position" value="{{ old('position', $experience?->position) }}" required class="mt-2 w-full rounded-xl border @error('position') border-red-300 bg-red-50 @else border-slate-200 bg-slate-50 @enderror px-3.5 py-2.5 text-sm font-medium outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">
                            @error('position')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block xl:col-span-1">
                            <span class="text-sm font-semibold text-slate-700">Tahun Bekerja <span class="text-red-500">*</span></span>
                            <input type="text" name="work_years" value="{{ old('work_years', $experience?->work_years) }}" required placeholder="2024 - 2026" class="mt-2 w-full rounded-xl border @error('work_years') border-red-300 bg-red-50 @else border-slate-200 bg-slate-50 @enderror px-3.5 py-2.5 text-sm font-medium outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">
                            @error('work_years')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </label>
                    </div>
                </div>

                <button class="mt-6 rounded-xl bg-orange-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-orange-500/20 transition hover:bg-orange-600">Simpan Data Recruitment</button>
            </form>

            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm shadow-slate-200/60 md:p-7">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-orange-500">Info</p>
                    <h2 class="mt-1.5 text-2xl font-bold text-slate-950">Status Profil</h2>
                    <div class="mt-4 rounded-2xl {{ $profileStepDone ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-orange-50 text-orange-700 ring-orange-200' }} p-4 ring-1">
                        <p class="text-sm font-bold">{{ $profileStepDone ? 'Profil recruitment sudah lengkap.' : 'Profil recruitment belum lengkap.' }}</p>
                        <p class="mt-1 text-xs font-semibold leading-5">Kelengkapan saat ini {{ $profile->profile_completion }}%. Dashboard kandidat terbuka setelah semua bagian wajib terisi.</p>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm shadow-slate-200/60 md:p-7">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-orange-500">Preview Output</p>
                    <h2 class="mt-1.5 text-2xl font-bold text-slate-950">Ringkasan Recruitment</h2>
                    <div class="mt-4 space-y-3 text-sm">
                        <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
                            <p class="font-bold text-slate-900">Recruitment</p>
                            <ul class="mt-3 space-y-2 text-slate-600">
                                <li><span class="font-semibold">Nama:</span> {{ $profile->full_name ?: '-' }}</li>
                                <li><span class="font-semibold">Gender:</span> {{ $profile->gender ?: '-' }}</li>
                                <li><span class="font-semibold">NIK:</span> {{ $profile->nik ?: '-' }}</li>
                                <li><span class="font-semibold">Tempat / Tanggal Lahir:</span> {{ $profile->birth_place ?: '-' }} / {{ $profile->birth_date?->format('d-m-Y') ?: '-' }}</li>
                                <li><span class="font-semibold">Alamat:</span> {{ $profile->address ?: '-' }}</li>
                                <li><span class="font-semibold">RT/RW:</span> {{ $profile->rt_rw ?: '-' }}</li>
                                <li><span class="font-semibold">Kel/Desa:</span> {{ $profile->village ?: '-' }}</li>
                                <li><span class="font-semibold">Kecamatan:</span> {{ $profile->district ?: '-' }}</li>
                                <li><span class="font-semibold">Kab/Kota:</span> {{ $profile->city ?: '-' }}</li>
                                <li><span class="font-semibold">Provinsi:</span> {{ $profile->province ?: '-' }}</li>
                                <li><span class="font-semibold">Telepon / WA:</span> {{ $profile->whatsapp ?: '-' }}</li>
                                <li><span class="font-semibold">Email:</span> {{ $profile->email ?: '-' }}</li>
                            </ul>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
                            <p class="font-bold text-slate-900">Riwayat Pendidikan</p>
                            <ul class="mt-3 space-y-2 text-slate-600">
                                <li><span class="font-semibold">Educ:</span> {{ $education?->education_level ?: '-' }}</li>
                                <li><span class="font-semibold">Institusi:</span> {{ $education?->institution ?: '-' }}</li>
                                <li><span class="font-semibold">Jurusan:</span> {{ $education?->major ?: '-' }}</li>
                                <li><span class="font-semibold">IPK/Rata2:</span> {{ $education?->ipk ?: '-' }}</li>
                                <li><span class="font-semibold">Keahlian/Sertifikasi:</span> {{ $education?->certification ?: '-' }}</li>
                                <li><span class="font-semibold">SIM:</span> {{ $profile->driving_license ?: '-' }}</li>
                            </ul>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
                            <p class="font-bold text-slate-900">Riwayat Pekerjaan</p>
                            <ul class="mt-3 space-y-2 text-slate-600">
                                <li><span class="font-semibold">Perusahaan Terakhir:</span> {{ $experience?->company_name ?: '-' }}</li>
                                <li><span class="font-semibold">Jabatan:</span> {{ $experience?->position ?: '-' }}</li>
                                <li><span class="font-semibold">Tahun Bekerja:</span> {{ $experience?->work_years ?: '-' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{--
                STEP DOKUMEN DINONAKTIFKAN SEMENTARA.
                Blok ini sengaja dikomentari agar bisa dipakai lagi sewaktu-waktu.
                --}}
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
