<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['candidate_profile_id', 'company_name', 'position', 'work_years', 'employment_type', 'location', 'start_date', 'end_date', 'currently_working', 'job_description', 'reason_for_leaving'])]
class CandidateExperience extends Model
{
    protected $table = 'candidate_experiences';

    public function candidateProfile(): BelongsTo
    {
        return $this->belongsTo(CandidateProfile::class);
    }
}
