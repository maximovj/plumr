@extends('plumr.layout.app')

@section('main')
    <x-main x-data="{
    summary: '',
    header: '',
    content: '',
    footer: ''
}"
x-init="
    Livewire.on('editorUpdated', ({editorId, _content_}) => {
        if(editorId == 1) summary = _content_;
        if(editorId == 2) header = _content_;
        if(editorId == 3) content = _content_;
        if(editorId == 4) footer = _content_;
    })
">

        <!-- Crear un nuevo artículo -->
        <section style="max-height: 90vh; overflow-y: auto;">
            <section class="flex justify-between items-center">
                <p class="text-lg font-semibold">Crear un nuevo artículo</p>
            </section>
        </section>

        <!-- Formularios de editores -->
        <form action="{{ route('article.store', ['user' => $user]) }}" method="POST" class="space-y-4">
            @csrf

            <!-- Título -->
            <section class="flex flex-col">
                <label class="text-gray-700 mb-1">Título</label>
                <input type="text" name="title" id="title" value="{{ old("title") }}"
                    placeholder="Ingresa un título para el artículo" autocomplete="off" autofocus
                    class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class("title") }}" />
                @error('title')
                    <p class="text-red-500 text-sm my-2">{{ $message }}</p>
                @enderror
            </section>

            <!-- Resumen -->
            <section class="flex flex-col">
                <label class="text-gray-700 mb-1">Resumen</label>
                @livewire('advanced-editor', ['editorId' => 1, 'placeholder' => 'Escribe un texto para resumen'])
                <input type="hidden" name="summary" x-model="summary">
                @error('summary')
                    <p class="text-red-700 text-sm">{{ $message }}</p>
                @enderror
            </section>

            <!-- Inicio -->
            <section class="flex flex-col">
                <label class="text-gray-700 mb-1">Inicio</label>
                @livewire('advanced-editor', ['editorId' => 2, 'placeholder' => 'Escribe un texto para inicio'])
                <input type="hidden" name="header" x-model="header">
            </section>

            <!-- Desarrollo -->
            <section class="flex flex-col">
                <label class="text-gray-700 mb-1">Desarrollo</label>
                @livewire('advanced-editor', ['editorId' => 3, 'placeholder' => 'Escribe un texto para desarrollo'])
                <input type="hidden" name="content" x-model="content">
            </section>

            <!-- Conclusión -->
            <section class="flex flex-col">
                <label class="text-gray-700 mb-1">Conclusión</label>
                @livewire('advanced-editor', ['editorId' => 4, 'placeholder' => 'Escribe un texto para conclusión'])
                <input type="hidden" name="footer" x-model="footer">
            </section>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Enviar Artículo</button>
        </form>

    </x-main>
@endsection
