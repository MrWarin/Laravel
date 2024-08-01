<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public static function getAllCustomer($start = 0, $end = 20)
    {
        $num_rows = Customer::count();

        $customer = Customer::offset($start)
        ->limit($end)
        ->get();

        return ['customer' => $customer, 'rows' => $num_rows];
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public static function insertCustomer($request)
    {
        $customer = new Customer;
        $data = $request->except('_token', 'address', 'profile', 'confirm_password');
        $confirm_password = $request->only('confirm_password')['confirm_password'];
        $request->flash();

        foreach ($data as $name => $value) {
            $customer->$name = $value;
        }

        if ($customer->password === $confirm_password) {

            $customer->password = Hash::make($customer->password);
            $customer->save();
            $customer->uploadProfileImage($request, $customer->id);
            $customer->storeAddress($request, $customer->id);
        } else {
            throw new \Exception('Password is not match !');
        }
    }

    public static function updateCustomer($request, $id)
    {
        $customer = Customer::find($id);
        $customer_data = $request->except('_method', '_token', 'profile', 'address');
        foreach ($customer_data as $name => $value) {
            if ($customer->$name !== $value) {

                $customer->$name = $value;
            }
        }

        $customer->save();
        $customer->uploadProfileImage($request, $customer->id);
        $customer->storeAddress($request, $customer->id);
    }

    public static function uploadProfileImage($request, $id)
    {
        $file = $request->file('profile');

        if (!empty($file)) {
            $where = ['cust_id' => $id, 'type' => 'profile'];
            $user_image = Customer_image::where($where)->first();

            if (empty($user_image->id)) {
                $user_image = new Customer_image;
                $user_image->cust_id = $id;
            }

            $path = public_path('images\profiles\customers\\' . $id);
            $user_image->name = 'profile.' . $file->getClientOriginalExtension();
            $image_resize = Image::make($file->getRealPath());
            $image_resize->resize(120, 120);

            if (!file_exists($path)) {
                mkdir($path, 755, 1);
            }

            $image_resize->save($path . '\\' . $user_image->name);

            $user_image->save();
        }
    }

    public static function storeAddress($request, $id)
    {
        $address = $request->only('address')['address'];
        $customer_address = Customer_address::where('cust_id', '=', $id)->get('id');
        $address_id = [];

        if (! array_search(null, $address[0])) {
            foreach ($address as $value) {
                if (!empty($value['id'])) {
                    $address_id[] = $value['id'];
                    Customer_address::where('id', '=', $value['id'])->update($value);
                } else {
                    $value['cust_id'] = $id;
                    Customer_address::insert($value);
                }
            }

            foreach ($customer_address as $value) {
                if (!in_array($value->id, $address_id)) {
                    $value->delete();
                }
            }
        }
    }

    public static function verifyCustomerEmail($id, $password)
    {
        $customer = Customer::select('password')
            ->where('id', '=', $id)
            ->where('email_verified_at', '=', null)
            ->first();

        if (!empty($customer)) {
            if (base64_decode($password) === $customer->password) {
                $update['email_verified_at'] = Carbon::now();
                Customer::where('id', '=', $id)->update($update);
                return true;
            } else {
                throw new \Exception('Password is not match !');
            }
        } else {
            throw new \Exception('User is invalid !');
        }

        return false;
    }
}
