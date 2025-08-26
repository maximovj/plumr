<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class TagsAdvanced extends Component
{
    public User $user;
    public $tags;

    public function mount(User $user, $tags = [])
    {
        $this->user = $user;
        $this->tags = collect($tags)->filter(fn($item) => !empty($item));
    }

    public function render()
    {
        return view('livewire.tags-advanced');
    }
}
