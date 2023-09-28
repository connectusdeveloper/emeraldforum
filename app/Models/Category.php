<?php

namespace App\Models;

use App\Models\Traits\{Paginatable, Sluggable};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    use Paginatable;
    use Sluggable;

    public $with = ['parent:id,name,slug'];
    protected $fillable = ['name', 'title', 'slug', 'description', 'category_id', 'order_no', 'private', 'active'];

    public function children()
    {
        return $this->hasMany(self::class, 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'category_id');
    }

    public function scopeActive($query)
    {
        $query->where('active', 1);
    }

    public function scopeMain($query)
    {
        $query->whereNull('category_id');
    }

    public function scopePrivate($query)
    {
        $query->where('private', 1);
    }

    public function scopePublic($query)
    {
        $query->whereNull('private')->orWhere('private', 0);
    }

    public function threads()
    {
        return $this->belongsToMany(Thread::class);
    }

    public static function tree($columns = null, $all = false)
    {
        $allCategories = Category::query()->withCount(['threads as total' => fn ($q) => $q->approved()])
            ->orderBy('order_no', 'asc')->when(!$all, fn ($q) => $q->public()->active())->get($columns);
        $categories = $allCategories->whereNull('category_id');
        self::makeTree($categories, $allCategories);
        return $categories;
    }

    private static function makeTree($categories, $allCategories)
    {
        foreach ($categories as $category) {
            $category->children = $allCategories->where('category_id', $category->id)->values();
            if ($category->children->isNotEmpty()) {
                $category->setRelation('children', $category->children);
                self::makeTree($category->children, $allCategories);
            }
        }
    }
}
