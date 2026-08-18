<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['application_id', 'test_type', 'title', 'instruction', 'test_url', 'scheduled_at', 'deadline_at', 'score', 'maximum_score', 'passing_score', 'result', 'note'])]
class CandidateTest extends Model
{
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'deadline_at' => 'datetime',
            'score' => 'decimal:2',
            'maximum_score' => 'decimal:2',
            'passing_score' => 'decimal:2',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
