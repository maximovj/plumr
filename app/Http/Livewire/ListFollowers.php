<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class ListFollowers extends Component
{

    public User $user;
    public Collection $followers;
    public int $count;

    public function mount($user)
    {
        $this->user = $user;
        $this->followers = $user->followers()
        ->select('users.id', 'users.username')
        ->with('profile:id,user_id,photo,fullname')->take(10)->get();
        $this->count = $this->followers->count();
    }

    public function render()
    {
        return view('livewire.list-followers');
    }
}
