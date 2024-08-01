<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class product_categories extends Model
{
    use HasFactory;

    public $timestamps = false;

    public static function getAllProductCategory($start = 0, $end = 20)
    {
        $num_rows = product_categories::count();

        $category = product_categories::select('Product_categories.*', DB::raw('COUNT(cat_id) as amount'))
            ->groupBy('Product_categories.id')
            ->leftJoin('Products', 'Products.cat_id', '=', 'Product_categories.id')
            ->offset($start)
            ->limit($end)
            ->get();

        return ['category' => $category, 'rows' => $num_rows];
    }
}
