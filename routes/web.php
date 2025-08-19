<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\FollowersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\MainAccountController;
use App\Http\Controllers\PostController;

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

Route::get('/', HomeController::class)->name('home');

// Rutas para login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('auth.login');
Route::post('/logout', [LoginController::class, 'destroy'])->name('auth.logout');

// Login para registro
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.attempt');

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

// Rutas para la cuenta del usuario
Route::get('/{user:username}', [MainAccountController::class, 'index'])
    ->middleware(['auth'])
    ->name('main_account');

// Rutas para editar cuenta de usuario
Route::get('/{user:username}/account', [AccountController::class, 'edit'])
    ->middleware(['auth', 'owner'])
    ->name('account.edit');

Route::post('/{user:username}/account', [AccountController::class, 'update'])
    ->middleware(['auth', 'owner'])
    ->name('account.update');

Route::get('/{user:username}/followers', FollowersController::class)
    ->middleware(['auth'])
    ->name('account.followers');

// Rutas para publicaciones
Route::resource('/{user:username}/post', PostController::class)
    ->scoped([
        'user' => 'username',
        'post' => 'url_access',
    ])
    ->middleware(['auth', 'owner'])
    ->except(['show']);

// Ruta pública para mostrar un post
Route::get('/{user:username}/post/{post:url_access}', [PostController::class, 'show'])
    ->name('post.show');

// Rutas para editar perfil de usuario
Route::get('/{user:username}/profile', [ProfileController::class, 'edit'])
    ->middleware(['auth', 'owner'])
    ->name('profile.edit');

Route::post('/{user:username}/profile', [ProfileController::class, 'update'])
    ->middleware(['auth', 'owner'])
    ->name('profile.update');

