<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js') }}"></script>
    <title>Document</title>
    @livewireStyles
</head>
<body class="bg-gray-50">
    <nav class="bg-gray-200 p-4">
        <div class="flex flex-row justify-between justify-items-center gap-4">
            <div>
                <p class="text-black text-opacity-0">Plumr</p>
                <h1 class="text-4xl font-semibold text-red-800">Plumr</h1>
            </div>
            <div class="flex flex-column gap-4 justify-items-center">
                <a href="#">Iniciar sesión</a>
                <a href="#">Registrarme</a>
            </div>
        </div>
    </nav>

    <main class="m-2 md:m-12">
        <section>
            @yield('main')
        </section>
    </main>

    @livewireScripts
</body>
</html>
