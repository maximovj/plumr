<?php

// Helpers para el sistema
// - Controladores, modelos, clases, triats, etc.

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

        return $username === auth()->user()->username
            ? auth()->user()
            : $user_check->followings()->where('username', $username)->first();
    }
}
