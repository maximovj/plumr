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

                {{-- @owner($user)
                    <a href="{{ route('medias.create', $user) }}"
                        class="text-green-600 font-semibold hover:underline text-sm animate__animated animate__pulse animate__infinite">
                        + Agregar multimedia
                    </a>
                @endowner --}}
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
        <section>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($medias as $media)
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
                            background-image: url('{{ asset('storage/media/default_audio.png') }}');
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
                            background-image: url('{{ asset('storage/media/default_pdf.png') }}');
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
                                <button type="button"" @click="showOptions = !showOptions"
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
                                        {{-- <li>
                                            <a
                                            role="button"
                                            x-data
                                            x-on:click="
                                                Livewire.emit('mediaMoveToAlbum',
                                                    {{ $media->albums()->first()->id }},
                                                    {{ $media->id }},
                                                    {{ $user->id }},
                                                    '{{ route('albums.show', [$user, $album]) }}',
                                                );
                                            "
                                            class="block px-4 py-2 text-xs text-gray-700 hover:bg-green-100 rounded">Mover a álbum</a>
                                        </li> --}}
                                        <li>
                                            <a
                                            role="button"
                                            x-data
                                            x-on:click="Livewire.emit('confirmDeleteModelClass',
                                                'App\\Models\\Media', // Clase del modelo
                                                {{ $media->id }},     // ID del registro
                                                '{{ route('medias.index', [$user]) }}', // Redirect (opcional)
                                                '¿Eliminar multimedia?',  // Título (opcional)
                                                'Este multimedia se eliminará permanentemente.' // Mensaje (opcional)
                                            )"
                                            class="block px-4 py-2 text-xs text-gray-700 hover:bg-green-100 rounded">Eliminar</a>
                                        </li>
                                        {{-- <li>
                                            <a href="{{ route('profile.edit', ['user' => $user]) }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-green-100 rounded">Editar</a>
                                        </li> --}}
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div
                        class="p-3 border-t">
                            <p class="text-xs text-gray-600 truncate">{{ $media->title }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No hay medios en este álbum.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-main>
@endsection
