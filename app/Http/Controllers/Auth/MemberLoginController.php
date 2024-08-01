<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth\LoginController as DefaultLoginController;

class MemberLoginController extends DefaultLoginController
{
    protected $redirectTo = '/member';

    public function __construct()
    {
        $this->middleware('guest:member')->except('logout');
    }

    protected function guard()
    {
        return Auth::guard('member');
    }
}
