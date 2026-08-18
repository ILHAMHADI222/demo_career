<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['application_id', 'created_by', 'type', 'note'])]
class HrNote extends Model
{
    public function application(): BelongsTo { return $this->belongsTo(Application::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
}
