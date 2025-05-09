<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name', 'capital'];

    public static function getQuizSet($count = 10)
    {
        return self::inRandomOrder()->limit($count)->get();
    }
}