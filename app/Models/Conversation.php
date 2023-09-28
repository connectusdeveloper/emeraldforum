<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['subject', 'user_id', 'receiver_id', 'updated_at'];
    protected $with = ['lastMessage', 'receiver:id,name,username'];

    public function lastMessage()
    {
        return $this->messages()->one()->latestOfMany();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
