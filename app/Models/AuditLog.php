<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'action', 'module', 'entity_type', 'entity_id', 'old_value', 'new_value', 'ip_address', 'user_agent'])]
class AuditLog extends Model
{
    protected function casts(): array
    {
        return ['old_value' => 'array', 'new_value' => 'array'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
