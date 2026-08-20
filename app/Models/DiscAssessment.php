<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscAssessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'code',
        'question_bank_id',
        'question_bank_version',
        'duration_minutes',
        'start_at',
        'end_at',
        'randomize_questions',
        'randomize_options',
        'result_visibility',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'randomize_questions' => 'boolean',
            'randomize_options' => 'boolean',
        ];
    }

    public function questionBank(): BelongsTo
    {
        return $this->belongsTo(QuestionBank::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(DiscAssessmentParticipant::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(DiscAssessmentSession::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isPublished(): bool
    {
        return $this->status === 'PUBLISHED';
    }

    public function isAvailable(): bool
    {
        if ($this->status !== 'PUBLISHED') {
            return false;
        }

        $now = now();
        if ($this->start_at && $now->lt($this->start_at)) {
            return false;
        }
        if ($this->end_at && $now->gt($this->end_at)) {
            return false;
        }

        return true;
    }
}
