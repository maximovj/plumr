@extends('plumr.layout.app')

@section('main')
<x-main>
    <section class="grid grid-cols-1 gap-6 @owner($user) md:grid-cols-2 @endowner ">

        @owner($user)
        <!-- Crear nueva publicación -->
        <section class="px-4">
            @if(session('error'))
                <div class="text-center mb-4 bg-red-500 rounded-md text-white py-2 px-4">
                    <h4>{{ session('error') }}</h4>
                </div>
            @endif
            @if(session('success'))
                <div class="text-center mb-4 bg-green-500 rounded-md text-white py-2 px-4">
                    <h4>{{ session('success') }}</h4>
                </div>
            @endif

            <div class="flex flex-row justify-between items-start mb-2">
                <a href="{{ route('main_account', ['user' => $user]) }}"
                class="inline-block bg-gray-700 hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition">
                    ← Volver
                </a>
                <h4 class="text-lg font-semibold">Crear una nueva publicación</h4>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-lg shadow-md">
                <form action="{{ route('post.store', ['user' => $user]) }}" method="POST" class="p-4 space-y-4">
                    @csrf @method('POST')

                    <!-- Título -->
                    <div class="flex flex-col gap-1">
                        <label class="text-sm text-gray-700" for="title">Título</label>
                        <input type="text" name="title" value="{{ old('title') }}" id="title"
                               placeholder="Ingresa tu Título" autocomplete="off"
                               class="rounded-md p-2 shadow-sm bg-white border {{ e_class('title') }}" />
                        @error('title') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Contenido -->
                    <div class="flex flex-col gap-1">
                        <label class="text-sm text-gray-700" for="content">Contenido</label>
                        <textarea name="content" id="content" placeholder="Ingresa tu contenido" autocomplete="off"
                                  class="rounded-md p-2 shadow-sm bg-white border {{ e_class('content') }}">{{ old('content') }}</textarea>
                        @error('content') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Estados emocionales -->
                    <div x-data="{ status: [] }" class="flex flex-col gap-1">
                        <label class="text-sm text-gray-700">Estados emocionales</label>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <template x-for="(state, index) in status" :key="index">
                                <span class="bg-gray-200 px-2 py-1 rounded-full text-xs flex items-center">
                                    <span x-text="state"></span>
                                    <button type="button" class="ml-1 text-red-500 font-bold" @click="status.splice(index, 1)">×</button>
                                </span>
                            </template>
                        </div>
                        <input type="text" placeholder="Escribe y presiona Enter"
                               class="rounded-md p-2 shadow-sm bg-white border"
                               @keydown.enter.prevent="
                                    if ($event.target.value.trim() !== '') {
                                        status.push($event.target.value.trim());
                                        $event.target.value = '';
                                    }
                               ">
                        <template x-for="state in status" hidden>
                            <input type="hidden" name="status[]" :value="state">
                        </template>
                    </div>

                    <!-- Etiquetas -->
                    <div x-data="{ tags: [] }" class="flex flex-col gap-1">
                        <label class="text-sm text-gray-700">Etiquetas</label>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <template x-for="(tag, index) in tags" :key="index">
                                <span class="bg-gray-200 px-2 py-1 rounded-full text-xs flex items-center">
                                    <span x-text="tag"></span>
                                    <button type="button" class="ml-1 text-red-500 font-bold" @click="tags.splice(index, 1)">×</button>
                                </span>
                            </template>
                        </div>
                        <input type="text" placeholder="Escribe y presiona Enter"
                               class="rounded-md p-2 shadow-sm bg-white border"
                               @keydown.enter.prevent="
                                    if ($event.target.value.trim() !== '') {
                                        tags.push($event.target.value.trim());
                                        $event.target.value = '';
                                    }
                               ">
                        <template x-for="tag in tags" hidden>
                            <input type="hidden" name="tags[]" :value="tag">
                        </template>
                    </div>

                    <!-- Botón crear -->
                    <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md transition">
                        <i class="bi bi-save"></i> Crear artículo
                    </button>
                </form>
            </div>
        </section>
        @endowner

        <!-- Mis publicaciones -->
        <section class="px-4" style="max-height: 90vh; overflow-y: auto;">
            <div class="flex justify-between items-center mb-4">
                <div class="text-sm text-gray-600 flex items-center gap-2">
                    <i class="bi bi-file-post-fill"></i> <strong>{{ $posts->count() }}</strong>
                </div>
                @owner($user)
                <h4 class="text-lg font-semibold">Mis publicaciones</h4>
                @else
                <h4>Publicaciones de
                    <a href="{{ route('main_account', ['user' => $user]) }}">
                        <span class="font-bold">{{ "@".$user->username  }}</span>
                    </a>
                </h4>
                @endowner
            </div>

            @if($posts->count() <= 0)
                <div class="bg-white border border-gray-200 rounded-md p-4 shadow-sm text-center text-gray-500">
                    No hay publicaciones aún
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($posts as $post)
                        <div class="bg-white border border-gray-200 rounded-md shadow-sm p-4">

                            <div class="flex justify-between items-start mb-2">
                                <h5 class="font-bold text-gray-800">{{ $post->title }}</h5>
                                <div class="flex gap-2">
                                    <a href="{{ route('post.show', [$user, $post]) }}" class="text-gray-600 hover:text-blue-500"><i class="bi bi-chat-square-quote"></i></a>
                                    @owner($user)
                                        <a href="#" class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded hover:bg-yellow-200 text-xs flex items-center gap-1"><i class="bi bi-pencil"></i> Editar</a>
                                        <a href="#" class="bg-red-100 text-red-700 px-2 py-1 rounded hover:bg-red-200 text-xs flex items-center gap-1"><i class="bi bi-trash"></i> Eliminar</a>
                                    @endowner
                                </div>
                            </div>

                            <p class="text-gray-700">{{ $post->content }}</p>
                            <div class="flex justify-between text-xs text-gray-500 mt-2">
                                <p>Creado {{ $post->created_at->diffForHumans() }}</p>
                                <div class="flex gap-3">
                                    <p><i class="bi bi-wechat"></i> <strong>0</strong> Discusiones</p>
                                    <p><i class="bi bi-1-square"></i> <strong>0</strong> Apoyo</p>
                                    <p><i class="bi bi-1-square"></i> <strong>0</strong> Difiero</p>
                                    <p><i class="bi bi-1-square"></i> <strong>0</strong> Neutral</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </section>
</x-main>
@endsection
