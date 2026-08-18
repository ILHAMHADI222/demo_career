<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['title', 'slug', 'job_code', 'department_id', 'division_id', 'position_id', 'location', 'employment_type', 'work_arrangement', 'number_of_openings', 'minimum_education', 'minimum_experience', 'description', 'responsibilities', 'requirements', 'required_skills', 'benefits', 'salary_min', 'salary_max', 'show_salary', 'registration_start_at', 'deadline_at', 'recruiter_id', 'status'])]
class Job extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'required_skills' => 'array',
            'show_salary' => 'boolean',
            'registration_start_at' => 'datetime',
            'deadline_at' => 'datetime',
        ];
    }

    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function division(): BelongsTo { return $this->belongsTo(Division::class); }
    public function position(): BelongsTo { return $this->belongsTo(Position::class); }
    public function recruiter(): BelongsTo { return $this->belongsTo(User::class, 'recruiter_id'); }
    public function questions(): HasMany { return $this->hasMany(JobQuestion::class)->orderBy('order'); }
    public function applications(): HasMany { return $this->hasMany(Application::class); }

    public function isOpen(): bool
    {
        return $this->status === 'PUBLISHED'
            && (! $this->registration_start_at || $this->registration_start_at->isPast())
            && (! $this->deadline_at || $this->deadline_at->endOfDay()->isFuture());
    }
}
