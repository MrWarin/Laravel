<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getAllUser($start = 0, $end = 20)
    {
        $num_rows = User::count();

        $user = User::offset($start)
            ->limit($end)
            ->get();

        return ['user' => $user, 'rows' => $num_rows];
    }

    /**
     * Upload image.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function uploadProfileImage($request, $id)
    {
        $file = $request->file('profile');

        if (!empty($file)) {
            $where = ['user_id' => $id, 'type' => 'profile'];
            $user_image = User_image::where($where)->first();

            if (empty($user_image->id)) {
                $user_image = new User_image;
                $user_image->user_id = $id;
            }

            $path = public_path('\images\profiles\users\\' . $id);
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

    public static function verifyUserEmail($id, $password)
    {
        $user = User::select('password')
            ->where('id', '=', $id)
            ->where('email_verified_at', '=', null)
            ->first();

        if (!empty($user)) {
            if (base64_decode($password) === $user->password) {
                $update['email_verified_at'] = Carbon::now();
                User::where('id', '=', $id)->update($update);
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
