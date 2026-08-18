<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['candidate_profile_id', 'education_level', 'institution', 'major', 'ipk', 'certification', 'start_year', 'graduation_year', 'gpa', 'status', 'description'])]
class CandidateEducation extends Model
{
    protected $table = 'candidate_educations';

    public function candidateProfile(): BelongsTo
    {
        return $this->belongsTo(CandidateProfile::class);
    }
}
