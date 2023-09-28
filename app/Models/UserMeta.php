<?php

namespace App\Models;

class UserMeta extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $table = 'usermeta';

    public function scopeOfUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
