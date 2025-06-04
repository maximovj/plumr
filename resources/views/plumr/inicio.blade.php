@extends('plumr.layout.base')

@section('main')
<h1>Página principal</h1>

<hr>

<div x-data="{ open: false }">
    <button @click="open = !open">Mostrar mensaje</button>

    <p x-show="open" class="mt-2 text-green-500">
        ¡Hola desde Alpine.js! 🎉
    </p>
</div>

@endsection
