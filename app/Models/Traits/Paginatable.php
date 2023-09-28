<?php

namespace App\Models\Traits;

trait Paginatable
{
    public function getPerPage()
    {
        return site_config('per_page') ?? 10;
    }

    public function onEachSide($count)
    {
        $this->onEachSide = $count;

        return $this;
    }
}
