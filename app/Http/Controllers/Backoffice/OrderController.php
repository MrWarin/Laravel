<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Product_detail;
use App\Models\Shipping;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($page = 1)
    {
        $per_page = 20;
        $start = ($page * $per_page) - $per_page;
        $item = Order::getAllOrder($start, $per_page);
        $order = $item['order'];
        $num_rows = $item['rows'];

        $page_num = ceil($num_rows / $per_page);

        return view('backoffice/order', [
            'orders' => $order,
            'page' => $page,
            'page_num' => $page_num
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer = Customer::all();

        $products = Product::select('Products.*', 'Product_brands.name_en as brand')
            ->where('Product_details.amount', '>', 0)
            ->where('Product_details.status', '=', 'enable')
            ->groupBy('Products.id')
            ->rightjoin('Product_details', 'Product_details.product_id', '=', 'Products.id')
            ->join('Product_brands', 'Product_brands.id', '=', 'Products.brand_id')
            ->get();

        $shipping = Shipping::all();

        foreach ($products as $name => $product) {
            $product_detail = Product_detail::where('product_id', '=', $product->id)->get();

            foreach ($product_detail as $detail) {
                $product->detail = $product_detail;
            }
        }

        return view('backoffice/orderform', [
            'customers' => $customer,
            'products' => $products,
            'shippings' => $shipping,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order_data = $request->except('_token', 'detail');
        $order = new Order;

        foreach ($order_data as $name => $value) {
            $order->$name = $value;
        }
        $order->save();

        $order_detail_data = $request->only('', 'detail')['detail'];
        foreach ($order_detail_data as $name => $value) {
            $product_detail = Product_detail::find($value['product_detail_id']);

            $order_detail_data[$name]['product_id'] = $product_detail->product_id;
            $order_detail_data[$name]['order_id'] = $order->id;
        }
        Order_detail::insert($order_detail_data);

        return back()->with('message', 'Insert Successful !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        $order_detail = Order_detail::all()->where('order_id', '=', $id);
        $customer = Customer::all();
        $shipping = Shipping::all();

        $products = Product::select('Products.*', 'Product_brands.name_en as brand')
            ->where('Product_details.amount', '>', 0)
            ->where('Product_details.status', '=', 'enable')
            ->groupBy('Products.id')
            ->rightjoin('Product_details', 'Product_details.product_id', '=', 'Products.id')
            ->join('Product_brands', 'Product_brands.id', '=', 'Products.brand_id')
            ->get();

        foreach ($products as $product) {
            $product_detail = Product_detail::where('product_id', '=', $product->id)->get();
            $product->detail = $product_detail;
        }

        return view('backoffice/orderform', [
            'edit' => $order,
            'order_details' => $order_detail,
            'customers' => $customer,
            'products' => $products,
            'shippings' => $shipping,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order_data = $request->except('_method', '_token', 'detail');
        $order = Order::find($id);
        foreach ($order_data as $name => $value) {
            if ($order->$name !== $value) {
                $order->$name = $value;
            }
        }
        $order->save();

        $order_detail_data = $request->only('', 'detail')['detail'];
        foreach ($order_detail_data as $name => $value) {
            Order_detail::where('id', '=', $value['id'])->update($value);
        }

        return back()->with('message', 'Update Successful !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();

        Order_detail::where('order_id', '=', $id)->delete();

        return back()->with('message', 'Delete Successful !');
    }
}
