<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js') }}"></script>
    <title>Document</title>
</head>
<body>

    <nav>
        <ul>
            <li>Blogs</li>
            <li>Iniciar sesión</li>
            <li>Registrarme</li>
        </ul>
    </nav>

    <main>
        <section>
            @yield('main')
        </section>
    </main>

</body>
</html>
