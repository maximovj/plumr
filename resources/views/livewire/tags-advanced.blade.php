<!-- Tags -->
@if (!$tags->isEmpty())
    <div class="flex gap-2 flex-wrap">
        @foreach ($tags as $tag)
            @if ($account_tag = is_account_tag($user, $tag))
                <a href="{{ route('main_account', [$account_tag]) }}">
                    <span class="bg-indigo-500 hover:bg-indigo-600 border-2 text-white rounded-full px-2 py-1 text-xs">
                        <i class="bi bi-person-fill"></i> {{ $account_tag->username }}
                    </span>
                </a>
            @else
                <span class="bg-gray-400 border-2 text-white rounded-full px-2 py-1 text-xs">
                    <i class="bi bi-tag-fill"></i> {{ $tag }}
                </span>
            @endif
        @endforeach
    </div>
@else
    <span class="bg-gray-400 border-2 text-white rounded-full px-2 py-1 text-xs">
        <i class="bi bi-tag-fill"></i> Sin etiquetas
    </span>
@endif
