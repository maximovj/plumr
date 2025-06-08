<nav class="bg-gray-800 p-4">
    <div class="flex flex-row justify-between justify-items-center gap-4">
        <div>
            <p class="text-black text-opacity-0">Plumr</p>
            <h1 class="text-4xl font-semibold text-red-800">Plumr</h1>
        </div>
        <div class="flex flex-column gap-4 justify-items-center">
            <a href="{{ route('login') }}" class="text-white">Iniciar sesión</a>
            <a href="{{ route('register.create') }}" class="text-white">Registrarme</a>
        </div>
    </div>
</nav>
