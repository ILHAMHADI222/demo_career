<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'question_bank_id',
        'question_number',
        'instruction',
        'active',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'question_number' => 'integer',
        ];
    }

    public function questionBank(): BelongsTo
    {
        return $this->belongsTo(QuestionBank::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(DiscQuestionOption::class)->orderBy('display_order')->orderBy('option_number');
    }

    public function activeOptions(): HasMany
    {
        return $this->hasMany(DiscQuestionOption::class)->where('active', true)->orderBy('display_order')->orderBy('option_number');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
