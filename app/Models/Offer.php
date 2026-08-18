<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['application_id', 'position', 'employment_type', 'salary', 'allowances', 'benefits', 'start_date', 'contract_duration', 'probation_duration', 'offer_letter', 'sent_at', 'expired_at', 'candidate_response', 'candidate_response_at', 'note', 'status'])]
class Offer extends Model
{
    protected function casts(): array
    {
        return [
            'allowances' => 'array',
            'benefits' => 'array',
            'start_date' => 'date',
            'sent_at' => 'datetime',
            'expired_at' => 'datetime',
            'candidate_response_at' => 'datetime',
            'salary' => 'decimal:2',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
