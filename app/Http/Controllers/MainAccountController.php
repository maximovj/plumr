<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainAccountController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('plumr.account.main');
    }
}
