@extends('plumr.layout.app')

@section('main')
<x-main>
    <div class="bg-white mx-auto p-8 rounded-lg shadow-md max-w-4xl h-full space-y-6">

        <!-- Encabezado -->
        <section class="flex justify-between items-center">
            <p class="text-lg font-semibold">Detalle del álbum</p>
            <a href="{{ route('albums.index', ['user' => $user]) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg shadow transition">
                ← Volver
            </a>
        </section>

        <!-- Título -->
        <section>
            <h2 class="text-2xl font-bold text-gray-800">{{ $album->title }}</h2>
            @if($album->description)
                <p class="mt-2 text-gray-600">{{ $album->description }}</p>
            @endif
        </section>

        <!-- Etiquetas -->
        @if($album->tags && count($album->tags))
            <section>
                <p class="text-sm text-gray-700 mb-2">Etiquetas:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($album->tags as $tag)
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                            #{{ $tag }}
                        </span>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Visibilidad -->
        <section>
            <p class="text-sm text-gray-700">Visibilidad:</p>
            <span class="inline-block mt-1 px-3 py-1 rounded-lg text-xs font-semibold
                {{ $album->visibility === 'public' ? 'bg-green-100 text-green-700' :
                   ($album->visibility === 'followers_only' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                {{ ucfirst(str_replace('_',' ',$album->visibility)) }}
            </span>
        </section>

        <!-- Portada -->
        @if($album->cover_url)
            <section>
                <p class="text-sm text-gray-700 mb-2">Portada:</p>
                <img src="{{ $album->cover_url }}" alt="Portada del álbum" class="h-60 w-auto rounded-lg shadow border">
            </section>
        @endif

        <!-- Información adicional -->
        <section class="text-sm text-gray-500 border-t pt-4">
            <p>Creado: {{ $album->created_at->format('d/m/Y H:i') }}</p>
            <p>Última actualización: {{ $album->updated_at->format('d/m/Y H:i') }}</p>
        </section>

    </div>
</x-main>
@endsection
