<?php

namespace App\Models\Traits;

use ArrayAccess;
use App\Models\Tag;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTags
{
    protected array $queuedTags = [];

    public function attachTag(string | Tag $tag)
    {
        return $this->attachTags([$tag]);
    }

    public function attachTags(array | ArrayAccess | Tag $tags): static
    {
        $tags = collect(static::findOrCreate($tags));
        $this->tags()->syncWithoutDetaching($tags->pluck('id')->toArray());
        return $this;
    }

    public static function bootHasTags()
    {
        static::created(function (Model $taggableModel) {
            if (0 === count($taggableModel->queuedTags)) {
                return;
            }
            $taggableModel->attachTags($taggableModel->queuedTags);
            $taggableModel->queuedTags = [];
        });

        static::deleted(function (Model $deletedModel) {
            $tags = $deletedModel->tags()->get();
            $deletedModel->detachTags($tags);
        });
    }

    public function detachTag(string | Tag $tag): static
    {
        return $this->detachTags([$tag]);
    }

    public function detachTags(array | ArrayAccess $tags): static
    {
        $tags = static::convertToTags($tags);
        collect($tags)->filter()->each(fn (Tag $tag) => $this->tags()->detach($tag));
        return $this;
    }

    public static function findOrCreate(string | array | ArrayAccess $values)
    {
        $tags = collect($values)->map(function ($value) {
            if ($value instanceof self) {
                return $value;
            }
            $tag = Tag::where(fn ($query) => $query->where('name', $value))->first();
            if (!$tag) {
                $tag = Tag::create(['name' => $value]);
            }
            return $tag;
        });
        return is_string($values) ? $tags->first() : $tags;
    }

    public function scopeWithAllTags(
        Builder $query,
        string | array | ArrayAccess | Tag $tags
    ): Builder {
        $tags = static::convertToTags($tags);
        collect($tags)->each(function ($tag) use ($query) {
            $query->whereHas('tags', function (Builder $query) use ($tag) {
                $query->where('tags.id', $tag->id ?? 0);
            });
        });
        return $query;
    }

    public function scopeWithAnyTags(
        Builder $query,
        string | array | ArrayAccess | Tag $tags
    ): Builder {
        $tags = static::convertToTags($tags);
        return $query->whereHas('tags', function (Builder $query) use ($tags) {
            $tagIds = collect($tags)->pluck('id');
            $query->whereIn('tags.id', $tagIds);
        });
    }

    public function scopeWithoutTags(
        Builder $query,
        string | array | ArrayAccess | Tag $tags
    ): Builder {
        $tags = static::convertToTags($tags);
        return $query->whereDoesntHave('tags', function (Builder $query) use ($tags) {
            $tagIds = collect($tags)->pluck('id');
            $query->whereIn('tags.id', $tagIds);
        });
    }

    public function setTagsAttribute(string | array | ArrayAccess | Tag $tags)
    {
        if (!$this->exists) {
            $this->queuedTags = $tags;
            return;
        }
        $this->syncTags($tags);
    }

    public function syncTags(string | array | ArrayAccess $tags): static
    {
        if (is_string($tags)) {
            $tags = Arr::wrap($tags);
        }

        $tags = collect(static::findOrCreate($tags));
        $this->tags()->sync($tags->pluck('id')->toArray());
        return $this;
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    protected static function convertToTags($values)
    {
        if ($values instanceof Tag) {
            $values = [$values];
        }

        return collect($values)->map(function ($value) {
            if ($value instanceof Tag) {
                return $value;
            }
            return static::where(fn ($query) => $query->where('name', $value))->first();
        });
    }
}
