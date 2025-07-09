<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $sanitized = array_map(function ($value) {
            return is_string($value) ? strip_tags($value) : $value;
        }, $request->all());
        $request->merge($sanitized);
        return $next($request);
    }
}
