<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __invoke(Request $request, Conversation $conversation)
    {
        $per_page = site_config('per_page') ?? 10;
        $offset = ($request->page - 1) * $per_page;
        return $conversation->messages()->with('user:id,name,username,profile_photo_path')->latest('id')->offset($offset)->limit($per_page)->get()->transform(function ($message) {
            $message->time = $message->created_at->diffForHumans();
            return $message;
        });
    }
}
