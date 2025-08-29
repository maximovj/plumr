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

Route::get('/{user:username}/edit/photo', [AccountController::class, 'edit_photo'])
    ->middleware(['auth', 'owner'])
    ->name('account.edit_photo');

Route::get('/{user:username}/edit/cover', [AccountController::class, 'edit_cover'])
    ->middleware(['auth', 'owner'])
    ->name('account.edit_cover');

Route::get('/{user:username}/followers', FollowersController::class)
    ->middleware(['auth'])
    ->name('account.followers');

Route::get('/{user:username}/followings', FollowingsController::class)
    ->middleware(['auth'])
    ->name('account.followings');

// Ruta para multimedias (medias)
Route::resource('{user:username}/medias', MediaController::class)
    ->scoped([
        'user' => 'username',
        'media' => 'slug',
    ])
    ->middleware(['auth'])
    ->except(['show', 'create', 'edit']);

Route::get('{user:username}/medias/create', [MediaController::class, 'create'])
    ->middleware(['auth', 'owner'])
    ->name('medias.create');

Route::get('{user:username}/medias/{media:slug}/edit', [MediaController::class, 'edit'])
    ->middleware(['auth', 'owner'])
    ->name('medias.edit');

Route::get('{user:username}/medias/{media:slug}', [MediaController::class, 'show'])
    ->name('medias.show');

// Rutas para publicaciones
Route::resource('/{user:username}/posts', PostController::class)
    ->scoped([
        'user' => 'username',
        'post' => 'url_access',
    ])
    ->middleware(['auth'])
    ->except(['show']);

// Ruta pública para mostrar un post
Route::get('/{user:username}/posts/{post:url_access}', [PostController::class, 'show'])
    ->name('posts.show');

// Ruta para artículos
Route::resource('{user:username}/articles', ArticleController::class)
    ->scoped([
        'user' => 'username',
        'article' => 'slug',
    ])
    ->middleware(['auth'])
    ->except(['show', 'create']);

Route::get('{user:username}/articles/create', [ArticleController::class, 'create'])
    ->middleware(['auth', 'owner'])
    ->name('articles.create');

Route::get('{user:username}/articles/{article:slug}', [ArticleController::class, 'show'])
    ->name('articles.show');

// Ruta para álbumes
Route::resource('{user:username}/albums', AlbumController::class)
    ->scoped([
        'user' => 'username',
        'album' => 'slug',
    ])
    ->middleware(['auth'])
    ->except(['show', 'create', 'edit']);

Route::get('{user:username}/albums/create', [AlbumController::class, 'create'])
    ->middleware(['auth', 'owner'])
    ->name('albums.create');

Route::get('{user:username}/albums/{album:slug}/edit', [AlbumController::class, 'edit'])
    ->middleware(['auth', 'owner'])
    ->name('albums.edit');

Route::get('{user:username}/albums/{album:slug}', [AlbumController::class, 'show'])
    ->name('albums.show');

// Rutas para editar perfil de usuario
Route::get('/{user:username}/profile', [ProfileController::class, 'edit'])
    ->middleware(['auth', 'owner'])
    ->name('profile.edit');

Route::post('/{user:username}/profile', [ProfileController::class, 'update'])
    ->middleware(['auth', 'owner'])
    ->name('profile.update');

