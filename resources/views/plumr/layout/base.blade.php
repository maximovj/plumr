<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet" />
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
    <script src="{{ mix('js/app.js') }}"></script>
    <title>Document</title>
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <nav class="bg-gray-800 p-4">
        <div class="flex flex-row justify-between justify-items-center gap-4">
            <div>
                <p class="text-black text-opacity-0">Plumr</p>
                <h1 class="text-4xl font-semibold text-red-800">Plumr</h1>
            </div>
            <div class="flex flex-column gap-4 justify-items-center">
                <a href="#" class="text-white">Iniciar sesión</a>
                <a href="#" class="text-white">Registrarme</a>
            </div>
        </div>
    </nav>

    <main class="m-2 md:m-12 flex-1" >
        <section>
            @yield('main')
        </section>
    </main>

    <footer>
        <div class="h-14 bg-gray-800 flex items-center justify-center">
            <p class="text-white">
                Desarrollado por
                <i class="bi bi-github text-4xl"></i>
                <a href="https://github.com/maximovj" class="text-blue-500">Víctor J. </a>
            </p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
