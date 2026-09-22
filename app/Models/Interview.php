<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['application_id', 'type', 'interviewer_id', 'scheduled_date', 'start_time', 'end_time', 'timezone', 'method', 'location', 'meeting_url', 'instruction', 'status'])]
class Interview extends Model
{
    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(InterviewEvaluation::class);
    }

    public function getStatusLabelAttribute(): string
    {
        $normalized = strtoupper(str_replace([' ', '-'], '_', $this->status ?? ''));

        return match ($normalized) {
            'SCHEDULED' => 'Terjadwal',
            'CONFIRMED' => 'Terkonfirmasi',
            'COMPLETED' => 'Selesai',
            'CANCELLED' => 'Dibatalkan',
            'RESCHEDULED' => 'Jadwal Ulang',
            'NO_SHOW' => 'Tidak Hadir',
            default => str($this->status)->replace('_', ' ')->headline()->toString(),
        };
    }
}
