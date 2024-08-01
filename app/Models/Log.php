<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    public static function getAllLog($start = 0, $end = 20)
    {
        $num_rows = Log::count();

        $log = Log::offset($start)
        ->limit($end)
        ->get();

        return ['log' => $log, 'rows' => $num_rows];
    }
}
