<?php

namespace App\Casts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class HtmlEntities implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return html_entity_decode($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return htmlentities($value);
    }
}
