<?php

namespace Lararole\Http\Middleware;

use Closure;
use Lararole\Models\Module;

class ModuleHasReadPermission
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
        $module = Module::whereSlug(request()->route()->parameter('module_slug'))->first();
        if ($module->user_has_permission()) {
            $request['module'] = $module;

            return $next($request);
        }

        if (! $request->expectsJson()) {
            return redirect()->route('access_denied');
        }

        return response()->json([
            'message' => 'Access denied',
        ], 403);
    }
}
