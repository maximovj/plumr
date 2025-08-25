<nav class="bg-gray-800 p-4">
    <div class="flex flex-row justify-between justify-items-center gap-4">
        <div>
            <p class="text-black text-opacity-0">Plumr</p>
            <a href="/">
                <h1 class="text-4xl font-semibold text-indigo-600">Plumr 🚀</h1>
            </a>
        </div>

        @auth
        <div class="flex flex-col justify-end">
                <div class="flex flex-row gap-2 justify-between items-end">
                    <a href="{{ route('main_account', ['user' => auth()->user()->username]) }}">
                        <div class="flex flex-col text-right">
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-blue-500">
                            <i class="bi bi-at"></i>{{ auth()->user()->username }}
                            </span>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-blue-500 text-xs">
                            {{ auth()->user()->profile->fullname }}
                            </span>
                        </div>
                    </a>
                    <a href="{{ route('account.edit', ['user' => $user]) }}">
                            <div
                                class="w-10 h-10 bg-cover bg-no-repeat bg-center rounded-full text-center border-2 border-gray-200 flex items-center justify-center shadow-sm"
                                style="background-image: url('{{ asset(auth()->user()->profile->photo_url) }}')">
                            </div>
                    </a>
                </div>
        </div>
        @endauth

        {{-- @guest
        @if(!Route::is('register') && !Route::is('login'))
        <div class="flex flex-col justify-end">
            <div class="flex flex-column gap-4 justify-items-center">
                <a href="{{ route('login') }}" class="text-white">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="text-white">Registrarme</a>
            </div>
        </div>
        @endif
        @endguest --}}
    </div>
</nav>
