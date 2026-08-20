<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiscAssessmentSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'disc_assessment_id',
        'disc_assessment_participant_id',
        'participant_id',
        'attempt_number',
        'session_token',
        'status',
        'current_question_index',
        'started_at',
        'last_activity_at',
        'completed_at',
        'ip_address',
        'user_agent',
        'question_order_json',
        'option_order_json',
        'question_snapshot_json',
        'scoring_snapshot_json',
        'raw_scores_json',
        'normalized_scores_json',
        'disc_profile_code',
        'disc_profile_name',
        'interpretation_json',
        'security_score',
    ];

    protected function casts(): array
    {
        return [
            'attempt_number' => 'integer',
            'current_question_index' => 'integer',
            'security_score' => 'integer',
            'started_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'completed_at' => 'datetime',
            'question_order_json' => 'array',
            'option_order_json' => 'array',
            'question_snapshot_json' => 'array',
            'scoring_snapshot_json' => 'array',
            'raw_scores_json' => 'array',
            'normalized_scores_json' => 'array',
            'interpretation_json' => 'array',
        ];
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(DiscAssessment::class, 'disc_assessment_id');
    }

    public function assessmentParticipant(): BelongsTo
    {
        return $this->belongsTo(DiscAssessmentParticipant::class, 'disc_assessment_participant_id');
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(DiscAssessmentAnswer::class);
    }

    public function securityEvents(): HasMany
    {
        return $this->hasMany(DiscAssessmentSecurityEvent::class)->orderByDesc('occurred_at');
    }

    public function isCompleted(): bool
    {
        return $this->status === 'COMPLETED';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'IN_PROGRESS';
    }

    public function getRemainingSecondsAttribute(): int
    {
        if ($this->isCompleted()) {
            return 0;
        }

        $durationSeconds = ((int) ($this->assessment?->duration_minutes ?: 10)) * 60;
        if (!$this->started_at) {
            return $durationSeconds;
        }

        $elapsed = max(0, now()->timestamp - $this->started_at->timestamp);
        return max(0, $durationSeconds - $elapsed);
    }
}
