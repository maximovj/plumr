<div>
    @if(auth()->user()->id != $user->id)
        @if(auth()->user()->followings->contains($user->id))
            <button wire:click="toggleFollow" class="bg-red-500 hover:bg-red-600 py-2 px-2 rounded-sm text-white text-xs">Dejar de seguir</button>
        @else
            <button wire:click="toggleFollow" class="bg-blue-500 hover:bg-blue-600 py-2 px-2 rounded-sm text-white text-xs">Seguir</button>
        @endif
    @endif
</div>
