@extends('plumr.layout.app')

@section('main')
<x-main>
    <div
    x-data="{
            showModal: false,
            mediaType: '',
            mediaSrc: '',
            currentTime: 0,
            duration: 0,
            interval: null,
            formatTime(seconds) {
                const min = Math.floor(seconds / 60);
                const sec = Math.floor(seconds % 60).toString().padStart(2, '0');
                return `${min}:${sec}`;
            }
    }"
    class="mx-auto max-w-6xl bg-white rounded-xl shadow-lg overflow-hidden"
    x-cloak
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
                        @click="
                            mediaType = '{{ pathinfo($media->file_path_url, PATHINFO_EXTENSION) }}';
                            mediaSrc = '{{ $media->file_path_url }}';
                            showModal = true;
                            $nextTick(() => {
                                if($refs.file_audio) {
                                    // Cargar audio y reproducir
                                    $refs.file_audio.load();
                                    $refs.file_audio.play();

                                    // Inicializar duración y barra
                                    const audio = $refs.file_audio;
                                    duration = $refs.file_audio.duration || 0;
                                    currentTime = $refs.file_audio.currentTime;
                                    console.log($refs.file_audio, audio);
                                    console.log(duration, currentTime);

                                    // Actualizar barra cada 200ms
                                    this.interval = setInterval(() => {
                                        currentTime = audio.currentTime;
                                        duration = audio.duration || 0;
                                        if(currentTime === duration) {
                                            clearInterval(this.interval);
                                        }
                                    }, 200);

                                }

                                if($refs.file_video) { $refs.file_video.load(); $refs.file_video.play(); }
                            });
                        "
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
        <div

            x-show="showModal"
             class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
             x-transition
             @click.self="showModal = false;
                        if($refs.file_audio) { $refs.file_audio.pause(); $refs.file_audio.currentTime = 0; clearInterval(this.interval); }
                        if($refs.file_video) { $refs.file_video.pause(); $refs.file_video.currentTime = 0; }"
             x-cloak>
            <div class="max-w-5xl w-full p-4">
                <!-- Botón cerrar -->
                <button @click="
                        showModal = false;
                        if($refs.file_audio) { $refs.file_audio.pause(); $refs.file_audio.currentTime = 0; clearInterval(this.interval); }
                        if($refs.file_video) { $refs.file_video.pause(); $refs.file_video.currentTime = 0; }
                    "

                        class="absolute top-2 right-2 bg-white text-black rounded-full text-2xl w-10 h-10">&times;</button>

                <!-- Imagen -->
                <template x-if="['jpg','jpeg','png','gif','webp'].includes(mediaType)">
                    <img :src="mediaSrc" class="max-h-[80vh] mx-auto rounded-lg shadow-lg">
                </template>

                <!-- Video -->
                <template x-if="['mp4','webm','ogg'].includes(mediaType)">
                    <video x-ref="file_video" :key="mediaSrc" controls autoplay class="max-h-[80vh] mx-auto rounded-lg shadow-lg">
                        <source :src="mediaSrc" :type="'video/' + mediaType">
                    </video>
                </template>

                <!-- Audio Rediseñado - Versión Compacta -->
                <template x-if="['mp3','wav','ogg'].includes(mediaType)">
                    <div class="bg-gray-800 rounded-xl p-4 shadow-lg flex flex-col items-center space-y-3 mt-4">
                        <!-- Título -->
                        <p class="text-white font-semibold text-center truncate w-full">{{ $media->title }}</p>

                        <!-- Controles -->
                        <div class="flex items-center justify-center gap-6">
                            <!-- Retroceder 10s -->
                            <button @click="$refs.file_audio.currentTime = Math.max(0, $refs.file_audio.currentTime - 10); $refs.file_audio.play();"
                                    class="w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center hover:bg-green-500 transition">
                                <i class="bi bi-skip-start-fill text-white text-xl"></i>
                            </button>

                            <!-- Play/Pause -->
                            <button @click="$refs.file_audio.paused ? $refs.file_audio.play() : $refs.file_audio.pause()"
                                    class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center hover:bg-green-600 transition">
                                <i :class="$refs.file_audio.paused ? 'bi bi-play-fill' : 'bi bi-pause-fill'" class="text-white text-2xl"></i>
                            </button>

                            <!-- Avanzar 10s -->
                            <button @click="$refs.file_audio.currentTime = Math.min(duration, $refs.file_audio.currentTime + 10); $refs.file_audio.play();"
                                    class="w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center hover:bg-green-500 transition">
                                <i class="bi bi-skip-end-fill text-white text-xl"></i>
                            </button>
                        </div>

                        <!-- Barra de progreso -->
                        <div class="flex items-center w-full gap-2">
                            <span class="text-xs text-gray-400 w-8 text-right" x-text="formatTime(currentTime)">00:00</span>
                            <input type="range"
                                min="0"
                                :max="duration"
                                step="0.01"
                                :value="currentTime"
                                @input="$refs.file_audio.currentTime = $event.target.value; currentTime = $event.target.value;"
                                class="flex-1 h-1 rounded-lg bg-gray-600 accent-green-500">
                            <span class="text-xs text-gray-400 w-8 text-left" x-text="formatTime(duration)">00:00</span>
                        </div>

                        <!-- Audio oculto -->
                        <audio x-ref="file_audio" :key="mediaSrc" class="hidden">
                            <source :src="mediaSrc" :type="'audio/' + mediaType">
                        </audio>
                    </div>
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
