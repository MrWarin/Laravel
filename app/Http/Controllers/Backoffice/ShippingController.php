<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipping;

class ShippingController extends Controller
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
        $item = Shipping::getAllShipment($start, $per_page);
        $shipping = $item['shipping'];
        $num_rows = $item['rows'];

        $page_num = ceil($num_rows / $per_page);

        return view('backoffice/shipping', [
            'shippings' => $shipping,
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
        return view('backoffice/shippingform');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shipping = new Shipping;

        $data = $request->except('_token');
        foreach($data as $name => $value)
        {
            $shipping->$name = $value;
        }

        $shipping->save();

        return back()->with('message','Insert Succesful !');
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
        $shipping = Shipping::find($id);

        return view('backoffice/shippingform', ['edit' => $shipping]);
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
        $shipping = Shipping::find($id);

        $data = $request->except('_method', '_token');
        foreach($data as $name => $value)
        {
            if($shipping->$name !== $value)
            {
                $shipping->$name = $value;
            }
        }

        $shipping->save();

        return back()->with('message','Update Successful !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shipping = Shipping::find($id);

        $shipping->delete();

        return back()->with('message','Delete Successful !');
    }
}
