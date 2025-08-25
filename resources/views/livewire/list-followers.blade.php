{{-- Seguidores --}}
<div class="flex flex-row items-center px-4 py-2">
    @foreach ($followers as $account)
        <a href="{{ route('main_account', [$account]) }}">
        <div
            class="w-10 h-10 bg-contain bg-no-repeat bg-center rounded-full text-center border-2 border-gray-200
                                flex items-center justify-center shadow-sm
                                transform transition ease-out duration-700
                                hover:-translate-y-1.5 hover:shadow-md {{ !$loop->first ? '-ml-4' : '' }}"
            style="background-image: url('{{ asset($account->profile->photo_url) }}')">
        </div>
        </a>
    @endforeach
    @if($count > 8)
    <div
        class="w-10 h-10 bg-white rounded-full text-center border-2 border-gray-200 shadow-sm
                            flex items-center justify-center -ml-4 z-10">{{ $count }}</div>
    @endif
</div>
