<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\FollowersController;
use App\Http\Controllers\FollowingsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\MainAccountController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página principal
Route::get('/', HomeController::class)->name('home');

// Rutas para login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('auth.login');
Route::post('/logout', [LoginController::class, 'destroy'])->name('auth.logout');

// Rutas para registro
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.attempt');

// Recuperación de cuenta
Route::get('/recover-password', fn () => "Recuperar Cuenta (FrontEnd).")
    ->name('recover-password.index');

Route::get('/recover-account', fn () => "Recuperar Cuenta (BackEnd)")
    ->name('recover-account.index');

// Autorizaciones
Route::get('/authorization-account', fn () => "Autorizar operación (FrontEnd).")
    ->name('authorization-account.index');

Route::get('/authorization-account', fn () => "Autorizar operación (BackEnd).")
    ->name('authorization-account.index');

// ----------------------------
// Rutas relacionadas a usuarios
// ----------------------------

// Cuenta de usuario (editar)
Route::get('/{user:username}/account', [AccountController::class, 'edit'])
    ->middleware(['auth', 'owner'])
    ->name('account.edit');

Route::post('/{user:username}/account', [AccountController::class, 'update'])
    ->middleware(['auth', 'owner'])
    ->name('account.update');

Route::get('/{user:username}/edit/photo', [AccountController::class, 'edit_photo'])
    ->middleware(['auth', 'owner'])
    ->name('account.edit_photo');

Route::get('/{user:username}/edit/cover', [AccountController::class, 'edit_cover'])
    ->middleware(['auth', 'owner'])
    ->name('account.edit_cover');

// Seguidores y seguidos
Route::get('/{user:username}/followers', FollowersController::class)
    ->middleware(['auth'])
    ->name('account.followers');

Route::get('/{user:username}/followings', FollowingsController::class)
    ->middleware(['auth'])
    ->name('account.followings');

// Medias
Route::resource('{user:username}/medias', MediaController::class)
    ->scoped([
        'user' => 'username',
        'media' => 'slug',
    ]);

// Posts
Route::resource('{user:username}/posts', PostController::class)
    ->scoped([
        'user' => 'username',
        'post' => 'url_access',
    ]);

// Articles
Route::resource('{user:username}/articles', ArticleController::class)
    ->scoped([
        'user' => 'username',
        'article' => 'slug',
    ]);

// Albums
Route::resource('{user:username}/albums', AlbumController::class)
    ->scoped([
        'user' => 'username',
        'album' => 'slug',
    ]);

// Perfil
Route::get('/{user:username}/profile', [ProfileController::class, 'edit'])
    ->middleware(['auth', 'owner'])
    ->name('profile.edit');

Route::post('/{user:username}/profile', [ProfileController::class, 'update'])
    ->middleware(['auth', 'owner'])
    ->name('profile.update');

// ----------------------------
// Ruta genérica al final
// ----------------------------
Route::get('/{user:username}', [MainAccountController::class, 'index'])
    ->middleware(['auth'])
    ->name('main_account');
