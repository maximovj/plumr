<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('plumr.auth.login');
    }

    public function login(Request $request)
    {
        dd($request->all());
    }
}
