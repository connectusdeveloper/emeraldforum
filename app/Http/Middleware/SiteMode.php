<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SiteMode
{
    public function handle(Request $request, Closure $next)
    {
        $settings = site_config();
        app()->setlocale(session('language', ($settings['language'] ?? null) ?: 'en'));

        if (str($request?->path() ?? '')->contains(['login', 'password', 'two-factor'])) {
            return $next($request);
        }

        $user = auth()->user();
        if ($user && $user->hasRole('super')) {
            return $next($request);
        }

        $mode = $settings['mode'] ?? null;
        if ('Private' == $mode && !$user) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
