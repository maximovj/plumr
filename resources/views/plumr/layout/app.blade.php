<!DOCTYPE html>
<html lang="es">
<x-head></x-head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <x-navbar></x-navbar>
    <x-app-error-message></x-app-error-message>
    @yield('main')
    <x-footer></x-footer>
    @livewireScripts
</body>
</html>
