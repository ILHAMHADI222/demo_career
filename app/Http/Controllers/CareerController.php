<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Job;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function home(): View
    {
        $jobs = Job::query()->where('status', 'PUBLISHED')->latest()->limit(6)->get();
        $departments = Department::query()->withCount('jobs')->orderBy('name')->get();

        return view('career.home', compact('jobs', 'departments'));
    }

    public function jobs(Request $request): View
    {
        $jobs = Job::query()
            ->with('department')
            ->where('status', 'PUBLISHED')
            ->when($request->string('keyword')->toString(), fn ($query, $keyword) => $query->where(fn ($q) => $q->where('title', 'like', "%{$keyword}%")->orWhere('description', 'like', "%{$keyword}%")))
            ->when($request->string('department')->toString(), fn ($query, $department) => $query->whereHas('department', fn ($q) => $q->where('code', $department)))
            ->when($request->string('location')->toString(), fn ($query, $location) => $query->where('location', 'like', "%{$location}%"))
            ->when($request->string('employment_type')->toString(), fn ($query, $type) => $query->where('employment_type', $type))
            ->when($request->string('work_arrangement')->toString(), fn ($query, $arrangement) => $query->where('work_arrangement', $arrangement))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $departments = Department::query()->orderBy('name')->get();

        return view('career.jobs', compact('jobs', 'departments'));
    }

    public function show(Job $job): View
    {
        abort_unless($job->status === 'PUBLISHED', 404);
        $job->load(['department', 'division', 'position', 'questions']);

        return view('career.job-detail', compact('job'));
    }
}
