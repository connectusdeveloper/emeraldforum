<?php

namespace Tecdiary\Installer\Http\Middleware;

use Closure;

class RedirectIfNotInstalled
{
    public function handle($request, Closure $next)
    {
        if (false == env('APP_INSTALLED', false)) {
            return redirect('/install');
        }

        return $next($request);
    }
}
