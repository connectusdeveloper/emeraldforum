<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

class RegisterController extends RegisteredUserController
{
    public function __invoke(Request $request)
    {
        $settings = site_config();
        if (($settings['registration'] ?? null) != 1) {
            return to_route('threads')->with('error', __('Registration is disabled.'));
        }
        return parent::create($request);
    }
}
