<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_code',
        'profile_name',
        'description',
        'strengths',
        'potential_limitations',
        'communication_style',
        'work_style',
        'development_suggestions',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
