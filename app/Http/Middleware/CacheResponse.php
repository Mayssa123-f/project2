<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class CacheResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $key = 'response|' . $request->fullUrl();

        if (Cache::has($key)) {
            return response(Cache::get($key));
        }

        $response = $next($request);

        Cache::put($key, $response->getContent(), 60); // cache 60 seconds

        return $response;
    }
}
