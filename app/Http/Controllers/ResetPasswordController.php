<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function resetpassword(Request $request)
    {
        return view('reset_password');
    }
}
