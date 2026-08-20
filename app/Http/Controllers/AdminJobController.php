<?php

namespace App\Http\Controllers;

use App\Enums\CareerEnums;
use App\Models\Department;
use App\Models\Job;
use App\Services\NumberGenerator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminJobController extends Controller
{
    public function index(Request $request): View
    {
        Job::query()
            ->where('status', 'PUBLISHED')
            ->whereNotNull('deadline_at')
            ->where('deadline_at', '<', now())
            ->update(['status' => 'CLOSED']);

        $jobs = Job::query()
            ->with(['department', 'recruiter'])
            ->when($request->string('keyword')->toString(), fn ($query, $keyword) => $query->where(fn ($q) => $q->where('title', 'like', "%{$keyword}%")->orWhere('job_code', 'like', "%{$keyword}%")->orWhere('location', 'like', "%{$keyword}%")))
            ->when($request->string('status')->toString(), fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.jobs.index', [
            'jobs' => $jobs,
            'statuses' => CareerEnums::JOB_STATUSES,
        ]);
    }

    public function create(): View
    {
        return $this->form(new Job(['status' => 'DRAFT', 'show_salary' => false]), 'admin.jobs.create');
    }

    public function store(Request $request, NumberGenerator $numberGenerator): RedirectResponse
    {
        $data = $this->validated($request);
        $data = $this->resolveDepartment($data);
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['job_code'] = $numberGenerator->jobCode();
        $data['recruiter_id'] = auth()->id();
        $data['required_skills'] = $this->skillsToArray($data['required_skills'] ?? null);

        Job::query()->create($data);

        return redirect()->route('admin.jobs.index')->with('success', 'Lowongan berhasil dibuat.');
    }

    public function edit(Job $job): View
    {
        return $this->form($job, 'admin.jobs.edit');
    }

    public function update(Request $request, Job $job): RedirectResponse
    {
        $data = $this->validated($request);
        $data = $this->resolveDepartment($data);
        $data['required_skills'] = $this->skillsToArray($data['required_skills'] ?? null);

        $job->update($data);

        return redirect()->route('admin.jobs.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function publish(Job $job): RedirectResponse
    {
        $job->update(['status' => 'PUBLISHED']);

        return back()->with('success', 'Lowongan berhasil dipublikasikan.');
    }

    public function close(Job $job): RedirectResponse
    {
        $job->update(['status' => 'CLOSED']);

        return back()->with('success', 'Lowongan berhasil ditutup.');
    }

    public function destroy(Job $job): RedirectResponse
    {
        $job->delete();

        return back()->with('success', 'Lowongan berhasil dihapus.');
    }

    private function form(Job $job, string $view): View
    {
        return view($view, [
            'job' => $job,
            'departments' => Department::query()->orderBy('name')->get(),
            'statuses' => CareerEnums::JOB_STATUSES,
        ]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'new_department_name' => ['nullable', 'string', 'max:255', 'unique:departments,name'],
            'new_department_code' => ['nullable', 'string', 'max:50', 'unique:departments,code'],
            'location' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'string', 'max:80'],
            'work_arrangement' => ['required', 'string', 'max:80'],
            'number_of_openings' => ['required', 'integer', 'min:1'],
            'minimum_education' => ['nullable', 'string', 'max:255'],
            'minimum_experience' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'responsibilities' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'required_skills' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0'],
            'show_salary' => ['nullable', 'boolean'],
            'registration_start_at' => ['nullable', 'date'],
            'deadline_at' => ['nullable', 'date', 'after_or_equal:registration_start_at'],
            'recruiter_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', Rule::in(CareerEnums::JOB_STATUSES)],
        ]);
    }

    private function resolveDepartment(array $data): array
    {
        if (! empty($data['new_department_name'])) {
            $code = $data['new_department_code'] ?: Str::upper(Str::slug($data['new_department_name'], '_'));

            $department = Department::query()->create([
                'name' => $data['new_department_name'],
                'code' => $code,
            ]);

            $data['department_id'] = $department->id;
        }

        unset($data['new_department_name'], $data['new_department_code']);

        return $data;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 2;

        while (Job::query()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    private function skillsToArray(?string $skills): ?array
    {
        if (! $skills) {
            return null;
        }

        return collect(explode(',', $skills))
            ->map(fn (string $skill) => trim($skill))
            ->filter()
            ->values()
            ->all();
    }
}
