<?php

namespace App\Models;

use App\Helpers\Notifiable;
use App\Jobs\SendNotifications;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;
use App\Concerns\HasSchemalessAttributes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Traits\{Followable, Paginatable};
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Followable;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use HasSchemalessAttributes;
    use Notifiable;
    use Paginatable;
    use TwoFactorAuthenticatable;

    protected $appends = ['profile_photo_url', 'meta_data'];
    protected $casts = ['email_verified_at' => 'datetime'];
    protected $fillable = ['name', 'email', 'username', 'password', 'active', 'banned'];
    protected $hidden = ['password', 'remember_token', 'two_factor_recovery_codes', 'two_factor_secret'];
    protected $with = ['badges', 'userMeta'];

    public function badges()
    {
        return $this->belongsToMany(Badge::class);
    }

    public function conversations()
    {
        return $this->conversationsBy->merge($this->conversationsTo);
    }

    public function conversationsBy()
    {
        return $this->hasMany(Conversation::class);
    }

    public function conversationsTo()
    {
        return $this->hasMany(Conversation::class, 'receiver_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(Thread::class);
    }

    public function getDisplayNameAttribute()
    {
        return $this->meta_data['display_name'] ?? $this->name;
    }

    public function getMetaDataAttribute()
    {
        return $this->relationLoaded('userMeta') ? $this->userMeta->pluck('meta_value', 'meta_key')->all() : [];
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function scopeActive($query)
    {
        $query->where('active', 1);
    }

    public function scopeBanned($query)
    {
        $query->where('banned', 1);
    }

    public function scopeInactive($query)
    {
        $query->whereNull('active')->orWhere('active', 0);
    }

    public function scopeNotBanned($query)
    {
        $query->whereNull('banned')->orWhere('banned', 0);
    }

    public static function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            $query->where(
                fn ($q) => $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
            );
        }
        return $query;
    }

    public function sendMessage(array $data)
    {
        $user = auth()->user();
        $conversation = Conversation::where('user_id', $user->id)->where('receiver_id', $data['user_id'])->first();

        if (!$conversation) {
            $conversation = Conversation::where('user_id', $data['user_id'])->where('receiver_id', $user->id)->first();
        }

        if ($conversation) {
            $conversation->update(['updated_at' => now()]);
        } else {
            $conversation = Conversation::create(['user_id' => $user->id, 'receiver_id' => $data['user_id']]);
        }

        $conversation->messages()->create(['user_id' => $user->id, 'body' => $data['message']]);
        SendNotifications::dispatchAfterResponse($user, $conversation, 'conversation-started');
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function userMeta()
    {
        return $this->hasMany(UserMeta::class, 'user_id', 'id');
    }

    public function votes()
    {
        return $this->hasMany(UserVote::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope('withCounts', function (Builder $builder) {
            $builder->withCount(['threads', 'replies'])->addSelect([
                DB::raw('(select count(following_id) from `follows` where `users`.`id` = `follows`.`following_id`) as followers_count'),
                DB::raw('(select count(follower_id) from `follows` where `users`.`id` = `follows`.`follower_id`) as followings_count'),
            ]);
        });
    }

    protected function defaultProfilePhotoUrl()
    {
        $name = trim(collect(explode(' ', $this->display_name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=7F9CF5&background=EBF4FF';
    }
}
