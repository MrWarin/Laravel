<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class Product extends Model
{
    use HasFactory;

    public static $request;
    public static $product_id;
    public static $product_detail_id = array();
    public static $product_attr_id = array();

    public static function getAllProduct($start = 0, $end = 20)
    {
        $num_rows = Product::select('Products.*', 'Product_brands.name_en as brand', 'Product_categories.name_en as category', DB::raw('MIN(Product_details.price) as price'), DB::raw('SUM(Product_details.amount) as stock'))
            ->leftJoin('Product_details', 'Product_details.product_id', '=', 'Products.id')
            ->leftJoin('Product_brands', 'Product_brands.id', '=', 'Products.brand_id')
            ->leftJoin('Product_categories', 'Product_categories.id', '=', 'Products.cat_id')
            ->count();

        $product = Product::select('Products.*', 'Product_brands.name_en as brand', 'Product_categories.name_en as category', DB::raw('MIN(Product_details.price) as price'), DB::raw('SUM(Product_details.amount) as stock'))
            ->groupBy('Products.id')
            ->leftJoin('Product_details', 'Product_details.product_id', '=', 'Products.id')
            ->leftJoin('Product_brands', 'Product_brands.id', '=', 'Products.brand_id')
            ->leftJoin('Product_categories', 'Product_categories.id', '=', 'Products.cat_id')
            ->offset($start)
            ->limit($end)
            ->get();

        return ['product' => $product, 'rows' => $num_rows];
    }


    public static function setRequest($request)
    {
        self::$request = $request;
    }

    public static function saveProduct($id = '')
    {
        $product_data = self::$request->except('_token', '_method', 'detail', 'attribute', 'image');

        if (!empty($id)) {
            $product = Product::find($id);
        }

        if (empty($product)) {
            $product = new Product;
        }

        foreach ($product_data as $name => $value) {
            if ($product->$name !== $value) {
                $product->$name = $value;
            }
        }

        $product->save();

        self::$product_id = $product->id;
    }

    public static function saveProductDetail()
    {
        $product_detail_data = self::$request->only('detail')['detail'];

        if (!empty($product_detail_data)) {

            foreach ($product_detail_data as $name => $array) {

                if (!empty($array['id'])) {
                    $product_detail = Product_detail::find($array['id']);
                    $product_attribute = Product_attribute_collection::select('*')->where('detail_id', '=', $array['id'])->first();
                }

                if (empty($product_detail)) {
                    $product_detail = new Product_detail;
                }

                if (empty($product_attribute)) {
                    $product_attribute = new Product_attribute_collection;
                }

                $product_detail->product_id = self::$product_id;
                $product_detail->barcode = $array['barcode'];
                $product_detail->price = $array['price'];
                $product_detail->amount = $array['amount'];
                $product_detail->save();

                // echo '<pre>'; print_r($product_detail->id); echo '</pre>';

                $product_attribute->product_id = self::$product_id;
                $product_attribute->detail_id = $product_detail->id;
                $product_attribute->attribute_id = $array['attr_id'];
                $product_attribute->save();

                array_push(self::$product_detail_id, $product_detail->id);
                array_push(self::$product_attr_id, $product_attribute->id);
                unset($product_detail);
                unset($product_attribute);
            }
        }
    }

    public static function saveProductImage()
    {
        $file = self::$request->file('image');

        if (!empty($file)) {

            foreach ($file as $key => $array) {

                $product_image = new Product_image;

                foreach ($array as $value) {
                    $path = public_path('images\products\\' . self::$product_id);
                    $name = $value['name']->getClientOriginalName();
                    $image_resize = Image::make($value['name']->getRealPath());
                    // $width = $image_resize->width();
                    // $height = $image_resize->height();
                    $image_resize->resize(120, 120);

                    if (!file_exists($path)) {
                        mkdir($path, 755, 1);
                    }

                    $image_resize->save($path . '\\' . $name);

                    $product_image->product_id = self::$product_id;
                    $product_image->product_detail_id = self::$product_detail_id[$key];
                    $product_image->name = $name;
                    $product_image->save();
                }
            }
        }
    }
}
