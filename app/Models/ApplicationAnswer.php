<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['application_id', 'job_question_id', 'answer'])]
class ApplicationAnswer extends Model
{
    protected function casts(): array
    {
        return ['answer' => 'array'];
    }

    public function application(): BelongsTo { return $this->belongsTo(Application::class); }
    public function question(): BelongsTo { return $this->belongsTo(JobQuestion::class, 'job_question_id'); }
}
