<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Flag extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function record(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeBy($query, User $user)
    {
        $query->where('user_id', $user->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Flag $flag) {
            $flag->user_id = auth()->id();
        });
    }
}
