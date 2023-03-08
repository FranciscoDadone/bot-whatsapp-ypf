<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Permissions {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param string $permission
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission) {
        if (!hasPermission($permission)) return abort(404, 'Not Found');
        return $next($request);
    }
}
