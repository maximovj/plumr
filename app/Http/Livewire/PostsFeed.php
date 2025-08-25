<?php

namespace App\Http\Livewire;

use App\Models\Post;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class PostsFeed extends Component
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
        $followingIds = $this->user->followings->pluck('id')->push(auth()->id(), $this->user->id);

        $posts = Post::with([
                'author:id,username',
                'author.profile:id,user_id,photo,fullname'
            ])
            ->join('users_posts', 'users_posts.post_id', '=', 'posts.id')
            ->whereIn('users_posts.user_id', $followingIds)
            ->orderBy('posts.created_at', 'desc')
            ->select('posts.*')
            ->paginate($this->perPage);

        return view('livewire.posts-feed', [
            'posts' => $posts
        ]);
    }
}
