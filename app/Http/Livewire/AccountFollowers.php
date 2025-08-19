<?php

namespace App\Http\Livewire;

use App\Models\Post;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AccountFollowers extends Component
{
    use WithPagination;

    public User $user;

    protected $paginationTheme = 'tailwind';
    public $perPage = 10;

    protected $listeners = ['load-more' => 'loadMore'];

    public function loadMore()
    {
        $this->perPage += 10;
        $this->dispatchBrowserEvent('loaded-more');
    }

    public function mount($user)
    {
        $this->user = $user;
    }

    public function render()
    {
        $followers = $this->user->followers()
            ->paginate($this->perPage);

        return view('livewire.account-followers', [
            'followers' => $followers
        ]);
    }
}
