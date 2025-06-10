@extends('plumr.layout.app')

@section('main')
<x-main>
    <h1>Tú cuenta</h1>
    <p>Página principal para ver tus artículos y discusiones</p>
    <p>username: {{ auth()->user()->username }}</p>
</x-main>
@endsection
