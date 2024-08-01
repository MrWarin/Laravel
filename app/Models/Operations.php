<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operations extends Model
{
    use HasFactory;

    public static function getOperation()
    {
        $ops = Operations::select('*')->get();

        return $ops;
    }

    public static function saveOperation($id = '')
    {
        $ops_data = self::$request->except('_token', '_method');

        if (!empty($id)) {
            $ops = Operations::find($id);
        }

        if (empty($ops)) {
            $ops = new Operations;
        }

        foreach ($ops_data as $name => $value) {
            if ($ops->$name !== $value) {
                $ops->$name = $value;
            }
        }

        $ops->save();

        self::$ops_id = $ops->id;
    }

}
