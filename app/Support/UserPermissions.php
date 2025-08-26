<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserPermissions
{

    public static function isOwner(User $user): bool
    {
        return Auth::check() && Auth::id() === $user->id;
    }

    public static function isNotOwner(User $user): bool
    {
        return Auth::check() && Auth::id() !== $user->id;
    }

    public static function isFollower(User $user_check): bool
    {
        return auth()->user()->followings->contains($user_check->id);
    }

}
