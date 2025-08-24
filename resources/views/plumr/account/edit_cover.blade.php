@extends('plumr.layout.app')

@section('main')
<x-main>
    @livewire('profile-cover-advanced', [
        'user' => $user
    ])
</x-main>
@endsection
