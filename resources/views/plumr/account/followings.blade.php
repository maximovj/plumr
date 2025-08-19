@extends('plumr.layout.app')

@section('main')
<x-main>
    <section class="grid grid-cols-1 gap-6 md:grid-cols-1">

        <!-- Seguidores de usuario -->
        @livewire('account-followings', ['user' => $user])

    </section>
</x-main>
@endsection
