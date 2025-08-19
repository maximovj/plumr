<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AccountFollowings extends Component
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
        $followings = $this->user->followings()
            ->paginate($this->perPage);

        return view('livewire.account-followings', [
            'followings' => $followings
        ]);
    }

}
