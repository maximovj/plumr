<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('plumr.inicio');
});

Route::get('/login', function () {
    return "Iniciar sesión.";
})->name('login');

Route::post('/login', function () {
    return "Iniciar sesión.";
})->name('login.attempt');

Route::post('/logout', function () {
    return "Cerrar cuenta.";
})->name('logout');

Route::get('/register', [RegisterController::class, 'create'])->name('auth.register');

Route::post('/register', function () {
    return "Registar cuenta (BackEnd).";
})->name('register.store');

Route::get('/recover-password', function () {
    return "Recuperar Cuenta (FrontEnd).";
})->name('recover-password.index');

Route::get('/recover-account', function () {
    return "Recuperar Cuenta (BackEnd)";
})->name('recover-account.index');

Route::get('/authorization-account', function () {
    return "Autorizar operación (FrontEnd).";
})->name('authorization-account.index');

Route::get('/authorization-account', function () {
    return "Autorizar operación (BackEnd).";
})->name('authorization-account.index');
