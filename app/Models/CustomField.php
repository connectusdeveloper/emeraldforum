<?php

namespace App\Models;

use App\Models\Traits\Paginatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomField extends Model
{
    use HasFactory;
    use Paginatable;

    public $casts = ['models' => 'array'];
    public $fillable = [
        'name', 'type', 'models', 'order_no', 'options', 'required', 'show', 'description',
    ];

    public function optionsArray()
    {
        return array_map('trim', explode(',', $this->options));
    }

    public function scopeActive($query)
    {
        $query->where('active', 1);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['trashed'] ?? null, fn ($q, $t) => $q->{$t . 'Trashed'}())
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->search($search));
    }

    public function scopeOfModel($query, $model)
    {
        $query->active()->whereJsonContains('models', $model)->orderBy('order_no', 'asc');
        // $query->where('models', 'like', "%{$model}%")->orderBy('order_no', 'asc');
    }

    public function scopeOfModels($query, $models)
    {
        $query->active();
        if (!empty($models)) {
            $r = 0;
            foreach ($models as $model) {
                $query->{$r ? 'orWhereJsonContains' : 'whereJsonContains'}('models', $model);
                $r++;
            }
            $query->orderBy('order', 'asc');
        }
    }

    public function scopeSearch($query, $s)
    {
        $query->where(
            fn ($q) => $q->where('name', 'like', "%{$s}%")->orWhere('type', 'like', "%{$s}%")
                ->orWhere('models', 'like', "%{$s}%")->orWhere('description', 'like', "%{$s}%")
        );
    }
}
