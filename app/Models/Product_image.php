<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_image extends Model
{
    use HasFactory;

    public $timestamps = false;

    public static function getProperImageSize($width, $height)
    {

    }
}
