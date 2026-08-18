@csrf
<div class="grid gap-5 lg:grid-cols-2">
    <div class="lg:col-span-2">
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Judul Lowongan</label>
        <input name="title" value="{{ old('title', $job->title) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100" required>
    </div>

    <div>
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Departemen</label>
        <select name="department_id" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
            <option value="">Pilih departemen</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" @selected(old('department_id', $job->department_id) == $department->id)>{{ $department->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Lokasi</label>
        <input name="location" value="{{ old('location', $job->location) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100" required>
    </div>

    <div>
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Tipe Kerja</label>
        <input name="employment_type" value="{{ old('employment_type', $job->employment_type ?? 'FULL_TIME') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100" required>
    </div>

    <div>
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Sistem Kerja</label>
        <input name="work_arrangement" value="{{ old('work_arrangement', $job->work_arrangement ?? 'ONSITE') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100" required>
    </div>

    <div>
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Jumlah Dibutuhkan</label>
        <input name="number_of_openings" type="number" min="1" value="{{ old('number_of_openings', $job->number_of_openings ?? 1) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100" required>
    </div>

    <div>
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Status</label>
        <select name="status" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100" required>
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $job->status ?? 'DRAFT') === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Pendidikan Minimal</label>
        <input name="minimum_education" value="{{ old('minimum_education', $job->minimum_education) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
    </div>

    <div>
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Pengalaman Minimal</label>
        <input name="minimum_experience" value="{{ old('minimum_experience', $job->minimum_experience) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
    </div>

    <div>
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Tanggal Mulai Registrasi</label>
        <input name="registration_start_at" type="datetime-local" value="{{ old('registration_start_at', optional($job->registration_start_at)->format('Y-m-d\TH:i')) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
    </div>

    <div>
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Deadline</label>
        <input name="deadline_at" type="datetime-local" value="{{ old('deadline_at', optional($job->deadline_at)->format('Y-m-d\TH:i')) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
    </div>

    <div class="lg:col-span-2">
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Deskripsi</label>
        <textarea name="description" rows="5" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100" required>{{ old('description', $job->description) }}</textarea>
    </div>

    <div>
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Tanggung Jawab</label>
        <textarea name="responsibilities" rows="5" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100">{{ old('responsibilities', $job->responsibilities) }}</textarea>
    </div>

    <div>
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Persyaratan</label>
        <textarea name="requirements" rows="5" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100">{{ old('requirements', $job->requirements) }}</textarea>
    </div>

    <div>
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Skill (pisahkan koma)</label>
        <input name="required_skills" value="{{ old('required_skills', is_array($job->required_skills) ? implode(', ', $job->required_skills) : $job->required_skills) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
    </div>

    <div>
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Benefit</label>
        <input name="benefits" value="{{ old('benefits', $job->benefits) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
    </div>

    <div>
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Gaji Minimal</label>
        <input name="salary_min" type="number" min="0" value="{{ old('salary_min', $job->salary_min) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
    </div>

    <div>
        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Gaji Maksimal</label>
        <input name="salary_max" type="number" min="0" value="{{ old('salary_max', $job->salary_max) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
    </div>
</div>

<label class="mt-5 flex items-center gap-3 rounded-2xl bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 ring-1 ring-slate-200">
    <input type="hidden" name="show_salary" value="0">
    <input type="checkbox" name="show_salary" value="1" @checked(old('show_salary', $job->show_salary))>
    Tampilkan gaji di halaman lowongan
</label>

<div class="mt-8 flex flex-wrap gap-3">
    <button class="rounded-2xl bg-orange-500 px-6 py-3 text-sm font-black text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">Simpan Lowongan</button>
    <a href="{{ route('admin.jobs.index') }}" class="rounded-2xl border border-slate-200 bg-white px-6 py-3 text-sm font-black text-slate-700 transition hover:bg-slate-50">Kembali</a>
</div>
