<?php

namespace App\Models;

use App\Enums\CareerEnums;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use InvalidArgumentException;

#[Fillable(['application_number', 'job_id', 'candidate_profile_id', 'status', 'current_step', 'score', 'submitted_at', 'withdraw_reason'])]
class Application extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return ['submitted_at' => 'datetime', 'score' => 'decimal:2'];
    }

    public function job(): BelongsTo { return $this->belongsTo(Job::class); }
    public function candidateProfile(): BelongsTo { return $this->belongsTo(CandidateProfile::class); }
    public function answers(): HasMany { return $this->hasMany(ApplicationAnswer::class); }
    public function statusHistories(): HasMany { return $this->hasMany(ApplicationStatusHistory::class)->orderBy('changed_at'); }
    public function interviews(): HasMany { return $this->hasMany(Interview::class); }
    public function tests(): HasMany { return $this->hasMany(CandidateTest::class); }
    public function hrNotes(): HasMany { return $this->hasMany(HrNote::class); }
    public function offer(): HasOne { return $this->hasOne(Offer::class); }

    public function canTransitionTo(string $toStatus): bool
    {
        return in_array($toStatus, CareerEnums::APPLICATION_TRANSITIONS[$this->status] ?? [], true);
    }

    public function transitionTo(string $toStatus, ?User $actor = null, ?string $reason = null, ?string $note = null): void
    {
        if (! $this->canTransitionTo($toStatus)) {
            throw new InvalidArgumentException("Transisi status {$this->status} ke {$toStatus} tidak valid.");
        }

        $fromStatus = $this->status;
        $this->forceFill(['status' => $toStatus])->save();

        $this->statusHistories()->create([
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'changed_by' => $actor?->id,
            'reason' => $reason,
            'note' => $note,
            'changed_at' => now(),
        ]);
    }
}
