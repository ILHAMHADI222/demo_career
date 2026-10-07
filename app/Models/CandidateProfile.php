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

    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://') || str_starts_with($this->photo, 'data:image/')) {
            return $this->photo;
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->photo)) {
            return asset('storage/' . $this->photo);
        }

        return null;
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->photo_url) {
            return $this->photo_url;
        }

        $name = urlencode($this->full_name ?: ($this->user?->name ?: 'Kandidat'));
        return "https://ui-avatars.com/api/?name={$name}&background=f97316&color=ffffff&size=256&bold=true";
    }

    public function recalculateCompletion(): int
    {
        $fields = [
            'photo', 'full_name', 'nik', 'birth_place', 'birth_date', 'gender',
            'marital_status', 'email', 'phone', 'whatsapp', 'address', 'rt_rw',
            'village', 'district', 'province', 'city'
        ];
        $filled = collect($fields)->filter(fn (string $field) => filled($this->{$field}))->count();

        $education = $this->educations()->first();
        $educationFields = ['education_level', 'institution', 'major', 'ipk'];
        $educationFilled = $education
            ? collect($educationFields)->filter(fn (string $field) => filled($education->{$field}))->count()
            : 0;

        $totalFields = count($fields) + count($educationFields);
        $this->profile_completion = (int) min(100, round((($filled + $educationFilled) / $totalFields) * 100));
        $this->save();

        return $this->profile_completion;
    }
}
