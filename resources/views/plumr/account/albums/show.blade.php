@extends('plumr.layout.app')

@section('main')
<x-main>
    <div class="bg-white mx-auto p-8 rounded-lg shadow-md max-w-5xl h-full space-y-8">

        <!-- Encabezado -->
        <section class="flex justify-between items-center">
            <p class="text-lg font-semibold">Detalle del álbum</p>
            <a href="{{ route('albums.index', ['user' => $user]) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg shadow transition">
                ← Volver
            </a>
        </section>

        <!-- Información general -->
        <section>
            <h2 class="text-2xl font-bold text-gray-800">{{ $album->title }}</h2>
            @if($album->description)
                <p class="mt-2 text-gray-600">{{ $album->description }}</p>
            @endif

            <!-- Etiquetas -->
            @if($album->tags && count($album->tags))
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($album->tags as $tag)
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                            #{{ $tag }}
                        </span>
                    @endforeach
                </div>
            @endif

            <!-- Visibilidad -->
            <div class="mt-4">
                <span class="inline-block px-3 py-1 rounded-lg text-xs font-semibold
                    {{ $album->visibility === 'public' ? 'bg-green-100 text-green-700' :
                       ($album->visibility === 'followers_only' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                    {{ ucfirst(str_replace('_',' ',$album->visibility)) }}
                </span>
            </div>
        </section>

        <!-- Portada -->
        @if($album->cover_url)
            <section>
                <img src="{{ $album->cover_url }}" alt="Portada del álbum" class="h-64 w-full object-cover rounded-lg shadow border">
            </section>
        @endif

        <!-- Contenido multimedia -->
        <section>
            <h3 class="text-lg font-semibold mb-4">Medios</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($album->media as $media)
                    <div class="bg-gray-50 rounded-lg shadow p-3 flex flex-col gap-2">
                        @if(Str::endsWith($media->file, ['.jpg','.jpeg','.png','.gif','.webp']))
                            <img src="{{ $media->url }}" alt="Imagen" class="rounded-lg object-cover h-48 w-full">
                        @elseif(Str::endsWith($media->file, ['.mp4','.webm','.ogg']))
                            <video controls class="rounded-lg w-full h-48 object-cover">
                                <source src="{{ $media->url }}" type="video/{{ pathinfo($media->file, PATHINFO_EXTENSION) }}">
                                Tu navegador no soporta reproducción de video.
                            </video>
                        @elseif(Str::endsWith($media->file, ['.mp3','.wav','.ogg']))
                            <audio controls class="w-full mt-2">
                                <source src="{{ $media->url }}" type="audio/{{ pathinfo($media->file, PATHINFO_EXTENSION) }}">
                                Tu navegador no soporta reproducción de audio.
                            </audio>
                        @elseif(Str::endsWith($media->file, ['.pdf']))
                            <div class="flex flex-col items-center">
                                <embed src="{{ $media->url }}" type="application/pdf" class="h-48 w-full rounded border" />
                                <a href="{{ $media->url }}" target="_blank" class="text-sm text-indigo-600 mt-2">Abrir PDF</a>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Archivo no soportado: {{ $media->file }}</p>
                        @endif

                        <p class="text-xs text-gray-600 truncate">{{ $media->file }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No hay medios en este álbum.</p>
                @endforelse
            </div>
        </section>

        <!-- Información adicional -->
        <section class="text-sm text-gray-500 border-t pt-4">
            <p>Creado: {{ $album->created_at->format('d/m/Y H:i') }}</p>
            <p>Última actualización: {{ $album->updated_at->format('d/m/Y H:i') }}</p>
        </section>

    </div>
</x-main>
@endsection
