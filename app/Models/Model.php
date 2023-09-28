<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    public static $slugFromColumn = 'title';
    public static $slugLocale;

    protected static function booting()
    {
        static::$slugLocale = app()->currentLocale();
        // static::$slugLocale = 'ar'; // if you uncomment this line then please comment/remove the above line
    }
}
