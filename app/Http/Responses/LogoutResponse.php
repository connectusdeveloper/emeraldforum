<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse($request)
    {
        $back_to = $request->back_to ?? url()->previous();
        session(['back_to' => str($back_to)->contains('password') ? '/' : $back_to]);
        return redirect()->intended(session('back_to', $request->headers->get('referer')));
    }
}
