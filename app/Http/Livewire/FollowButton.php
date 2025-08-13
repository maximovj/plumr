<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class FollowButton extends Component
{
    public User $user;

    public function mount($user)
    {
        $this->user = $user;
    }

    public function toggleFollow()
    {
        $authUser = auth()->user();

        if ($authUser->followings->contains($this->user->id)) {
            $authUser->followings()->detach($this->user->id);
        } else {
            $authUser->followings()->attach($this->user->id);
        }
    }

    public function render()
    {
        return view('livewire.follow-button');
    }
}
