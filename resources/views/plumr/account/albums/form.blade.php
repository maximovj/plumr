@extends('plumr.layout.app')

@section('main')
<x-main>
    <div class="bg-white mx-auto p-8 rounded-xl shadow-lg max-w-4xl">

        {{-- Mensaje de errores --}}
        @if($errors->any())
            <div class="bg-red-600 text-white px-4 py-3 mb-6 rounded-md shadow">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Header --}}
        <section class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">
                {{ $action == 'create' ? 'Crear nuevo álbum' : 'Editar álbum' }}
            </h1>
            <a href="{{ route('albums.index', ['user' => $user]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg shadow transition">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </section>

        {{-- Formulario --}}
        <form action="{{ $route }}" method="POST" enctype="multipart/form-data"
              x-data="{ imagePreview: '{{ $album->cover_url ?? '' }}', files: [], previewFile: null, visibility: '{{ old('visibility', $album->visibility ?? 'private') }}' }"
              class="space-y-6">
            @csrf
            @if($action == 'edit')
                @method('PUT')
            @endif

            {{-- Título --}}
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 flex flex-col">
                    <label class="text-gray-700 font-medium mb-2">Título</label>
                    <input type="text" name="title" id="title"
                           value="{{ old('title', $album->title ?? '') }}"
                           placeholder="Ingresa un título para el álbum"
                           autocomplete="off" autofocus
                           class="p-3 rounded-lg border border-gray-300 bg-blue-50 focus:ring-2 focus:ring-green-400 focus:outline-none shadow-sm {{ e_class('title') }}">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Descripción --}}
            <div class="flex flex-col">
                <label for="description" class="text-gray-700 font-medium mb-2">Descripción</label>
                <textarea name="description" id="description" rows="4"
                          placeholder="Ingresa una descripción"
                          class="p-3 rounded-lg border border-gray-300 bg-white focus:ring-2 focus:ring-green-400 focus:outline-none shadow-sm {{ e_class('description') }}">{{ old('description', $album->description ?? '') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Etiquetas --}}
            <x-input-array type="component" name="tags" label="Etiquetas" :items="$album->tags"
                           labelClass="text-gray-700 font-medium"
                           placeholder="Escribe una etiqueta y presiona Enter"
                           inputClass="p-3 rounded-lg border border-gray-300 bg-blue-50 focus:ring-2 focus:ring-green-400 focus:outline-none shadow-sm {{ e_class('tags') }}" />

            {{-- Visibilidad --}}
            <div class="flex flex-col gap-2">
                <label class="text-gray-700 font-medium">Modo de visibilidad</label>
                <select x-model="visibility" name="visibility"
                        class="p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-400 focus:outline-none w-48">
                    <option value="private">Privado</option>
                    <option value="public">Público</option>
                    <option value="followers_only">Protegido</option>
                </select>
                <span class="text-sm text-gray-500 mt-1">Seleccionado: <strong x-text="visibility"></strong></span>
            </div>

            {{-- Portada --}}
            <div class="flex flex-col gap-4">
                <div class="flex flex-col">
                    <label class="text-gray-700 font-medium mb-2">Portada</label>
                    <input type="file" accept="image/*" name="cover" id="cover"
                           @change="imagePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                           class="p-3 rounded-lg border border-gray-300 bg-blue-50 focus:ring-2 focus:ring-green-400 focus:outline-none shadow-sm {{ e_class('cover') }}">
                    @error('cover')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <template x-if="imagePreview">
                    <div class="mt-2">
                        <p class="text-gray-500 text-sm mb-1">Vista previa:</p>
                        <img :src="imagePreview" alt="Vista previa portada" class="rounded border max-h-48 w-auto">
                    </div>
                </template>
            </div>

            {{-- Archivos múltiples --}}
            <div class="flex flex-col gap-2">
                <label class="text-gray-700 font-medium">Archivos</label>
                <input type="file" name="media[]" id="media" accept="image/*,video/*,audio/*,.pdf" multiple
                       @change="files = Array.from($event.target.files)"
                       class="p-3 rounded-lg border border-gray-300 bg-blue-50 focus:ring-2 focus:ring-green-400 focus:outline-none shadow-sm">

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
            </div>

            {{-- Modal Preview --}}
            <div x-show="previewFile" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                 x-transition.opacity>
                <div class="relative bg-white rounded-xl shadow-lg p-4 max-w-lg max-h-screen">

                    {{-- Botón cerrar --}}
                    <button type="button" @click="previewFile = null" @click="previewFile = null"
                            class="absolute z-50 top right-3 w-18 h-18 px-4 py-2 flex items-center justify-center bg-white rounded-full shadow hover:bg-gray-100 transition">
                        <i class="bi bi-x-lg text-2xl text-gray-700"></i>
                    </button>

                    {{-- Contenido según tipo --}}
                    <template x-if="previewFile && previewFile.type.startsWith('image/')">
                        <img :src="URL.createObjectURL(previewFile)" class="max-h-96 w-auto rounded" alt="">
                    </template>
                    <template x-if="previewFile && previewFile.type.startsWith('video/')">
                        <video controls class="max-h-96 w-full rounded">
                            <source :src="URL.createObjectURL(previewFile)" :type="previewFile.type">
                        </video>
                    </template>
                    <template x-if="previewFile && previewFile.type.startsWith('audio/')">
                        <audio controls class="w-full">
                            <source :src="URL.createObjectURL(previewFile)" :type="previewFile.type">
                        </audio>
                    </template>
                    <template x-if="previewFile && previewFile.type.includes('pdf')">
                        <iframe :src="URL.createObjectURL(previewFile)" class="w-full h-96 rounded"></iframe>
                    </template>
                </div>
            </div>

            {{-- Botón enviar --}}
            <button type="submit"
                    class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition">
                {{ $action == 'create' ? 'Crear álbum' : 'Actualizar álbum' }}
            </button>
        </form>
    </div>
</x-main>
@endsection
