<?php

// Helpers para el sistema
// - Controladores, modelos, clases, triats, etc.

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
