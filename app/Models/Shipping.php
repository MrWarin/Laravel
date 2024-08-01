<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    public static function getAllShipment($start = 0, $end = 20)
    {
        $num_rows = Shipping::count();

        $shipping = Shipping::offset($start)
        ->limit($end)
        ->get();

        return ['shipping' => $shipping, 'rows' => $num_rows];
    }
}
