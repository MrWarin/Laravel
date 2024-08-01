<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
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
        $item = User::getAllUser($start, $per_page);
        $user = $item['user'];
        $num_rows = $item['rows'];

        $page_num = ceil($num_rows / $per_page);

        return view('backoffice/user', [
            'users' => $user,
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
        return view('backoffice/userform');
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
            $user = new User;
            $data = $request->except('_token', 'confirm_password');
            $confirm_password = $request->only('confirm_password')['confirm_password'];

            if ($data['password'] === $confirm_password) {

                $data['password'] = Hash::make($data['password']);

                foreach ($data as $name => $value) {
                    $user->$name = $value;
                }

                $user->save();
                $user->uploadProfileImage($request, $user->id);
            } else {
                throw new \Exception('Password is not match !');
            }
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        return back()->with('message', 'Insert Succesful !');
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
        $user = User::select(['Users.*', 'User_images.name as image'])
            ->leftjoin('User_images', 'User_images.user_id', '=', 'Users.id')
            ->find($id);

        return view('backoffice/userform', ['edit' => $user]);
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
            $user = User::find($id);
            $data = $request->except('profile', '_method', '_token');

            foreach ($data as $name => $value) {
                if ($user->$name !== $value) {
                    $user->$name = $value;
                }
            }

            $user->save();
            $user->uploadProfileImage($request, $user->id);
            
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
        $user = User::find($id);

        $user->delete();

        return back()->with('message', 'Delete Successful !');
    }

    /**
     * Reset user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request, $id)
    {
        try {
            $request = $request->except('_token');
            $user = User::find($id);

            if (
                Hash::check($request['old_password'], $user->password) &&
                $request['old_password'] !== $request['new_password'] &&
                $request['new_password'] === $request['confirm_password']
            ) {
                $user->password = Hash::make($request['new_password']);
            } else {
                throw new \Exception('Password is not match !');
            }

            $user->save();
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        return back()->with('message', 'Password was changed Successfully !');
    }

    /**
     * Verify user email.
     *
     * @param  int  $id
     * @param  string $password
     * @return \Illuminate\Http\Response
     */
    public function verify($id, $password)
    {
        try {
            User::verifyUserEmail($id, $password);
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return view('backoffice/verify', ['message' => $message]);
    }
}
