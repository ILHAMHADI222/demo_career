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
        'current_stage',
        'current_question_index',
        'started_at',
        'last_activity_at',
        'completed_at',
        'stage_1_started_at',
        'stage_1_completed_at',
        'stage_2_started_at',
        'stage_2_completed_at',
        'stage_3_started_at',
        'stage_3_completed_at',
        'stage_1_answers_json',
        'stage_2_answers_json',
        'stage_1_score',
        'stage_2_score',
        'stage_1_correct_count',
        'stage_2_correct_count',
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
            'current_stage' => 'integer',
            'current_question_index' => 'integer',
            'stage_1_score' => 'float',
            'stage_2_score' => 'float',
            'stage_1_correct_count' => 'integer',
            'stage_2_correct_count' => 'integer',
            'security_score' => 'integer',
            'started_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'completed_at' => 'datetime',
            'stage_1_started_at' => 'datetime',
            'stage_1_completed_at' => 'datetime',
            'stage_2_started_at' => 'datetime',
            'stage_2_completed_at' => 'datetime',
            'stage_3_started_at' => 'datetime',
            'stage_3_completed_at' => 'datetime',
            'stage_1_answers_json' => 'array',
            'stage_2_answers_json' => 'array',
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

    public function getActualStartedAtAttribute(): ?\Carbon\Carbon
    {
        return $this->started_at ?? $this->stage_1_started_at ?? $this->created_at;
    }

    public function getActualCompletedAtAttribute(): ?\Carbon\Carbon
    {
        return $this->completed_at ?? $this->stage_3_completed_at;
    }

    public function getStartedAtWibAttribute(): ?\Carbon\Carbon
    {
        return $this->actual_started_at?->copy()->setTimezone('Asia/Jakarta');
    }

    public function getCompletedAtWibAttribute(): ?\Carbon\Carbon
    {
        return $this->actual_completed_at?->copy()->setTimezone('Asia/Jakarta');
    }

    public function getFormattedTimeRangeWibAttribute(): string
    {
        $start = $this->started_at_wib;
        $end = $this->completed_at_wib;

        if ($start && $end) {
            return $start->format('H:i:s') . ' s/d ' . $end->format('H:i:s') . ' WIB';
        }

        if ($start) {
            return $start->format('H:i:s') . ' WIB (Sedang Berjalan)';
        }

        return '-';
    }

    public function getRemainingSecondsAttribute(): int
    {
        if ($this->isCompleted()) {
            return 0;
        }

        $durationSeconds = ((int) ($this->assessment?->duration_minutes ?: 10)) * 60;
        $started = $this->actual_started_at;
        if (!$started) {
            return $durationSeconds;
        }

        $elapsed = max(0, now()->timestamp - $started->timestamp);
        return max(0, $durationSeconds - $elapsed);
    }
}
