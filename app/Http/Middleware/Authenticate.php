<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    protected function redirectTo(Request $request): ?string
    {
        Log::info('Redirecting to login', ['url' => $request->url()]);

        if ($request->is('products*') || $request->is('/')) {
            return null;
        }

        return $request->expectsJson() ? null : route('login');
    }
}
