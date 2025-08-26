<?php

// Helpers para el sistema
// - Controladores, modelos, clases, triats, etc.

if(!function_exists('isfollower')) {
    function isfollower(\App\Models\User $user_check)
    {
        return auth()->user()->followings->contains($user_check->id);
    }
}
