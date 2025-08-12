@extends('plumr.layout.app')

@section('main')
    <x-main>
        <section class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-0">
            <section class="#">
                @if(session('error'))
                <div class="text-center px-4 mb-4 bg-red-500 rounded-md text-white">
                    <h4>{{ session('error') }}</h4>
                </div>
                @endif
                @if(session('success'))
                <div class="text-center px-4 mb-4 bg-green-500 rounded-md text-white">
                    <h4>{{ session('success') }}</h4>
                </div>
                @endif
                <div class="flex flex-row justify-between items-center px-2 mb-4">
                    <h4>Crear una nueva publicación</h4>
                    <a
                    class="bg-gray-700 py-2 px-4 rounded-sm text-white text-sm"
                    role="button"
                    href="{{ route('main_account', ['user' => $user]) }}">
                    Volver
                    </a>
                </div>
                <div class="border-2 border-gray-100 rounded-md">

                    {{-- Crear nueva publicación --}}
                    <div class="p-4 bg-gray-100 shadow-md">
                        <form action="{{ route('post.store', ['user' => $user]) }}" method="POST">
                            @csrf
                            @method('POST')

                            <section class="flex flex-col gap-2 mb-4">
                                <label class="text-xs text-gray-700" for="title">Título</label>
                                <input type="text" name="title" value="{{ old('title') }}" id="title"
                                    placeholder="Ingresa tu Título" autocomplete="off"
                                    class="rounded-md p-2 {{ e_class('title') }} bg-blue-50 shadow-sm" />
                                @error('title')
                                    <p class="text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </section>

                            <section class="flex flex-col gap-2 mb-4">
                                <label class="text-xs text-gray-700" for="content">Contenido</label>
                                <textarea name="content" id="content" placeholder="Ingresa tu contenido"
                                    autocomplete="off"
                                    class="rounded-md p-2 {{ e_class('content') }} bg-blue-50 shadow-sm">{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </section>

                            <section x-data="{ status: [] }"  class="flex flex-col gap-2 mb-4">
                                <label class="text-xs text-gray-700">Estados emocionales</label>

                                <!-- Estados -->
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="(state, index) in status" :key="index">
                                        <span class="bg-gray-200 px-2 py-1 rounded-full text-xs">
                                            <span x-text="state"></span>
                                            <button type="button" class="ml-1 text-red-500" @click="status.splice(index, 1)">×</button>
                                        </span>
                                    </template>
                                </div>

                                <!-- Input -->
                                <input type="text" placeholder="Escribe y presiona Enter"
                                    class="rounded-md p-2 bg-blue-50 shadow-sm"
                                    @keydown.enter.prevent="
                                        if ($event.target.value.trim() !== '') {
                                            status.push($event.target.value.trim());
                                            $event.target.value = '';
                                        }
                                    ">

                                <!-- Campo oculto para enviar datos -->
                                <template x-for="state in status" hidden>
                                    <input type="hidden" name="status[]" :value="state">
                                </template>
                            </section>

                            <section x-data="{ tags: [] }" class="flex flex-col gap-2 mb-4">
                                <label class="text-xs text-gray-700">Etiquetas</label>

                                <!-- Etiquetas -->
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="(tag, index) in tags" :key="index">
                                        <span class="bg-gray-200 px-2 py-1 rounded-full text-xs">
                                            <span x-text="tag"></span>
                                            <button type="button" class="ml-1 text-red-500" @click="tags.splice(index, 1)">×</button>
                                        </span>
                                    </template>
                                </div>

                                <!-- Input -->
                                <input type="text" placeholder="Escribe y presiona Enter"
                                    class="rounded-md p-2 bg-blue-50 shadow-sm"
                                    @keydown.enter.prevent="
                                        if ($event.target.value.trim() !== '') {
                                            tags.push($event.target.value.trim());
                                            $event.target.value = '';
                                        }
                                    ">

                                <!-- Campo oculto para enviar datos -->
                                <template x-for="tag in tags" hidden>
                                    <input type="hidden" name="tags[]" :value="tag">
                                </template>
                            </section>

                            {{-- Botones --}}
                            <section>
                                <button type="submit" class="bg-green-100 p-2 rounded-md">
                                    <span class="text-xs"><i class="bi bi-save"></i> Crear artículo</span>
                                </button>
                            </section>
                        </form>
                    </div>
                </div>
            </section>
            <section style="height: 100vh; max-height: 100vh; overflow: auto;">
                <div class="flex flex-row justify-between px-2 mb-6">
                    <div
                        class="px-2 rounded-full text-center
                        flex  justify-center">
                        <span class="text-xs">
                            <i class="bi bi-file-post-fill"></i>
                            <strong>{{ $posts->count() }}</strong>
                        </span>
                    </div>
                    <h4>Mis publicaciones</h4>
                </div>
                <section class="grid grid-cols-1 gap-2 mx-2 scroll-plumr">
                    @if($posts->count() <= 0)
                        <div class="border-2 border-gray-100 rounded-md">
                            <article class="p-4">
                                <p class="text-center">
                                    No hay publicaciones aún
                                </p>
                            </article>
                        </div>
                    @else
                    @foreach ($posts as $post)
                        <div class="border-2 border-gray-100 rounded-md">
                            <article class="p-4">
                                {{-- Botones --}}
                                <section class="flex flex-row justify-between gap-1 py-2">
                                    <i class="bi bi-chat-square-quote"></i>
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="#" class="bg-yellow-100 p-2 rounded-md">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <a href="#" class="bg-red-100 p-2 rounded-md">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </a>
                                    </div>
                                </section>

                                <h1 class="font-bold">{{ $post->title }}</h1>

                                <p>
                                    {{ $post->content }}
                                </p>

                                {{-- Información de la publicación --}}
                                <section class="flex flex-row gap-1 py-2">
                                    <p class="text-xs">Creado {{ $post->created_at->diffForHumans() }}</p>
                                </section>

                                {{-- Estadísticas --}}
                                <section class="flex flex-row gap-1 py-2">
                                    <p class="text-sm"><i class="bi bi-wechat"></i>&nbsp;<strong>0</strong>&nbsp;Discusiones</p>
                                    <p class="text-sm"><i class="bi bi-1-square"></i></i>&nbsp;<strong>0</strong>&nbsp;Apoyo</p>
                                    <p class="text-sm"><i class="bi bi-1-square"></i></i>&nbsp;<strong>0</strong>&nbsp;Difiero</p>
                                    <p class="text-sm"><i class="bi bi-1-square"></i></i>&nbsp;<strong>0</strong>&nbsp;Neutral</p>
                                </section>

                                {{-- Botones --}}
                                <section class="hidden">
                                    <button class="bg-green-100 p-2 rounded-md">
                                        <i class="bi bi-1-square"></i> Apoyo
                                    </button>
                                    <button class="bg-red-100 p-2 rounded-md">
                                        <i class="bi bi-1-square"></i> Difiero
                                    </button>
                                    <button class="bg-blue-100 p-2 rounded-md">
                                        <i class="bi bi-1-square"></i> Neutral
                                    </button>
                                </section>
                            </article>
                        </div>
                        @endforeach
                    @endif
                </section>
            </section>
        </section>
    </x-main>
@endsection
