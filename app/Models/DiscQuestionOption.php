<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscQuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'disc_question_id',
        'option_number',
        'option_label',
        'statement',
        'most_mapping',
        'least_mapping',
        'display_order',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'option_number' => 'integer',
            'display_order' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(DiscQuestion::class, 'disc_question_id');
    }
}
