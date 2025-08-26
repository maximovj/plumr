@extends('plumr.layout.app')

@section('main')
<x-main>
    <div class="bg-white mx-auto p-8 rounded-lg shadow-md max-w-4xl h-full space-y-6">

        {{-- Errores generales --}}
        @if ($errors->any())
            <div class="bg-red-600 text-white px-4 py-3 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Header --}}
        <section class="flex justify-between items-center border-b pb-3 mb-4">
            <h2 class="text-xl font-semibold">
                {{ $action === 'create' ? 'Crear un nuevo álbum' : 'Editar álbum' }}
            </h2>
            <a href="{{ route('albums.index', ['user' => $user]) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg shadow transition">
                ← Volver
            </a>
        </section>

        {{-- Formulario --}}
        <form action="{{ $route }}" method="POST" enctype="multipart/form-data"
              class="space-y-6"
              x-data="{ imagePreview: '{{ $album->cover_url ?? '' }}', files: [] }">

            @csrf
            @if($action === 'edit')
                @method('PUT')
            @endif

            {{-- Título --}}
            <div>
                <label for="title" class="block text-gray-700 font-medium mb-1">Título</label>
                <input type="text"
                       name="title"
                       id="title"
                       value="{{ old('title', $album->title ?? '') }}"
                       placeholder="Ingresa un título para el álbum"
                       autocomplete="off"
                       autofocus
                       class="w-full p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('title') }}" />
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descripción --}}
            <div>
                <label for="description" class="block text-gray-700 font-medium mb-1">Descripción</label>
                <textarea name="description"
                          id="description"
                          placeholder="Ingresa una descripción"
                          minlength="15"
                          maxlength="255"
                          class="w-full rounded-lg p-3 shadow-sm bg-white border focus:outline-none focus:ring-2 focus:ring-green-400 {{ e_class('description') }}">{{ old('description', $album->description ?? '') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Etiquetas --}}
            <div>
                <x-input-array
                    type="component"
                    name="tags"
                    label="Etiquetas"
                    :items="$album->tags"
                    labelClass="text-sm text-gray-700 font-medium"
                    placeholder="Escribe un estado y presiona Enter"
                    inputClass="w-full p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('title') }}" />
            </div>

            {{-- Visibilidad --}}
            <div x-data="{ visibility: '{{ old('visibility', $album->visibility) }}' }">
                <label class="block text-sm text-gray-700 font-medium mb-1">Visibilidad</label>
                <select x-model="visibility"
                        name="visibility"
                        class="p-2 rounded-lg border w-40 shadow-sm focus:ring-2 focus:ring-indigo-400">
                    <option value="private">Privado</option>
                    <option value="public">Público</option>
                    <option value="followers_only">Protegido</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Seleccionado: <strong x-text="visibility"></strong></p>
            </div>

            {{-- Portada --}}
            <div>
                <label for="cover" class="block text-gray-700 font-medium mb-1">Portada</label>
                <input type="file"
                       accept="image/*"
                       name="cover"
                       id="cover"
                       @change="imagePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                       class="w-full p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('cover') }}" />
                @error('cover')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                {{-- Preview portada --}}
                <template x-if="imagePreview">
                    <div class="mt-3">
                        <p class="text-sm text-gray-500 mb-1">Vista previa:</p>
                        <img :src="imagePreview" alt="Vista previa portada" class="h-40 w-auto rounded border shadow-sm">
                    </div>
                </template>
            </div>

            {{-- Archivos adicionales --}}
            <div>
                <label for="media" class="block text-gray-700 font-medium mb-1">Archivos</label>
                <input type="file"
                       name="media[]"
                       id="media"
                       accept="image/*,audio/*,video/*,.pdf"
                       multiple
                       @change="files = Array.from($event.target.files)"
                       class="w-full p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm" />

                {{-- Previews dinámicos --}}
                <template x-if="files.length > 0">
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4">
                        <template x-for="(file, index) in files" :key="index">
                            <div class="flex flex-col items-center text-xs text-gray-600 space-y-1">

                                {{-- Imagen --}}
                                <template x-if="file.type.startsWith('image/')">
                                    <img :src="URL.createObjectURL(file)" class="h-24 w-auto rounded border shadow-sm">
                                </template>

                                {{-- Video --}}
                                <template x-if="file.type.startsWith('video/')">
                                    <video controls class="h-24 rounded border shadow-sm">
                                        <source :src="URL.createObjectURL(file)">
                                    </video>
                                </template>

                                {{-- Audio --}}
                                <template x-if="file.type.startsWith('audio/')">
                                    <audio controls class="w-full">
                                        <source :src="URL.createObjectURL(file)">
                                    </audio>
                                </template>

                                {{-- PDF --}}
                                <template x-if="file.type.includes('pdf')">
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded">📄 <span x-text="file.name"></span></span>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Botón submit --}}
            <div class="pt-4 border-t">
                <button type="submit"
                        class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 px-4 rounded-lg shadow transition">
                    {{ $action === 'create' ? 'Crear álbum' : 'Actualizar álbum' }}
                </button>
            </div>
        </form>
    </div>
</x-main>
@endsection
