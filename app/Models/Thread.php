<?php

namespace App\Models;

use App\Casts\HtmlEntities;
use App\Jobs\SendNotifications;
use App\Concerns\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Searchable\{SearchResult, Searchable};
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\{AllowDelete, GroupPermission, HasTags, Paginatable, Sluggable};

class Thread extends Model implements Searchable
{
    use AllowDelete;
    use GroupPermission;
    use HasFactory;
    use HasSchemalessAttributes;
    use HasTags;
    use Paginatable;
    use Sluggable;

    public static $settings;
    public $with = ['categories:id,name,slug', 'user:id,name,username,profile_photo_path', 'lastReply:created_at,thread_id,user_id', 'flag', 'flag.user:id,name,username,profile_photo_path', 'tags'];
    protected $casts = ['body' => HtmlEntities::class];
    protected $fillable = [
        'title', 'slug', 'description', 'body', 'category_id', 'private', 'active',  'views', 'up_votes', 'down_votes', 'flagged', 'sticky', 'sticky_category', 'extra_attributes',
        'approved', 'approved_by', 'group',
    ];
    protected $withCount = ['replies'];

    public function acceptedReply()
    {
        return $this->hasOne(Reply::class)->accepted();
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class);
    }

    public function flag()
    {
        return $this->morphOne(Flag::class, 'subject');
    }

    public function getSearchResult(): SearchResult
    {
        return new SearchResult($this, $this->title, route('threads.show', $this->slug));
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function scopeActive($query)
    {
        $query->where('active', 1);
    }

    public function scopeApproved($query)
    {
        $query->where('approved', 1);
    }

    public function scopeFlagged($query)
    {
        $query->withoutGlobalScope('flagged')->has('flag');
    }

    public function scopeNotApproved($query)
    {
        $query->whereNull('approved')->orWhere('approved', 0);
    }

    public function scopePrivate($query)
    {
        $query->where('private', 1);
    }

    public function scopePublic($query)
    {
        $query->whereNull('private')->orWhere('private', 0);
    }

    public function thread()
    {
        return $this->hasOne(Thread::class, 'id');
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

        static::creating(function (Thread $thread) {
            $thread->user_id = $thread->user_id ?? auth()->id();
        });

        static::created(function (Thread $thread) {
            SendNotifications::dispatchAfterResponse($thread->user, $thread);
            activity()->causedBy($thread->user)->performedOn($thread)->event('created')->log('Created new thread');
        });

        static::deleted(function (Thread $thread) {
            activity()->causedBy(auth()->user())->performedOn($thread)->event('deleted')->log('Deleted a thread');
        });
    }
}
