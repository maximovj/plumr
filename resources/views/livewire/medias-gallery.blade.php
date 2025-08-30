<section x-cloak x-data="{
    showModal: false,
    mediaType: '',
    mediaSrc: '',
    mediaTitle: '',
    ended: false,
    currentTime: 0,
    duration: 0,
    interval: null,
    formatTime(seconds) {
        const min = Math.floor(seconds / 60);
        const sec = Math.floor(seconds % 60).toString().padStart(2, '0');
        return `${min}:${sec}`;
    }
}">

    @livewire('media-move-to-album')

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($medias as $media)
            <div class="relative bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden">
                @if (Str::endsWith($media->file_path_url, ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                    <img src="{{ $media->file_path_url }}" class="h-48 w-full object-cover" alt="Imagen">
                @elseif(Str::endsWith($media->file_path_url, ['.mp4', '.webm', '.ogg']))
                    <video class="w-full h-48 object-cover">
                        <source src="{{ $media->file_path_url }}"
                            type="video/{{ pathinfo($media->file_path_url, PATHINFO_EXTENSION) }}">
                    </video>
                @elseif(Str::endsWith($media->file_path_url, ['.mp3', '.wav', '.ogg']))
                    <div style="
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
                    <div style="
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
                @if (Auth::check() && Auth::user()->id === $user->id)
                    <div class="absolute top-2 right-2" x-data="{ showOptions: false }" x-cloak>
                        <button type="button"
                            @click="showOptions = !showOptions"
                            @mouseleave="showOptions = false"
                            class="bg-gray-700 text-white w-10 h-10 p-2 rounded-full hover:bg-gray-800 focus:outline-none relative">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>

                        <!-- Menú desplegable -->
                        <div x-show="showOptions" @mouseenter="showOptions = true" @mouseleave="showOptions = false"
                            @click.outside="showOptions = false" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                            class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg z-10">
                            <ul class="flex flex-col py-2">
                                <li>
                                    <a role="button" x-data
                                        x-on:click="Livewire.emit('mediaMoveToAlbum',
                                    {{ $album->id ?? '"*"' }}, // Album id (requerido)
                                    {{ $media->id }}, // Media id (requerido)
                                    {{ $user->id }}, // User id (requerido)
                                    '{{ $redirect }}', // Redirect (opcional)
                            );"
                                        class="block px-4 py-2 text-xs text-gray-700 hover:bg-green-100 rounded">
                                        Mover a álbum
                                    </a>
                                </li>
                                <li>
                                    <a role="button" x-data
                                        x-on:click="Livewire.emit('confirmDeleteModelClass',
                                    'App\\Models\\Media', // Clase del modelo
                                    {{ $media->id }},     // ID del registro
                                    $wire.redirect, // Redirect (opcional)
                                    '¿Eliminar multimedia?',  // Título (opcional)
                                    'Este multimedia se eliminará permanentemente.' // Mensaje (opcional)
                            );"
                                        class="block px-4 py-2 text-xs text-gray-700 hover:bg-green-100 rounded">Eliminar</a>
                                </li>
                                <li>
                                    <a href="{{ route('medias.edit', [$user, $media]) }}"
                                        class="block px-4 py-2 text-xs text-gray-700 hover:bg-green-100 rounded">Editar</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif

                <div @click="
                            mediaType = '{{ pathinfo($media->file_path_url, PATHINFO_EXTENSION) }}';
                            mediaSrc = '{{ $media->file_path_url }}';
                            mediaTitle = '{{ $media->title }}';
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

    <!-- Modal Lightbox -->
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
        x-transition
        @click.self="showModal = false;
                            if($refs.file_audio) { $refs.file_audio.pause(); $refs.file_audio.currentTime = 0; clearInterval(this.interval); }
                            if($refs.file_video) { $refs.file_video.pause(); $refs.file_video.currentTime = 0; }"
        x-cloak>
        <div class="max-w-5xl w-full p-4">
            <!-- Botón cerrar -->
            <button type="button"
                @click="
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
                <video x-ref="file_video" :key="mediaSrc" controls autoplay
                    class="max-h-[80vh] mx-auto rounded-lg shadow-lg">
                    <source :src="mediaSrc" :type="'video/' + mediaType">
                </video>
            </template>

            <!-- Audio Rediseñado - Con Iconos Indicativos de ±10s -->
            <template x-if="['mp3','wav','ogg'].includes(mediaType)">
                <div class="bg-gray-900 rounded-xl p-5 shadow-lg flex flex-col items-center space-y-4 mt-4">
                    <!-- Título -->
                    <p class="text-white font-semibold text-center truncate w-full" x-text="mediaTitle"></p>

                    <!-- Controles -->
                    <div class="flex items-center justify-center gap-6 relative">
                        <!-- Retroceder 10s -->
                        <div class="flex flex-col items-center">
                            <button type="button"
                                @click="$refs.file_audio.currentTime = Math.max(0, $refs.file_audio.currentTime - 10); $refs.file_audio.play();"
                                class="w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center hover:bg-green-500 transition">
                                <i class="bi bi-arrow-counterclockwise text-white text-xl"></i>
                            </button>
                            <span class="text-xs text-gray-400 mt-1">-10s</span>
                        </div>

                        <!-- Play/Pause -->
                        <button type="button"
                            @click="
                                    if(ended) {
                                        $refs.file_audio.currentTime = 0; // reinicia audio
                                        currentTime = 0;                  // reinicia barra de progreso
                                        ended = false;
                                    }
                                    if($refs.file_audio.paused) {
                                        $refs.file_audio.play();
                                        // reinicia interval para actualizar currentTime
                                        clearInterval(interval);
                                        interval = setInterval(() => {
                                            currentTime = $refs.file_audio.currentTime;
                                            duration = $refs.file_audio.duration || 0;
                                            if(currentTime >= duration) {
                                                clearInterval(interval);
                                                ended = true;
                                            }
                                        }, 200);
                                    } else {
                                        $refs.file_audio.pause();
                                    }
                                "
                            class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center hover:bg-green-600 transition">
                            <i :class="$refs.file_audio.paused ? 'bi bi-play-fill' : 'bi bi-pause-fill'"
                                class="text-white text-2xl"></i>
                        </button>

                        <!-- Avanzar 10s -->
                        <div class="flex flex-col items-center">
                            <button type="button"
                                @click="$refs.file_audio.currentTime = Math.min(duration, $refs.file_audio.currentTime + 10); $refs.file_audio.play();"
                                class="w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center hover:bg-green-500 transition">
                                <i class="bi bi-arrow-clockwise text-white text-xl"></i>
                            </button>
                            <span class="text-xs text-gray-400 mt-1">+10s</span>
                        </div>
                    </div>

                    <!-- Barra de progreso -->
                    <div class="flex items-center w-full gap-2">
                        <span class="text-xs text-gray-400 w-8 text-right" x-text="formatTime(currentTime)">00:00</span>
                        <input type="range" min="0" :max="duration" step="0.01"
                            :value="currentTime"
                            @input="$refs.file_audio.currentTime = $event.target.value; currentTime = $event.target.value;"
                            class="flex-1 h-1 rounded-lg bg-gray-700 accent-green-500">
                        <span class="text-xs text-gray-400 w-8 text-left" x-text="formatTime(duration)">00:00</span>
                    </div>

                    <!-- Audio oculto -->
                    <audio x-ref="file_audio" :key="mediaSrc" class="hidden"
                        @ended="ended = true; clearInterval(interval); currentTime = duration;">
                        <source :src="mediaSrc" :type="'audio/' + mediaType">
                    </audio>
                </div>
            </template>

            <!-- PDF -->
            <template x-if="['pdf'].includes(mediaType)">
                <embed :src="mediaSrc" type="application/pdf" class="w-full h-4/6 rounded border shadow-lg"
                    style="height: 80vh !important;" />
            </template>
        </div>
    </div>
</section>
