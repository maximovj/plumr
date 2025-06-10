<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('plumr.auth.login');
    }

    public function store(Request $request)
    {
        // Validación de formulario
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if(!auth()->attempt($request->only('email', 'password'), $request->remember))
        return redirect()->back()->with('error_auth', 'Correo electrónico o contraseña incorrectas.');

        return redirect()->route('main_account');

    }

    public function destroy()
    {
        auth()->logout();
        return redirect()->route('auth.login');
    }


}
