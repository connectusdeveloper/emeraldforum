<?php

namespace App\Models;

use App\Models\Traits\Paginatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Badge extends Model
{
    use HasFactory;
    use Paginatable;

    protected $fillable = ['name', 'image', 'css_class'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
