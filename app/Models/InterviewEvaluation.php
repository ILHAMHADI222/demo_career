<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['interview_id', 'evaluator_id', 'criteria_scores', 'strengths', 'weaknesses', 'note', 'recommendation'])]
class InterviewEvaluation extends Model
{
    protected function casts(): array
    {
        return [
            'criteria_scores' => 'array',
        ];
    }

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}
