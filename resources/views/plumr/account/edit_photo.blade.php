@extends('plumr.layout.app')

@section('main')
<x-main>
    @livewire('profile-photo-crop-advanced', [
        'user' => $user
    ])
</x-main>
@endsection
