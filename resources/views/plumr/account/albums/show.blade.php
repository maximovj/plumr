@extends('plumr.layout.app')

@section('main')
<x-main>
    <div class="mx-auto max-w-6xl bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Portada grande -->
        @if($album->cover_url)
            <div class="h-72 w-full overflow-hidden">
                <img src="{{ $album->cover_url }}"
                     alt="Portada del álbum"
                     class="w-full h-full object-cover">
            </div>
        @endif

        <!-- Info básica -->
        <div class="p-8">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">{{ $album->title }}</h1>

                <!-- Botones de acción -->
                <section class="flex flex-row gap-2 mt-4 lg:mt-0">
                    @owner($user)
                    <a href="{{ route('albums.edit', [$user, $album]) }}" class="flex items-center gap-2 bg-yellow-100 text-yellow-700 px-3 py-1 rounded hover:bg-yellow-200 transition">
                        <i class="bi bi-pencil"></i> Editar
                    </a>

                    <button
                        x-data
                        x-on:click="Livewire.emit('confirmDeleteModelClass',
                            'App\\Models\\Album', // Clase del modelo
                            {{ $album->id }},     // ID del registro
                            '{{ route('albums.index', $user) }}', // Redirect (opcional)
                            '¿Eliminar álbum?',  // Título (opcional)
                            'Este artículo se eliminará permanentemente.' // Mensaje (opcional)
                        )"
                        class="flex items-center gap-2 bg-red-100 text-red-700 px-3 py-1 rounded hover:bg-red-200 transition"
                    >
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                    @endowner

                    @auth
                    <a href="{{ route('albums.index', ['user' => $user]) }}"
                       class="px-4 py-2 text-sm font-medium bg-gray-100 hover:bg-gray-200 rounded-lg shadow">
                        ← Volver
                    </a>
                    @endauth
                </section>
            </div>

            <!-- Descripción -->
            @if($album->description)
                <p class="mt-4 text-gray-700">{{ $album->description }}</p>
            @endif

            <!-- Etiquetas -->
            <div class="mt-4 flex flex-wrap gap-2">
                @livewire('tags-advanced', [
                    'user' => $user,
                    'tags' => $album->tags,
                ])
            </div>

            <!-- Visibilidad + fechas -->
            <div class="mt-6 flex items-center justify-between text-sm text-gray-500">
                <span class="px-3 py-1 rounded-lg text-xs font-semibold
                    {{ $album->visibility === 'public' ? 'bg-green-100 text-green-700' :
                       ($album->visibility === 'followers_only' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                    {{ ucfirst(str_replace('_',' ',$album->visibility)) }}
                </span>

                <div class="text-right">
                    <p>Creado: {{ $album->created_at->format('d/m/Y H:i') }}</p>
                    <p>Actualizado: {{ $album->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Galería de medios -->
        <div class="p-8 bg-gray-50 border-t">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">Galería de medios</h2>
        @livewire('medias-gallery', [
            'user' => $user,
            'medias' => $album->medias,
            'album' => $album,
        ])
        </div>

    </div>
</x-main>
@endsection
