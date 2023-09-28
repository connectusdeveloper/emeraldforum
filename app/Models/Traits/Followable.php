<?php

namespace App\Models\Traits;

trait Followable
{
    public function approvedFollowers()
    {
        return $this->followers()->wherePivotNotNull('approved_at');
    }

    public function approvedFollowings()
    {
        return $this->followings()->wherePivotNotNull('approved_at');
    }

    public function follow($user)
    {
        // $this->followers()->detach($user->id);
        return $this->followings()->attach([$user->id => ['approved_at' => now()]]);
    }

    public function followers()
    {
        return $this->belongsToMany(self::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    public function followings()
    {
        return $this->belongsToMany(self::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    public function isFollowing($user)
    {
        return $this->followings->where('id', $user->id)->count();
        // return $this->followings()->whereRelation('followers', 'id', $user->id)->exists();
    }

    public function loadFollowCount()
    {
        return $this->loadCount(['followers', 'followings']);
    }

    public function unfollow($user)
    {
        return $this->followings()->detach($user->id);
    }
}
