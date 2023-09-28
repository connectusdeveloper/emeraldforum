<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

class LogoutController extends AuthenticatedSessionController
{
    public function __invoke(Request $request)
    {
        auth()->user()->offsetUnset('meta');
        return parent::destroy($request);
    }
}
