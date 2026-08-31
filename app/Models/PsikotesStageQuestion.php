<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PsikotesStageQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'question_bank_id',
        'stage',
        'question_number',
        'question_text',
        'options_json',
        'correct_answer_1',
        'correct_answer_2',
        'explanation',
        'active',
    ];

    protected $casts = [
        'options_json' => 'array',
        'active' => 'boolean',
        'question_number' => 'integer',
    ];

    public function questionBank(): BelongsTo
    {
        return $this->belongsTo(QuestionBank::class);
    }
}
