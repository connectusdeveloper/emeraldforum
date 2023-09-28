<?php

namespace App\Models;

use App\Helpers\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invitation extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = ['code', 'email', 'accepted_at', 'accepted_by', 'user_id'];

    public function acceptedBy()
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Invitation $invitation) {
            $invitation->code = $invitation->code ?: str()->random(8);
            $invitation->user_id = $invitation->user_id ?? auth()->id();
        });
    }
}
