<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_detail;
use App\Models\Product_brand;
use App\Models\Product_categories;
use App\Models\Product_image;
use App\Models\Product_attribute;
use App\Models\Product_attribute_group;
use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
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
        $item = Product::getAllProduct($start, $per_page);
        $product = $item['product'];
        $num_rows = $item['rows'];

        $page_num = ceil($num_rows / $per_page);

        return view('backoffice/product', [
            'products' => $product, 
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
        $brand = Product_brand::all();
        $category = Product_categories::all();
        $attribute_group = Product_attribute_group::all();

        return view(
            'backoffice/productform',
            [
                'brands' => $brand,
                'categories' => $category,
                'attribute_group' => $attribute_group
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $product = new Product;
            $product->setRequest($request);
            $product->saveProduct();
            $product->saveProductDetail();
            $product->saveProductImage();
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect('/product//')->with('message', 'Insert Successful !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        return view('backoffice/productdetail', ['products' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        $brand = Product_brand::all();
        $category = Product_categories::all();
        $attribute_group = Product_attribute_group::all();

        $product_detail = Product_detail::select('Product_details.*', 'Product_attribute_collections.attribute_id', 'Product_attributes.value as attribute_name')
            ->groupBy('Product_details.id')
            ->join('Product_attribute_collections', 'Product_attribute_collections.detail_id', '=', 'Product_details.id')
            ->join('Product_attributes', 'Product_attributes.id', '=', 'Product_attribute_collections.attribute_id')
            ->where('Product_details.product_id', '=', $product->id)
            ->get();

        // echo '<pre>'; print_r($product); echo '</pre>';

        $product->detail = $product_detail;

        foreach ($product->detail as $detail) {
            $product_image = Product_image::where('product_detail_id', '=', $detail->id)->get();
            $detail->image = $product_image;
        }

        return view(
            'backoffice/productform',
            [
                'edit' => $product,
                'brands' => $brand,
                'categories' => $category,
                'attribute_group' => $attribute_group
            ]
        );
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
        try {
            $product = new Product;
            $product->setRequest($request);
            $product->saveProduct($id);
            $product->saveProductDetail();
            // $product->saveProductImage();
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
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
        $product = Product::find($id);

        $product->delete();

        return back()->with('message', 'Delete Successful !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showAttribute(Request $request)
    {
        try {
            $data = $request->only('search')['search'];

            if (!empty($data)) {
                $json = array();
                $product_attr = Product_attribute::where('value', 'like', '%' . $data . '%')->get();

                foreach ($product_attr as $key => $value) {
                    $json['results'][$key]['id'] = $value->id;
                    $json['results'][$key]['text'] = $value->value;
                    $json['results'][$key]['html'] = '<div>' . $value->value . '</div>';
                }

                return json_encode($json);
            }
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAttribute(Request $request)
    {
        try {
            $data = $request->only('data')['data'];
            $product_attr = Product_attribute::where('value', '=', $data)->first();

            if (empty($product_attr)) {
                $product_attr = new Product_attribute;
                $product_attr->value = $data;
                $product_attr->save();
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
