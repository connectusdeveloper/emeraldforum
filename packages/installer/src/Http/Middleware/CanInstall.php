<?php

namespace Tecdiary\Installer\Http\Middleware;

use Closure;

class CanInstall
{
    public function handle($request, Closure $next)
    {
        if (true == env('APP_INSTALLED', false)) {
            return redirect('/');
        }

        return $next($request);
    }
}
