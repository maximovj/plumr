@extends('plumr.layout.app')

@section('main')
<x-main>
    <div class="px-6 py-6 max-h-[90vh] overflow-y-auto">
        <!-- Encabezado -->
        <section class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex flex-col gap-1">
                <div class="text-sm text-gray-500 flex items-center gap-2">
                    <i class="bi bi-journal-text"></i>
                    <strong>{{ $medias->count() }}</strong> multimedias
                </div>

                @owner($user)
                    <a href="{{ route('medias.create', $user) }}"
                        class="text-green-600 font-semibold hover:underline text-sm animate__animated animate__pulse animate__infinite animate__slow">
                        + Agregar multimedia
                    </a>
                @endowner
            </div>

            <div>
                @owner($user)
                    <h4 class="text-lg font-semibold text-gray-800">Galería de medios</h4>
                @else
                    <h4 class="text-lg text-gray-800">
                        Multimedias de
                        <a href="{{ route('main_account', ['user' => $user]) }}" class="font-bold hover:underline">
                            {{ '@' . $user->username }}
                        </a>
                    </h4>
                @endowner
            </div>
        </section>

        <!-- Galería de medios -->
        @livewire('medias-gallery', [
            'user' => $user,
            'medias' => $medias,
        ])

    </div>
</x-main>
@endsection
