<?php

namespace Lararole\Http\Middleware;

use Closure;
use Lararole\Models\Module;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        if (! auth()->check()) {
            throw new HttpException(401, 'Unauthenticated!');
        }

        $module_slug = $request->route() ? $request->route()->parameter('module_slug') : $request->module_slug;

        $module = Module::whereSlug($module_slug)->first();

        if (! $module) {
            throw new HttpException(404, 'Module not found');
        }

        if ($module->user_has_permission()) {
            $request['module'] = $module;

            return $next($request);
        }

        if (! $request->expectsJson()) {
            return redirect()->route('access_denied');
        }

        throw new HttpException(403, 'Access Denied!');
    }
}
