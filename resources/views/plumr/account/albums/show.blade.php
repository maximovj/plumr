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
                <a href="{{ route('albums.index', ['user' => $user]) }}"
                   class="px-4 py-2 text-sm font-medium bg-gray-100 hover:bg-gray-200 rounded-lg shadow">
                    ← Volver
                </a>
            </div>

            @if($album->description)
                <p class="mt-4 text-gray-700">{{ $album->description }}</p>
            @endif

            <!-- Etiquetas -->
            @if($album->tags && count($album->tags))
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($album->tags as $tag)
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full shadow-sm">
                            #{{ $tag }}
                        </span>
                    @endforeach
                </div>
            @endif

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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($album->media as $media)
                    <div class="bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden">
                        @if(Str::endsWith($media->file, ['.jpg','.jpeg','.png','.gif','.webp']))
                            <img src="{{ $media->url }}" class="h-48 w-full object-cover" alt="Imagen">
                        @elseif(Str::endsWith($media->file, ['.mp4','.webm','.ogg']))
                            <video controls class="w-full h-48 object-cover">
                                <source src="{{ $media->url }}" type="video/{{ pathinfo($media->file, PATHINFO_EXTENSION) }}">
                            </video>
                        @elseif(Str::endsWith($media->file, ['.mp3','.wav','.ogg']))
                            <div class="p-4">
                                <audio controls class="w-full">
                                    <source src="{{ $media->url }}" type="audio/{{ pathinfo($media->file, PATHINFO_EXTENSION) }}">
                                </audio>
                            </div>
                        @elseif(Str::endsWith($media->file, ['.pdf']))
                            <div class="h-48 flex items-center justify-center bg-gray-100">
                                <span class="text-gray-600 text-sm">📄 PDF</span>
                            </div>
                            <div class="p-3 text-center">
                                <a href="{{ $media->url }}" target="_blank" class="text-indigo-600 text-sm">Abrir PDF</a>
                            </div>
                        @else
                            <div class="h-48 flex items-center justify-center bg-gray-100 text-gray-500 text-xs">
                                Archivo no soportado
                            </div>
                        @endif
                        <div class="p-3 border-t">
                            <p class="text-xs text-gray-600 truncate">{{ $media->file }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No hay medios en este álbum.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-main>
@endsection
