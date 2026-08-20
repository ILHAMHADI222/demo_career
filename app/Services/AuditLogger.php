<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public static function log(string $action, string $module, ?Model $entity = null, array $old = [], array $new = [], ?Request $request = null): AuditLog
    {
        return AuditLog::query()->create([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => $module,
            'entity_type' => $entity ? $entity::class : null,
            'entity_id' => $entity?->getKey(),
            'old_value' => $old ?: null,
            'new_value' => $new ?: null,
            'ip_address' => $request ? $request->ip() : request()?->ip(),
            'user_agent' => $request ? $request->userAgent() : request()?->userAgent(),
        ]);
    }
}
