{{-- Seguidores --}}
<div class="flex flex-row items-center px-4 py-2">
    @foreach ($followers as $item)
        <div
            class="w-10 h-10 bg-white rounded-full text-center border-2 border-gray-400
                                flex items-center justify-center
                                transform transition ease-out duration-700
                                hover:-translate-y-1.5 hover:shadow-md {{ !$loop->first ? '-ml-4' : '' }}">
            {{ $item->profile->user_id }}
        </div>
    @endforeach
    @if($count > 8)
    <div
        class="w-10 h-10 bg-white rounded-full text-center border-2 border-gray-400
                            flex items-center justify-center -ml-4 z-10">{{ $count }}</div>
    @endif
</div>
