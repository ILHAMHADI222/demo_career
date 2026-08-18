<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'candidate_number', 'photo', 'full_name', 'nik', 'birth_place', 'birth_date', 'gender', 'marital_status', 'email', 'phone', 'whatsapp', 'address', 'rt_rw', 'village', 'district', 'province', 'city', 'postal_code', 'linkedin_url', 'portfolio_url', 'driving_license', 'profile_completion'])]
class CandidateProfile extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function educations(): HasMany { return $this->hasMany(CandidateEducation::class); }
    public function experiences(): HasMany { return $this->hasMany(CandidateExperience::class); }
    public function skills(): HasMany { return $this->hasMany(CandidateSkill::class); }
    public function documents(): HasMany { return $this->hasMany(CandidateDocument::class); }
    public function applications(): HasMany { return $this->hasMany(Application::class); }

    public function recalculateCompletion(): int
    {
        $fields = ['photo', 'full_name', 'nik', 'birth_place', 'birth_date', 'gender', 'marital_status', 'email', 'phone', 'whatsapp', 'address', 'rt_rw', 'village', 'district', 'province', 'city', 'postal_code', 'driving_license'];
        $filled = collect($fields)->filter(fn (string $field) => filled($this->{$field}))->count();

        $education = $this->educations()->first();
        $educationFields = ['education_level', 'institution', 'major', 'ipk', 'certification'];
        $educationFilled = $education
            ? collect($educationFields)->filter(fn (string $field) => filled($education->{$field}))->count()
            : 0;

        $experience = $this->experiences()->first();
        $experienceFields = ['company_name', 'position', 'work_years'];
        $experienceFilled = $experience
            ? collect($experienceFields)->filter(fn (string $field) => filled($experience->{$field}))->count()
            : 0;

        $totalFields = count($fields) + count($educationFields) + count($experienceFields);
        $this->profile_completion = (int) round((($filled + $educationFilled + $experienceFilled) / $totalFields) * 100);
        $this->save();

        return $this->profile_completion;
    }
}
