from pathlib import Path

path = Path('resources/views/dashboard/admin.blade.php')
text = path.read_text(encoding='utf-8')
start = text.index('            {{-- APPLICANT TRACKING --}}')
end = text.index('        </div>\n    </section>', start)
new = '''            {{-- HRD APPLICANT TRACKING --}}
            <div class="mt-8 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-500">HRD Applicant Tracking</p>
                        <h2 class="mt-2 text-2xl font-black text-slate-950">Full List Data Pelamar</h2>
                        <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                            HRD dapat melihat data diri lengkap, foto, pendidikan, pengalaman, posisi yang dilamar, serta mengubah status pelamar langsung dari tabel.
                        </p>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-2xl bg-slate-50 px-5 py-3 text-sm font-black text-slate-700 ring-1 ring-slate-200">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        {{ $allApplications->count() }} total pelamar
                    </div>
                </div>

                <div class="mt-7 overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="min-w-[1800px] w-full divide-y divide-slate-200 text-left">
                        <thead class="bg-slate-950 text-xs font-black uppercase tracking-[0.16em] text-white">
                            <tr>
                                <th class="px-4 py-4">Foto</th>
                                <th class="px-4 py-4">Apply Date</th>
                                <th class="px-4 py-4">Position Applied</th>
                                <th class="px-4 py-4">Candidate Name</th>
                                <th class="px-4 py-4">Gender</th>
                                <th class="px-4 py-4">NIK</th>
                                <th class="px-4 py-4">TTL</th>
                                <th class="px-4 py-4">Alamat</th>
                                <th class="px-4 py-4">Kontak</th>
                                <th class="px-4 py-4">Pendidikan</th>
                                <th class="px-4 py-4">Pengalaman</th>
                                <th class="px-4 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse ($allApplications as $application)
                                @php
                                    $profile = $application->candidateProfile;
                                    $education = $profile?->educations?->first();
                                    $experience = $profile?->experiences?->first();
                                @endphp
                                <tr class="align-top transition hover:bg-slate-50">
                                    <td class="px-4 py-5">
                                        @if($profile?->photo)
                                            <img src="{{ asset('storage/'.$profile->photo) }}" alt="Foto {{ $profile->full_name }}" class="h-16 w-16 rounded-2xl object-cover ring-2 ring-orange-100">
                                        @else
                                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-50 text-lg font-black text-orange-600">
                                                {{ strtoupper(substr($profile?->full_name ?? 'K', 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-5 text-sm font-bold text-slate-700">
                                        {{ $application->submitted_at?->format('d M Y') ?? $application->created_at?->format('d M Y') }}
                                        <p class="mt-1 text-[10px] font-black uppercase tracking-wide text-slate-400">{{ $application->application_number }}</p>
                                    </td>
                                    <td class="px-4 py-5 text-sm font-black text-slate-900">
                                        {{ $application->job?->title ?? '-' }}
                                        <p class="mt-1 text-xs font-semibold text-slate-500">{{ $application->job?->department?->name ?? '-' }}</p>
                                    </td>
                                    <td class="px-4 py-5 text-sm text-slate-700">
                                        <p class="font-black text-slate-950">{{ $profile?->full_name ?? '-' }}</p>
                                        <p class="mt-1 text-xs font-semibold text-slate-500">{{ $profile?->marital_status ?? '-' }}</p>
                                    </td>
                                    <td class="px-4 py-5 text-sm font-semibold text-slate-700">{{ $profile?->gender ?? '-' }}</td>
                                    <td class="px-4 py-5 text-sm font-semibold text-slate-700">{{ $profile?->nik ?? '-' }}</td>
                                    <td class="px-4 py-5 text-sm text-slate-700">
                                        <p>{{ $profile?->birth_place ?? '-' }}</p>
                                        <p class="mt-1 font-semibold">{{ $profile?->birth_date?->format('d M Y') ?? '-' }}</p>
                                    </td>
                                    <td class="px-4 py-5 text-sm leading-6 text-slate-700">
                                        <p class="min-w-64">{{ $profile?->address ?? '-' }}</p>
                                        <p>RT/RW: {{ $profile?->rt_rw ?? '-' }}</p>
                                        <p>{{ $profile?->village ?? '-' }}, {{ $profile?->district ?? '-' }}</p>
                                        <p>{{ $profile?->city ?? '-' }}, {{ $profile?->province ?? '-' }} {{ $profile?->postal_code ?? '' }}</p>
                                    </td>
                                    <td class="px-4 py-5 text-sm leading-6 text-slate-700">
                                        <p>Email: {{ $profile?->email ?? '-' }}</p>
                                        <p>Telp: {{ $profile?->phone ?? '-' }}</p>
                                        <p>WA: {{ $profile?->whatsapp ?? '-' }}</p>
                                    </td>
                                    <td class="px-4 py-5 text-sm leading-6 text-slate-700">
                                        <p class="font-black text-slate-900">{{ $education?->education_level ?? '-' }}</p>
                                        <p>{{ $education?->institution ?? '-' }}</p>
                                        <p>{{ $education?->major ?? '-' }} | IPK: {{ $education?->ipk ?? '-' }}</p>
                                        <p>Skill/Sertifikasi: {{ $education?->certification ?? '-' }}</p>
                                        <p>SIM: {{ $profile?->driving_license ?? '-' }}</p>
                                    </td>
                                    <td class="px-4 py-5 text-sm leading-6 text-slate-700">
                                        <p class="font-black text-slate-900">{{ $experience?->company_name ?? '-' }}</p>
                                        <p>{{ $experience?->position ?? '-' }}</p>
                                        <p>{{ $experience?->work_years ?? '-' }}</p>
                                    </td>
                                    <td class="px-4 py-5">
                                        <form method="POST" action="{{ route('admin.applications.status', $application) }}" class="min-w-56 space-y-3">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="w-full rounded-xl border-slate-200 text-xs font-bold text-slate-700 focus:border-orange-500 focus:ring-orange-500">
                                                @foreach($statusOptions as $status => $label)
                                                    <option value="{{ $status }}" @selected($application->status === $status)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" name="note" placeholder="Catatan opsional" class="w-full rounded-xl border-slate-200 text-xs font-semibold focus:border-orange-500 focus:ring-orange-500">
                                            <button type="submit" class="w-full rounded-xl bg-orange-500 px-3 py-2 text-xs font-black text-white transition hover:bg-orange-600">Update Status</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="px-5 py-12 text-center">
                                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-50 text-xl">👥</div>
                                        <p class="mt-4 text-lg font-black text-slate-950">Belum ada pelamar masuk.</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-600">Pelamar akan muncul otomatis setelah kandidat mengirim lamaran.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
'''
path.write_text(text[:start] + new + text[end:], encoding='utf-8')
