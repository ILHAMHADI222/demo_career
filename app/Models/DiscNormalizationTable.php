<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscNormalizationTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'version',
        'chart_type',
        'raw_score',
        'd_score',
        'i_score',
        's_score',
        'c_score',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'raw_score' => 'integer',
            'd_score' => 'float',
            'i_score' => 'float',
            's_score' => 'float',
            'c_score' => 'float',
            'is_active' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
