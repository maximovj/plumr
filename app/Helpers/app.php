<?php

// Helpers para el sistema
// - Controladores, modelos, clases, triats, etc.

use Illuminate\Support\Facades\Storage;

if(!function_exists('isowner')) {
    function isowner(\App\Models\User $user_check)
    {
        return auth()->user()->id == $user_check->id;
    }
}

if(!function_exists('isfollower')) {
    function isfollower(\App\Models\User $user_check)
    {
        return auth()->user()->followings->contains($user_check->id);
    }
}

if(!function_exists('account_tag')) {
    function account_tag(string $tag)
    {
        return auth()->user()->followings()->where('username', ltrim($tag, '@'))->first();
    }
}

if (!function_exists('is_account_tag')) {
    function is_account_tag(\App\Models\User $user_check, string $tag)
    {
        $username = ltrim($tag, '@');

        return $username === $user_check->username
            ? $user_check
            : $user_check->followings()->where('username', $username)->first();
    }
}

if (!function_exists('file_path_url')) {
    function file_path_url($filePath)
    {
        $default_file = 'img/media/media_default.png';

        // 1️⃣ Revisa en public/
        $publicFullPath = public_path($filePath);
        if ($filePath && file_exists($publicFullPath)) {
            return asset($filePath);
        }

        // 2️⃣ Revisa en storage/public
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return asset('storage/' . $filePath);
        }

        // 3️⃣ Si no existe en ninguno, usa default
        return asset($default_file);
    }
}
