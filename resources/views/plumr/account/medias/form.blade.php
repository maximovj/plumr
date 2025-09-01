@extends('plumr.layout.app')

@section('main')
<x-main>
    <div class="bg-white max-w-4xl mx-auto p-8 h-full rounded shadow">

        @if($errors->any())
            <div class="bg-red-700 text-white px-4 py-3 mb-4 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Título de la página --}}
        <section class="flex justify-between items-center mb-6">
            <p class="text-lg font-semibold">
                {{ $action == 'create' ? 'Crear un nuevo multimedia' : 'Editar multimedia' }}
            </p>
            <a href="{{ route('medias.index', ['user' => $user]) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg shadow transition">
                ← Volver
            </a>
        </section>

        <form
            x-data="{
                files: [],
                previewFile: null
            }"
            x-init
            action="{{ $route }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-4"
            x-cloak>
            @csrf
            @if($action == 'edit')
                @method('PUT')
            @endif

            {{-- Título --}}
            <div class="flex flex-col md:flex-row gap-4">
                <section class="flex flex-col w-full">
                    <label class="text-gray-700 mb-1">Título (Requerido)</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $media->title ?? '') }}"
                           placeholder="Ingresa un título para el álbum" autocomplete="off" autofocus
                           class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('title') }}">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </section>
            </div>

            {{-- Descripción --}}
            <section class="flex flex-col gap-1">
                <label class="text-sm text-gray-700" for="description">Descripción</label>
                <textarea name="description" id="description" placeholder="Ingresa una descripción" autocomplete="off"
                          min="15" max="255"
                          class="rounded-md p-2 shadow-sm bg-white border {{ e_class('description') }}">{{ old('description',$media->description ?? '') }}</textarea>
                @error('description')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </section>

            {{-- Etiquetas --}}
            <section>
                <x-input-array type="component" name="tags" label="Etiquetas" :items="$media->tags"
                               labelClass="text-sm text-gray-700"
                               placeholder="Escribe un estado y presiona Enter"
                               inputClass="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('title') }}" />
            </section>

            {{-- Visibilidad --}}
            <section x-data="{ visibility: '{{ old('visibility', $media->visibility ?? 'private') }}' }" class="flex flex-col gap-2">
                <label class="text-sm text-gray-700">Seleccione modo de visibilidad</label>
                <select x-model="visibility" name="visibility" class="p-2 rounded border-2 w-36">
                    <option value="private">Privado</option>
                    <option value="public">Público</option>
                    <option value="followers_only">Protegido</option>
                </select>
                <span class="text-xs text-gray-500 mt-1">Seleccionado: <strong x-text="visibility"></strong></span>
            </section>


            {{-- Archivo único --}}
            <section
                x-data="{
                    file: null,
                    previewFile: null,
                    previewUrl: '{{ $action == 'edit' && $media->file_path ? asset('storage/' . $media->file_path) : '' }}'
                }"
            >
                <label class="text-gray-700 mb-1 block">Seleccione un solo archivo</label>
                <input type="file" name="media" id="media" accept="image/*,audio/*,video/*,.pdf"
                    @change="
                        file = $event.target.files[0];
                        previewUrl = URL.createObjectURL(file);
                    "
                    class="w-full p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm">
                <small class="text-xs text-gray-400">Se admiten images, audios, videos y .pdf</small>
                @error('media')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                {{-- Icono + nombre archivo (nuevo o existente) --}}
                <template x-if="previewUrl">
                    <div class="mt-4 flex flex-col items-center text-xs text-gray-600 space-y-1 cursor-pointer"
                        @click="previewFile = previewUrl">

                        {{-- Iconos según tipo --}}
                        <template x-if="file && file.type.startsWith('image/')">
                            <i class="bi bi-image text-4xl text-indigo-500"></i>
                        </template>
                        <template x-if="file && file.type.startsWith('video/')">
                            <i class="bi bi-camera-video text-4xl text-red-500"></i>
                        </template>
                        <template x-if="file && file.type.startsWith('audio/')">
                            <i class="bi bi-music-note-beamed text-4xl text-green-500"></i>
                        </template>
                        <template x-if="file && file.type.includes('pdf')">
                            <i class="bi bi-file-earmark-pdf text-4xl text-red-600"></i>
                        </template>
                        <template x-if="!file">
                            {{-- Si no se subió uno nuevo → mostramos ícono genérico segun extensión --}}
                            @php
                                $ext = pathinfo($media->file_path ?? '', PATHINFO_EXTENSION);
                            @endphp
                            @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                                <i class="bi bi-image text-4xl text-indigo-500"></i>
                            @elseif(in_array($ext, ['mp4','webm','mov']))
                                <i class="bi bi-camera-video text-4xl text-red-500"></i>
                            @elseif(in_array($ext, ['mp3','wav','ogg']))
                                <i class="bi bi-music-note-beamed text-4xl text-green-500"></i>
                            @elseif($ext === 'pdf')
                                <i class="bi bi-file-earmark-pdf text-4xl text-red-600"></i>
                            @else
                                <i class="bi bi-file-earmark text-4xl text-gray-400"></i>
                            @endif
                        </template>

                        <span class="truncate w-20 text-center" x-text="file ? file.name : '{{ basename($media->file_path ?? '') }}'"></span>
                    </div>
                </template>

                {{-- Modal Preview --}}
                <div x-cloak x-show="previewFile" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                    x-transition.opacity>
                    <div class="bg-white rounded-lg shadow-lg p-4 max-w-lg max-h-screen relative">

                        {{-- Botón cerrar --}}
                        <button type="button" @click="previewFile = null"
                                class="absolute top-3 right-3 w-10 h-10 flex items-center justify-center bg-white rounded-full shadow hover:bg-gray-100 transition duration-200">
                            <i class="bi bi-x-lg text-2xl text-gray-700"></i>
                        </button>

                        {{-- Imagen --}}
                        <template x-if="previewFile && previewFile.match(/\.(jpg|jpeg|png|gif|webp)$/i)">
                            <img :src="previewFile" class="max-h-96 w-auto rounded" alt="">
                        </template>

                        {{-- Video --}}
                        <template x-if="previewFile && previewFile.match(/\.(mp4|webm|mov)$/i)">
                            <video controls class="max-h-96 w-full rounded">
                                <source :src="previewFile">
                            </video>
                        </template>

                        {{-- Audio --}}
                        <template x-if="previewFile && previewFile.match(/\.(mp3|wav|ogg)$/i)">
                            <audio controls class="w-full">
                                <source :src="previewFile">
                            </audio>
                        </template>

                        {{-- PDF --}}
                        <template x-if="previewFile && previewFile.match(/\.pdf$/i)">
                            <iframe :src="previewFile" class="w-full h-96"></iframe>
                        </template>

                    </div>
                </div>
            </section>

            <!--- Seleccionar álbum --->
            <section class="flex flex-col gap-2">
                <label class="text-sm text-gray-700">Seleccionar álbum (Opcional)</label>
                <ul class="max-h-40 overflow-auto relative space-y-4">
                    @forelse ($user->albums as $album)
                    <li class="flex justify-start items-center gap-4 w-full">
                        <input type="checkbox" class="w-6 h-6 inline"
                        name="albums[]" id="albums[]" value="{{ $album->id }}"
                        @if($media->albums()->get()->contains($album->id)) checked  @endif>
                        <a class="text-indigo-600 hover:text-indigo-700 hover:underline cursor-pointer" href="{{ route('albums.show', [$user, $album]) }}">
                            <span class="block">
                                {{ $album->title }} ({{ $album->medias()->count() }})
                            </span>
                        </a>
                    </li>
                    @empty
                    <li class="flex justify-start items-center gap-4 w-full">
                        <span class="text-xs text-gray-400">Aún no hay álbumes</span>
                    </li>
                    @endforelse
                </ul>
            </section>

            {{-- Botón enviar --}}
            <button type="submit"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg shadow transition">
                {{ $action == 'create' ? 'Crear multimedia' : 'Actualizar multimedia' }}
            </button>
        </form>

    </div>
</x-main>
@endsection
