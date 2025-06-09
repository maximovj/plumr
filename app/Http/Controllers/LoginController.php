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
        // Validación de formulario
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        dd($request->all());
    }
}
