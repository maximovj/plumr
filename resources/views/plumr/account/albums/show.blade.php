@extends('plumr.layout.app')

@section('main')
<x-main>
    <div
        x-data="{ showModal: false, mediaType: '', mediaSrc: '' }"
        class="mx-auto max-w-6xl bg-white rounded-xl shadow-lg overflow-hidden"
    >
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
                    <div class="relative bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden">
                        @if(Str::endsWith($media->file_path_url, ['.jpg','.jpeg','.png','.gif','.webp']))
                            <img src="{{ $media->file_path_url }}" class="h-48 w-full object-cover" alt="Imagen">
                        @elseif(Str::endsWith($media->file_path_url, ['.mp4','.webm','.ogg']))
                            <video class="w-full h-48 object-cover">
                                <source src="{{ $media->file_path_url }}" type="video/{{ pathinfo($media->file_path_url, PATHINFO_EXTENSION) }}">
                            </video>
                        @elseif(Str::endsWith($media->file_path_url, ['.mp3','.wav','.ogg']))
                            <div
                            style="
                            background-image: url('{{ asset('storage/media/music-file.gif') }}');
                            background-position: top;
                            background-size: 100% 100%;
                            background-repeat: no-repeat;"
                            class="h-48 flex items-center justify-center bg-gray-100 text-indigo-600 text-sm">
                                <div class="flex justify-center items-center bg-black bg-opacity-50 w-24 h-14 rounded-full">
                                    <span class="text-gray-200 text-sm font-semibold">🎵 Audio</span>
                                </div>
                            </div>
                        @elseif(Str::endsWith($media->file_path_url, ['.pdf']))
                            <div
                            style="
                            background-image: url('{{ asset('storage/media/pdf.gif') }}');
                            background-position: top;
                            background-size: 100% 100%;
                            background-repeat: no-repeat;"
                            class="h-48 flex items-center justify-center bg-gray-100 ">
                                <div class="flex justify-center items-center bg-black bg-opacity-50 w-24 h-14 rounded-full">
                                    <span class="text-gray-200 text-sm font-semibold">📄 PDF</span>
                                </div>
                            </div>
                        @else
                            <div class="h-48 flex items-center justify-center bg-gray-100 text-gray-500 text-xs">
                                Archivo no soportado
                            </div>
                        @endif

                        <!-- Botón de acciones (solo para el dueño) -->
                        @if(Auth::check() && Auth::user()->id === $user->id)
                            <div class="absolute top-2 right-2" x-data="{ showOptions: false }" x-cloak>
                                <button @click="showOptions = !showOptions"
                                    class="bg-gray-700 text-white w-10 h-10 p-2 rounded-full hover:bg-gray-800 focus:outline-none relative">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>

                                <!-- Menú desplegable -->
                                <div x-show="showOptions" @click.outside="showOptions = false"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-90"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-90"
                                    class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg z-50">
                                    <ul class="flex flex-col py-2">
                                        <li>
                                            <a href="{{ route('account.edit_photo', [$user]) }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-green-100 rounded">Mover de álbum</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('account.edit_cover', [$user]) }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-green-100 rounded">Eliminar</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('profile.edit', ['user' => $user]) }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-green-100 rounded">Editar</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div
                        @click="showModal = true;
                                 mediaType = '{{ pathinfo($media->file_path_url, PATHINFO_EXTENSION) }}';
                                 mediaSrc = '{{ $media->file_path_url }}'"
                        class="p-3 border-t cursor-pointer">
                            <p class="text-xs text-gray-600 truncate">{{ $media->title }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No hay medios en este álbum.</p>
                @endforelse
            </div>
        </div>

        <!-- Modal Lightbox -->
        <div x-show="showModal"
             class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
             x-transition
             @click.self="showModal = false"
             x-cloak>
            <div class="max-w-5xl w-full p-4">
                <!-- Botón cerrar -->
                <button @click="showModal = false"
                        class="absolute top-2 right-2 bg-white text-black rounded-full text-2xl w-10 h-10">&times;</button>

                <!-- Imagen -->
                <template x-if="['jpg','jpeg','png','gif','webp'].includes(mediaType)">
                    <img :src="mediaSrc" class="max-h-[80vh] mx-auto rounded-lg shadow-lg">
                </template>

                <!-- Video -->
                <template x-if="['mp4','webm','ogg'].includes(mediaType)">
                    <video controls autoplay class="max-h-[80vh] mx-auto rounded-lg shadow-lg">
                        <source :src="mediaSrc" :type="'video/' + mediaType">
                    </video>
                </template>

                <!-- Audio -->
                <template x-if="['mp3','wav','ogg'].includes(mediaType)">
                    <audio controls autoplay class="w-full">
                        <source :src="mediaSrc" :type="'audio/' + mediaType">
                    </audio>
                </template>

                <!-- PDF -->
                <template x-if="['pdf'].includes(mediaType)">
                    <embed :src="mediaSrc" type="application/pdf" class="w-full h-4/6 rounded border shadow-lg" style="height: 80vh !important;" />
                </template>
            </div>
        </div>
    </div>
</x-main>
@endsection
