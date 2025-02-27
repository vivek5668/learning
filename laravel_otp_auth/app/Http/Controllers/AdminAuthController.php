<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        // dd($request->all());
        $credentials = $request->only('email', 'password');
        // dd($credentials);
        //
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->intended('/admin/students');
        }

        return back()->withErrors(['email' => 'Invalid credentialssssssssssssss']);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect('/admin/login');
    }
}
