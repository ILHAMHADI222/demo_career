<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscAssessmentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'disc_assessment_session_id',
        'disc_question_id',
        'most_option_id',
        'least_option_id',
        'most_selected_at',
        'least_selected_at',
        'is_autosaved',
    ];

    protected function casts(): array
    {
        return [
            'is_autosaved' => 'boolean',
            'most_selected_at' => 'datetime',
            'least_selected_at' => 'datetime',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(DiscAssessmentSession::class, 'disc_assessment_session_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(DiscQuestion::class, 'disc_question_id');
    }

    public function mostOption(): BelongsTo
    {
        return $this->belongsTo(DiscQuestionOption::class, 'most_option_id');
    }

    public function leastOption(): BelongsTo
    {
        return $this->belongsTo(DiscQuestionOption::class, 'least_option_id');
    }
}
