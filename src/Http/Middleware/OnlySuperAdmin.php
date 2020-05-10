<?php

namespace Lararole\Http\Middleware;

use Closure;
use Lararole\Models\Module;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OnlySuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! auth()->check()) {
            throw new HttpException(401, 'Unauthenticated!');
        }

        if (auth()->user()->isSuperAdmin()) {
            return $next($request);
        }

        if (! $request->expectsJson()) {
            return redirect()->route('access.denied');
        }

        throw new HttpException(403, 'Access Denied!');
    }
}
