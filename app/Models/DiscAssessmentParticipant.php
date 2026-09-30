<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiscAssessmentParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'disc_assessment_id',
        'user_id',
        'application_id',
        'max_attempts',
        'status',
        'invited_at',
    ];

    protected function casts(): array
    {
        return [
            'max_attempts' => 'integer',
            'invited_at' => 'datetime',
        ];
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(DiscAssessment::class, 'disc_assessment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(DiscAssessmentSession::class)->orderBy('attempt_number');
    }

    public function latestSession()
    {
        return $this->hasOne(DiscAssessmentSession::class)->latestOfMany();
    }

    public function isInvitationExpired(): bool
    {
        $invitedAt = $this->invited_at ?? $this->created_at;
        if (!$invitedAt) {
            return false;
        }

        return now()->greaterThan($invitedAt->copy()->addHours(24));
    }

    public function getInvitationExpiresAtAttribute(): ?\Carbon\Carbon
    {
        $invitedAt = $this->invited_at ?? $this->created_at;
        return $invitedAt ? $invitedAt->copy()->addHours(24) : null;
    }

    public function getRemainingInvitationHoursAttribute(): int
    {
        $expiresAt = $this->invitation_expires_at;
        if (!$expiresAt || now()->greaterThan($expiresAt)) {
            return 0;
        }

        return (int) ceil(now()->diffInMinutes($expiresAt, false) / 60);
    }
}
