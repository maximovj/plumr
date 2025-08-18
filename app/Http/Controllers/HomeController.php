<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if(Auth::check()) {
            return redirect()->route('main_account', [
                'user' => Auth::user()->username
            ]);
        }

        return view('plumr.inicio');
    }
}
