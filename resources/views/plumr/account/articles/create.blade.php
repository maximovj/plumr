@extends('plumr.layout.app')

@section('main')
    <x-main>

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
                    <p class="text-red-500 text-xs my-2">{{ $message }}</p>
                @enderror
            </section>

            <!-- Contenido -->
            <section class="flex flex-col gap-1">
                <label class="text-sm text-gray-700" for="summary">Resumen</label>
                <textarea name="summary" id="summary" placeholder="Ingresa un resumen del artículo" autocomplete="off" min="15" max="255"
                class="rounded-md p-2 shadow-sm bg-white border {{ e_class('summary') }}">{{ old('summary') }}</textarea>
                @error('summary') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
            </section>

            {{-- Inicio --}}
            <section class="flex flex-col">
                <label for="header" class="block text-gray-700 mb-1">Inicio</label>
                @livewire('advanced-editor', [
                    'editorId' => 'header_cmp1',
                    'placeholder' => 'Escribe un texto para inicio',
                    'fieldName' => 'header',
                    'content' => old('header', $article->header ?? ''),
                ])

                @error('header')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </section>

            {{-- Desarrollo --}}
            <section class="flex flex-col">
                <label for="content" class="block text-gray-700 mb-1">Desarrollo</label>
                @livewire('advanced-editor', [
                    'editorId' => 'content_cmp1',
                    'placeholder' => 'Escribe un texto para desarrollo',
                    'fieldName' => 'content',
                    'content' => old('content', $article->content ?? ''),
                ])

                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </section>

            {{-- Conclusión --}}
            <section class="flex flex-col">
                <label for="footer" class="block text-gray-700 mb-1">Conclusión</label>
                @livewire('advanced-editor', [
                    'editorId' => 'footer_cmp1',
                    'placeholder' => 'Escribe un texto para conclusión',
                    'fieldName' => 'footer',
                    'content' => old('footer', $article->footer ?? ''),
                ])

                @error('footer')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </section>


            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Enviar Artículo</button>
        </form>

    </x-main>
@endsection
