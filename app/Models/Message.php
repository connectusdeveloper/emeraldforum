<?php

namespace App\Models;

use App\Models\Traits\Paginatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;
    use Paginatable;

    protected $fillable = ['body', 'conversation_id', 'user_id'];
    protected $with = ['user:id,name,username,profile_photo_path'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function scopeOfUser($query, $user_id = null)
    {
        $query->where('user_id', $user_id ?? auth()->id());
    }

    public function scopeSeen($query)
    {
        $query->where('seen', 1);
    }

    public function scopeUnseen($query)
    {
        $query->whereNull('seen')->orWhere('seen', 0);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
