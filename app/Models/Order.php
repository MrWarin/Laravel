<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    public static function getAllOrder($start = 0, $end = 20)
    {
        $num_rows = Order::select('Orders.*', 'Customers.name as cust_name', 'Shippings.name as ship_name', DB::raw('SUM(order_details.amount * product_details.price) as total'))
            ->join('Customers', 'Customers.id', '=', 'Orders.cust_id')
            ->join('Shippings', 'Shippings.id', '=', 'Orders.ship_id')
            ->join('Order_details', 'Order_details.order_id', '=', 'Orders.id')
            ->join('Product_details', 'Product_details.id', '=', 'Order_details.product_detail_id')
            ->count();

        $order = Order::select('Orders.*', 'Customers.name as cust_name', 'Shippings.name as ship_name', DB::raw('SUM(order_details.amount * product_details.price) as total'))
            ->groupBy('Orders.id')
            ->join('Customers', 'Customers.id', '=', 'Orders.cust_id')
            ->join('Shippings', 'Shippings.id', '=', 'Orders.ship_id')
            ->join('Order_details', 'Order_details.order_id', '=', 'Orders.id')
            ->join('Product_details', 'Product_details.id', '=', 'Order_details.product_detail_id')
            ->get();

        return ['order' => $order, 'rows' => $num_rows];
    }
}
