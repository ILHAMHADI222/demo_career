<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscAssessmentSecurityEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'disc_assessment_session_id',
        'event_type',
        'metadata_json',
        'occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata_json' => 'array',
            'occurred_at' => 'datetime',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(DiscAssessmentSession::class, 'disc_assessment_session_id');
    }

    public function getOccurredAtWibAttribute(): ?\Carbon\Carbon
    {
        $time = $this->occurred_at ?? $this->created_at;
        return $time?->copy()->setTimezone('Asia/Jakarta');
    }
}
