<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (! $request->user() || ! $request->user()->canDo($permission)) {
            abort(403, 'Anda tidak memiliki permission yang dibutuhkan.');
        }

        return $next($request);
    }
}
