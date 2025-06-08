<!DOCTYPE html>
<html lang="en">
<x-head></x-head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <x-navbar></x-navbar>
    @yield('main')
    <x-footer></x-footer>
    @livewireScripts
</body>
</html>
