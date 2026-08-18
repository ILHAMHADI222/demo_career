<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'code'])]
class Department extends Model
{
    use SoftDeletes;

    public function divisions(): HasMany
    {
        return $this->hasMany(Division::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }
}
