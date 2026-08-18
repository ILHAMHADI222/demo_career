<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'name', 'is_required'])]
class DocumentType extends Model
{
    protected function casts(): array
    {
        return ['is_required' => 'boolean'];
    }
}
