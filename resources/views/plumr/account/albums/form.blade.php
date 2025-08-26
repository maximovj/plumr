@extends('plumr.layout.app')

@section('main')
<x-main>
    <div class="bg-white mx-auto p-8 rounded-lg shadow-md max-w-4xl h-full">

        @if(count($errors) > 0)
        <div class="bg-red-700 text-white px-2 py-4 mb-4">
            {{ $errors->first() }}
        </div>
        @endif

        <!-- Crear un nuevo artículo -->
        <section>
            <div class="flex justify-between items-center">
                @if($action == 'create')
                <p class="text-lg font-semibold">Crear un nuevo álbum</p>
                @else
                <p class="text-lg font-semibold">Editar álbum</p>
                @endif
                <a href="{{ route('albums.index', ['user' => $user]) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg shadow transition">
                    ← Volver
                </a>
                </d>
        </section>

        <!-- Formularios de editores -->
        <form action="{{ $route }}" method="POST" enctype="multipart/form-data" class="space-y-4" x-data="{ imagePreview: '{{ $album->cover_url ?? '' }}' }">
            @csrf
            @if($action == 'edit')
            @method('PUT')
            @endif

            <!-- Título y Subtítulo en fila -->
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Título -->
                <section class="flex flex-col w-full">
                    <label class="text-gray-700 mb-1">Título</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $album->title ?? '') }}" placeholder="Ingresa un título para el artículo" autocomplete="off" autofocus class="p-3 rounded-lg bg-blue-50 border border-gray-300
                        focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('title') }}" />
                    @error('title')
                    <p class="text-red-500 text-xs my-2">{{ $message }}</p>
                    @enderror
                </section>
            </div>

            <!-- Descripción -->
            <section class="flex flex-col gap-1">
                <label class="text-sm text-gray-700" for="description">Descripción</label>
                <textarea name="description" id="description" placeholder="Ingresa una descripción" autocomplete="off" min="15" max="255" class="rounded-md p-2 shadow-sm bg-white border {{ e_class('description') }}">{{ old('description',$album->description ?? '') }}</textarea>
                @error('description')
                <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </section>

            <section>
                <!-- Etiquetas -->
                <x-input-array type="component" name="tags" label="Etiquetas" :items="$album->tags" labelClass="text-sm text-gray-700" placeholder="Escribe un estado y presiona Enter" inputClass="p-3 rounded-lg bg-blue-50 border border-gray-300
                    focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('title') }}" />
            </section>

            <section x-data="{ visibility: '{{ old('visibility', $album->visibility) }}' }" class="flex flex-col gap-2">
                <label class="text-sm text-gray-700" for="summary">
                    Seleccione modo de visibilidad
                </label>
                <select x-model="visibility" name="visibility" class="p-2 rounded border-2 w-36">
                    <option value="private">Privado</option>
                    <option value="public">Público</option>
                    <option value="followers_only">Protegido</option>
                </select>

                <!-- Si quieres, hasta puedes mostrar dinámicamente el valor -->
                <span class="text-xs text-gray-500 mt-1">
                    Seleccionado: <strong x-text="visibility"></strong>
                </span>
            </section>


            {{-- Portada --}}
            <div class="flex flex-col gap-4">
                <!-- Portada -->
                <section class="flex flex-col w-full">
                    <label class="text-gray-700 mb-1">Portada</label>
                    <input type="file" accept="image/*" name="cover" id="cover" @change="imagePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null" value="{{ old('cover',$album->cover_url ?? '') }}" placeholder="Ingresa fecha de publicación" autocomplete="off" class="p-3 rounded-lg bg-blue-50 border border-gray-300
                        focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('cover') }}" />
                    @error('cover')
                    <p class="text-red-500 text-xs my-2">{{ $message }}</p>
                    @enderror
                </section>

                {{-- Portada Vista Previa --}}
                <section class="flex flex-col w-full">
                    <template x-if="imagePreview">
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 mb-1">Vista previa:</p>
                            <img :src="imagePreview" alt="Imagen previa" class="h-40 w-auto rounded border">
                        </div>
                    </template>
                </section>
            </div>

            <section x-data="{ files: [] }">
                <label class="text-gray-700 mb-1">Archivos</label>
                <input type="file" name="media[]" id="media" accept="image/*,audio/*,video/*,.pdf" multiple @change="files = Array.from($event.target.files)" class="p-3 rounded-lg bg-blue-50 border border-gray-300
               focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm" />

                <template x-if="files.length > 0">
                    <div class="mt-4 space-y-2">
                        <template x-for="(file, index) in files" :key="index">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <template x-if="file.type.startsWith('image/')">
                                    <img :src="URL.createObjectURL(file)" class="h-20 w-auto rounded border shadow-sm">
                                </template>

                                <template x-if="file.type.startsWith('video/')">
                                    <video controls class="h-20 rounded border shadow-sm">
                                        <source :src="URL.createObjectURL(file)" type="video/mp4">
                                    </video>
                                </template>

                                <template x-if="file.type.startsWith('audio/')">
                                    <audio controls class="w-48">
                                        <source :src="URL.createObjectURL(file)" type="audio/mpeg">
                                    </audio>
                                </template>

                                <template x-if="file.type.includes('pdf')">
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded">PDF: <span x-text="file.name"></span></span>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>
            </section>

            <button type="submit" class=" bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-2 rounded-lg shadow transition">{{ $action == 'create' ? 'Crear álbum' : 'Actualizar álbum' }}</button>
        </form>
    </div>
</x-main>
@endsection
