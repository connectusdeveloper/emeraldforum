<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];
    protected $keyType = 'string';
    protected $primaryKey = 'tec_key';
}
