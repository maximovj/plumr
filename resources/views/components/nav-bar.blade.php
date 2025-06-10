<nav class="bg-gray-800 p-4">
    <div class="flex flex-row justify-between justify-items-center gap-4">
        <div>
            <p class="text-black text-opacity-0">Plumr</p>
            <a href="/">
                <h1 class="text-4xl font-semibold text-red-800">Plumr</h1>
            </a>
        </div>
        @auth
            <div class="flex flex-column gap-4 justify-items-center">
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf @method('POST')
                    <button class="text-white">Cerrar sesión</button>
                </form>
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
