<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionBank extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'assessment_type',
        'version',
        'status',
        'created_by',
        'updated_by',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(DiscQuestion::class)->orderBy('question_number');
    }

    public function activeQuestions(): HasMany
    {
        return $this->hasMany(DiscQuestion::class)->where('active', true)->orderBy('question_number');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(DiscAssessment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function isPublished(): bool
    {
        return $this->status === 'PUBLISHED';
    }

    public function isDraft(): bool
    {
        return $this->status === 'DRAFT';
    }
}
