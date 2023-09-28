<?php

namespace App\Models;

use App\Helpers\Notifiable;
use App\Jobs\SendNotifications;
use App\Concerns\HasSchemalessAttributes;
use App\Models\Traits\{AllowDelete, Paginatable};
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reply extends Model
{
    use AllowDelete;
    use HasFactory;
    use HasSchemalessAttributes;
    use Notifiable;
    use Paginatable;

    public static $settings;
    public $with = ['user:id,name,username,profile_photo_path'];
    protected $fillable = [
        'body', 'up_votes', 'down_votes', 'flagged', 'sticky', 'sticky_category', 'extra_attributes', 'guest_name', 'guest_email',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function flag()
    {
        return $this->morphOne(Flag::class, 'subject');
    }

    public function routeNotificationForMail(): array|string
    {
        return $this->guest_email;
    }

    public function scopeAccepted($query)
    {
        $query->where('accepted', 1);
    }

    public function scopeActive($query)
    {
        $query->where('active', 1);
    }

    public function scopePrivate($query)
    {
        $query->where('private', 1);
    }

    public function scopePublic($query)
    {
        $query->whereNull('private')->orWhere('private', 0);
    }

    public function scopeVerified($query)
    {
        $query->whereNotNull('user_id')->orWhereNotNull('guest_verified');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes(): MorphMany
    {
        return $this->morphMany(UserVote::class, 'voteable');
    }

    protected static function booted(): void
    {
        static::$settings = site_config();
        static::addGlobalScope('flagged', function (Builder $builder) {
            if (static::$settings['hide_flagged'] ?? null) {
                return $builder->doesntHave('flag');
            }
        });

        static::addGlobalScope('verified', fn (Builder $builder) => $builder->verified());

        static::creating(function (Reply $reply) {
            $reply->user_id = $reply->user_id ?? auth()->id();
        });

        static::created(function (Reply $reply) {
            SendNotifications::dispatchAfterResponse($reply->user, $reply->thread, 'replied');
            activity()->causedBy($reply->user)->performedOn($reply)->event('replied')->log('Replied to thread');
        });

        static::deleted(function (Reply $reply) {
            activity()->causedBy(auth()->user())->performedOn($reply->thread)->event('deleted')->log('Deleted a reply');
        });
    }
}
