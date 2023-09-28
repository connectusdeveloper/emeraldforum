<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;

class ReplyVerificationController extends Controller
{
    public function __invoke(Request $request, $reply_id)
    {
        if (!$request->hasValidSignature()) {
            abort(404);
        }

        $reply = Reply::withoutGlobalScopes()->findOrFail($reply_id);
        $reply->guest_verified = now();
        $reply->save();
        return to_route('threads.show', $reply->thread->slug)->with('message', __('Your reply has been posted.'));
    }
}
