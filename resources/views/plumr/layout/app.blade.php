<!DOCTYPE html>
<html lang="es">
<x-head></x-head>
<style>[x-cloak]{ display:none !important; }</style>
<body class="bg-gray-50 min-h-screen flex flex-col">
    @livewireScripts
    {{-- Quill is a modern rich text editor built for compatibility and extensibility.  --}}
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/scrapooo/quill-resize-module@1.0.2/dist/quill-resize-module.js"></script>

    @stack('scripts')

    @livewire('confirm-delete-model-class')
    @livewire('post-modal-form')

    <x-navbar></x-navbar>
    <x-app-error-message></x-app-error-message>
    @yield('main')
    <x-footer></x-footer>

</body>
</html>
