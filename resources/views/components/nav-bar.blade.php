<nav class="bg-gray-800 p-4">
    <div class="flex flex-row justify-between justify-items-center gap-4">
        <div>
            <p class="text-black text-opacity-0">Plumr</p>
            <a href="/">
                <h1 class="text-4xl font-semibold text-red-800">Plumr</h1>
            </a>
        </div>
        @auth
            <div class="flex flex-col justify-end">
                    <div class="flex flex-row gap-2 justify-between items-end">
                        <a href="{{ route('main_account', ['user' => auth()->user()->username]) }}">
                            <div class="flex flex-col">
                                <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-blue-500">
                                <i class="bi bi-at"></i>{{ auth()->user()->username }}
                                </span>
                                <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-blue-500 text-xs">
                                {{ auth()->user()->name }}
                                </span>
                            </div>
                        </a>
                        <a href="{{ route('account.edit', ['user' => $user]) }}">
                            <div
                            class="w-10 h-10 bg-white rounded-full text-center border-2 border-gray-400 flex items-center justify-center">
                                {{ strtoupper(mb_substr(auth()->user()->username, 0, 1)) }}
                            </div>
                        </a>
                    </div>
            </div>
        @endauth

        @guest
            @if(!Route::is('register') && !Route::is('login'))
            <div class="flex flex-column gap-4 justify-items-center">
                <a href="{{ route('login') }}" class="text-white">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="text-white">Registrarme</a>
            </div>
            @endif
        @endguest
    </div>
</nav>
