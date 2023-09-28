<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserVote extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeBy($query, User $user)
    {
        $query->where('user_id', $user->id);
    }

    public function voteable(): MorphTo
    {
        return $this->morphTo();
    }
}
