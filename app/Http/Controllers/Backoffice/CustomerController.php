<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Customer_address;
use App\Models\Province;
use App\Models\District;
use App\Models\Subdistrict;
use Carbon\Carbon;

class CustomerController extends Controller
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
        $item = Customer::getAllCustomer($start, $per_page);
        $customer = $item['customer'];
        $num_rows = $item['rows'];

        $page_num = ceil($num_rows / $per_page);

        return view('backoffice/customer', [
            'customers' => $customer,
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
        $province = Province::all();
        $district = District::all();
        $subdistrict = Subdistrict::all();

        return view('backoffice/customerform', [
            'provinces' => $province,
            'districts' => $district,
            'subdistricts' => $subdistrict,
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
        Customer::insertCustomer($request);

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
        $customer = Customer::select(['Customers.*', 'Customer_images.name as image'])
        ->leftjoin('Customer_images', 'Customer_images.cust_id', '=', 'Customers.id')
        ->find($id);

        $address = Customer_address::where('cust_id', '=', $id)->get();
        $province = Province::all();
        $district = District::all();
        $subdistrict = Subdistrict::all();

        return view(
            'backoffice/customerform',
            [
                'edit' => $customer,
                'addresses' => $address,
                'provinces' => $province,
                'districts' => $district,
                'subdistricts' => $subdistrict,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Customer::updateCustomer($request, $id);

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
        $customer = Customer::find($id);

        $customer->delete();

        return back()->with('message', 'Delete Successful !');
    }

    /**
     * Verify customer email.
     *
     * @param  int  $id
     * @param  string $password
     * @return \Illuminate\Http\Response
     */
    public function verify($id, $password)
    {
        try {
            Customer::verifyCustomerEmail($id, $password);
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return view('member/verify', ['message' => $message]);
    }
}
