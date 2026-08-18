<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\CandidateProfile;
use App\Models\Application;
use Illuminate\Http\Request;

class AdminStatisticsController extends Controller
{
    public function index()
    {
        $totalJobs = Job::count();
        $totalCandidates = CandidateProfile::count();
        $totalApplications = Application::count();

        return view('admin.statistics', [
            'totalJobs' => $totalJobs,
            'totalCandidates' => $totalCandidates,
            'totalApplications' => $totalApplications,
        ]);
    }
}