<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class FollowButton extends Component
{
    public User $user;
    public $isFollowing;

    public function mount($user)
    {
        $this->user = $user;
        $this->isFollowing = auth()->user()->followings->contains($user->id);
    }

    public function toggleFollow()
    {
        $authUser = auth()->user();

        if ($this->isFollowing) {
            $authUser->followings()->detach($this->user->id);
            $this->isFollowing = false;
        } else {
            $authUser->followings()->attach($this->user->id);
            $this->isFollowing = true;
        }
    }

    public function render()
    {
        return view('livewire.follow-button');
    }
}
