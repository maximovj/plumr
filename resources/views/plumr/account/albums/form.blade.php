@extends('plumr.layout.app')

@section('main')
<x-main>
    <div class="bg-white mx-auto p-8 rounded-lg shadow-md max-w-4xl h-full">

        @if($errors->any())
            <div class="bg-red-700 text-white px-4 py-3 mb-4 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Título de la página --}}
        <section class="flex justify-between items-center mb-6">
            <p class="text-lg font-semibold">
                {{ $action == 'create' ? 'Crear un nuevo álbum' : 'Editar álbum' }}
            </p>
            <a href="{{ route('albums.index', ['user' => $user]) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg shadow transition">
                ← Volver
            </a>
        </section>

        {{-- Formulario --}}
        <form action="{{ $route }}" method="POST" enctype="multipart/form-data" class="space-y-4"
              x-data="{ imagePreview: '{{ $album->cover_url ?? '' }}', files: [], previewFile: null }">
            @csrf
            @if($action == 'edit')
                @method('PUT')
            @endif

            {{-- Título --}}
            <div class="flex flex-col md:flex-row gap-4">
                <section class="flex flex-col w-full">
                    <label class="text-gray-700 mb-1">Título (Requerido)</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $album->title ?? '') }}"
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
                          class="rounded-md p-2 shadow-sm bg-white border {{ e_class('description') }}">{{ old('description',$album->description ?? '') }}</textarea>
                @error('description')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </section>

            {{-- Etiquetas --}}
            <section>
                <x-input-array type="component" name="tags" label="Etiquetas" :items="$album->tags"
                               labelClass="text-sm text-gray-700"
                               placeholder="Escribe un estado y presiona Enter"
                               inputClass="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('title') }}" />
            </section>

            {{-- Visibilidad --}}
            <section x-data="{ visibility: '{{ old('visibility', $album->visibility ?? 'private') }}' }" class="flex flex-col gap-2">
                <label class="text-sm text-gray-700">Seleccione modo de visibilidad</label>
                <select x-model="visibility" name="visibility" class="p-2 rounded border-2 w-36">
                    <option value="private">Privado</option>
                    <option value="public">Público</option>
                    <option value="followers_only">Protegido</option>
                </select>
                <span class="text-xs text-gray-500 mt-1">Seleccionado: <strong x-text="visibility"></strong></span>
            </section>

            {{-- Portada --}}
            <div class="flex flex-col gap-4">
                <section class="flex flex-col w-full">
                    <label class="text-gray-700 mb-1">Portada</label>
                    <input type="file" accept="image/*" name="cover" id="cover"
                           @change="imagePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                           class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('cover') }}">
                    @error('cover')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </section>

                {{-- Vista previa portada --}}
                <section class="flex flex-col w-full">
                    <template x-if="imagePreview">
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 mb-1">Vista previa:</p>
                            <img :src="imagePreview" alt="Imagen previa" class="h-40 w-auto rounded border">
                        </div>
                    </template>
                </section>
            </div>

            {{-- Archivos múltiples --}}
            <section>
                <label class="text-gray-700 mb-1 block">Selecciona uno o más archivos (Opcional)</label>
                <input type="file" name="media[]" id="media" accept="image/*,audio/*,video/*,.pdf" multiple
                       @change="files = Array.from($event.target.files)"
                       class="w-full p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm">
                <small class="text-xs text-gray-400">Se admiten images, audios, videos y PDF</small>

                {{-- Grid de iconos --}}
                <template x-if="files.length > 0">
                    <div class="mt-4 grid grid-cols-3 md:grid-cols-4 gap-4">
                        <template x-for="(file, index) in files" :key="index">
                            <div class="flex flex-col items-center text-xs text-gray-600 space-y-1 cursor-pointer"
                                 @click="previewFile = file">

                                <template x-if="file.type.startsWith('image/')">
                                    <i class="bi bi-image text-4xl text-indigo-500"></i>
                                </template>
                                <template x-if="file.type.startsWith('video/')">
                                    <i class="bi bi-camera-video text-4xl text-red-500"></i>
                                </template>
                                <template x-if="file.type.startsWith('audio/')">
                                    <i class="bi bi-music-note-beamed text-4xl text-green-500"></i>
                                </template>
                                <template x-if="file.type.includes('pdf')">
                                    <i class="bi bi-file-earmark-pdf text-4xl text-red-600"></i>
                                </template>
                                <template x-if="!file.type.startsWith('image/') && !file.type.startsWith('video/') && !file.type.startsWith('audio/') && !file.type.includes('pdf')">
                                    <i class="bi bi-file-earmark text-4xl text-gray-400"></i>
                                </template>

                                <span class="truncate w-20 text-center" x-text="file.name"></span>
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Modal Preview --}}
                <div x-show="previewFile" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                     x-transition.opacity>
                    <div class="bg-white rounded-lg shadow-lg p-4 max-w-lg max-h-screen">

                        {{-- Botón cerrar --}}
                        <button type="button" @click="previewFile = null"
                                class="absolute top-3 right-3 w-10 h-10 flex items-center justify-center bg-white rounded-full shadow hover:bg-gray-100 transition duration-200">
                            <i class="bi bi-x-lg text-2xl text-gray-700"></i>
                        </button>

                        {{-- Imagen --}}
                        <template x-if="previewFile && previewFile.type.startsWith('image/')">
                            <img :src="URL.createObjectURL(previewFile)" class="max-h-96 w-auto rounded" alt="">
                        </template>

                        {{-- Video --}}
                        <template x-if="previewFile && previewFile.type.startsWith('video/')">
                            <video controls class="max-h-96 w-full rounded">
                                <source :src="URL.createObjectURL(previewFile)" :type="previewFile.type">
                            </video>
                        </template>

                        {{-- Audio --}}
                        <template x-if="previewFile && previewFile.type.startsWith('audio/')">
                            <audio controls class="w-full">
                                <source :src="URL.createObjectURL(previewFile)" :type="previewFile.type">
                            </audio>
                        </template>

                        {{-- PDF --}}
                        <template x-if="previewFile && previewFile.type.includes('pdf')">
                            <iframe :src="URL.createObjectURL(previewFile)" class="w-full h-96"></iframe>
                        </template>

                    </div>
                </div>

            </section>

            @if($action == 'edit')
                <!-- Galería de medios -->
                <section>
                    <div class="p-8 bg-gray-50 border-t">
                        <h2 class="text-lg font-semibold text-gray-800 mb-6">Galería de medios</h2>
                        @livewire('medias-gallery', [
                            'user' => $user,
                            'medias' => $album->medias,
                            'redirect' => route('albums.edit', [$user->username, $album->slug]),
                        ])
                    </div>
                </section>
            @endif

            {{-- Botón enviar --}}
            <button type="submit"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg shadow transition">
                {{ $action == 'create' ? 'Crear álbum' : 'Actualizar álbum' }}
            </button>
        </form>

    </div>
</x-main>
@endsection
