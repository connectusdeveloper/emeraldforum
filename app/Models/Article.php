<?php

namespace App\Models;

use App\Concerns\HasSchemalessAttributes;
use Spatie\Searchable\{SearchResult, Searchable};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\{GroupPermission, Paginatable, Sluggable};

class Article extends Model implements Searchable
{
    use GroupPermission;
    use HasFactory;
    use HasSchemalessAttributes;
    use Paginatable;
    use Sluggable;

    protected $fillable = ['title', 'slug', 'description', 'body', 'order_no', 'active', 'user_id', 'extra_attributes', 'image', 'group'];

    public function getSearchResult(): SearchResult
    {
        return new SearchResult($this, $this->title, route('articles.show', $this->slug));
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    protected static function booted(): void
    {
        static::creating(function (Article $article) {
            $article->user_id = $article->user_id ?? auth()->id();
        });
    }
}
