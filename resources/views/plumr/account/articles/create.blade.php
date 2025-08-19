@extends('plumr.layout.app')

@section('main')
    <x-main>
        <!-- Crear un un nuevo artículo -->
        <section class="px-4" style="max-height: 90vh; overflow-y: auto;">
            <section class="flex justify-between items-center mb-4">
                <p class="text-lg font-semibold">Crear un nuevo artículo</p>
            </section>
        </section>
        @livewire('advanced-editor', [
            'content' => 'Hola mundo'
        ])
    </x-main>
@endsection
