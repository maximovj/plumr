<div>
    @if(auth()->id() != $user->id)
        @if($isFollowing)
            <button wire:click="toggleFollow" class="bg-red-500 hover:bg-red-600 py-2 px-2 rounded-sm text-white text-xs w-32">
                Dejar de seguir
            </button>
        @else
            <button wire:click="toggleFollow" class="bg-blue-500 hover:bg-blue-600 py-2 px-2 rounded-sm text-white text-xs w-32">
                Seguir
            </button>
        @endif
    @endif
</div>
