<?php

namespace App\Models\Traits;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRolesAndPermissions
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', strtoupper($role));
    }

    public function hasAnyRole(array $roles): bool
    {
        $roles = array_map('strtoupper', $roles);

        return $this->roles->pluck('name')->intersect($roles)->isNotEmpty();
    }

    public function permissions(): array
    {
        return $this->roles()
            ->with('permissions:id,name')
            ->get()
            ->flatMap(fn (Role $role) => $role->permissions->pluck('name'))
            ->unique()
            ->values()
            ->all();
    }

    public function canDo(string $permission): bool
    {
        return $this->hasRole('SUPERADMIN') || in_array($permission, $this->permissions(), true);
    }

    public function assignRole(string $role): void
    {
        $roleModel = Role::query()->where('name', strtoupper($role))->firstOrFail();
        $this->roles()->syncWithoutDetaching([$roleModel->id]);
    }
}
