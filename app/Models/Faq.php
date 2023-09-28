<?php

namespace App\Models;

use App\Concerns\HasSchemalessAttributes;
use App\Models\Traits\{Paginatable, Sluggable};
use Spatie\Searchable\{SearchResult, Searchable};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faq extends Model implements Searchable
{
    use HasFactory;
    use HasSchemalessAttributes;
    use Paginatable;
    use Sluggable;

    public static $slugFromColumn = 'question';
    public $with = ['FaqCategories:id,name,slug'];
    protected $fillable = ['question', 'answer', 'slug', 'faq_category_id', 'active', 'order_no', 'extra_attributes'];

    public function FaqCategories()
    {
        return $this->belongsToMany(FaqCategory::class);
    }

    public function getSearchResult(): SearchResult
    {
        return new SearchResult($this, $this->question, route('faqs.show', $this->slug));
    }

    public function scopeActive($query)
    {
        $query->where('active', 1);
    }

    public function scopeSearch($query, $search)
    {
        $query->where('question', 'like', "%{$search}%")
            ->orWhere('answer', 'like', "%{$search}%")
            ->orWhereHas('categories', fn ($q) => $q->where('name', 'like', "%{$search}%"));
    }
}
