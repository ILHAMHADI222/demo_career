<?php

namespace App\Models;

use App\Models\Traits\HasRolesAndPermissions;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'registration_otp', 'registration_otp_expires_at', 'allowed_stages'])]
#[Hidden(['password', 'remember_token', 'registration_otp'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRolesAndPermissions, Notifiable;

    public function candidateProfile(): HasOne
    {
        return $this->hasOne(CandidateProfile::class);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->candidateProfile?->photo_url;
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->photo_url) {
            return $this->photo_url;
        }

        $name = urlencode($this->name ?: 'User');
        return "https://ui-avatars.com/api/?name={$name}&background=f97316&color=ffffff&size=256&bold=true";
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'registration_otp_expires_at' => 'datetime',
            'password' => 'hashed',
            'allowed_stages' => 'array',
        ];
    }

    public function getAllowedStages(): array
    {
        if (is_array($this->allowed_stages) && count($this->allowed_stages) > 0) {
            return array_map('intval', $this->allowed_stages);
        }

        return [1, 2, 3];
    }

    public function canTakeStage(int $stage): bool
    {
        return in_array($stage, $this->getAllowedStages(), true);
    }
}
