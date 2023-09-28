<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        if (Auth::user()->banned) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->with('error', __('Your account is banned, please contact site admin.'));
        }
        $back_to = $request->back_to ?? url()->previous();
        session(['back_to' => str($back_to)->contains('password') ? '/' : $back_to]);
        return redirect()->intended(session('back_to', $request->headers->get('referer')));
    }
}
