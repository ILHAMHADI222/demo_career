<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['job_id', 'question', 'type', 'required', 'options', 'order'])]
class JobQuestion extends Model
{
    protected function casts(): array
    {
        return ['required' => 'boolean', 'options' => 'array'];
    }

    public function job(): BelongsTo { return $this->belongsTo(Job::class); }
}
