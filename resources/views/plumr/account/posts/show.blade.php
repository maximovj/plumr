@extends('plumr.layout.app')

@section('main')
    <x-main>
        @guest
        <section class="bg-gray-600 h-100 my-4 py-4 rounded-sm shadow-sm flex gap-4 justify-around">
            <div class="text-center">
                <p class="text-white font-extrabold">
                    Bienvenido al portal de PLUMR
                </p>
                <p class="text-gray-400 font-light">
                    Ingresa al portal para poder compartir tus opiniones
                </p>
                <p class="text-gray-400 font-light">
                    acerca de los acontencimientos del momento...
                </p>
                <p class="text-gray-300 font-light">
                    Haz que tu voz sea escuchada !!!
                </p>
            </div>
            <div class="text-center">
                <p class="text-white font-extrabold">
                    Bienvenido al portal de PLUMR
                </p>
                <p class="text-gray-400 font-light">
                    Ingresa al portal para poder compartir tus opiniones
                </p>
                <p class="text-gray-400 font-light">
                    acerca de los acontencimientos del momento...
                </p>
                <p class="text-gray-300 font-light">
                    Haz que tu voz sea escuchada !!!
                </p>
            </div>
        </section>
        @endguest

        <section class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-0">
            <section class="#">
                @auth
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
                        <h4>Mi publicación</h4>
                        <a
                        class="bg-gray-700 py-2 px-4 rounded-sm text-white text-sm"
                        role="button"
                        href="{{ route('main_account', ['user' => $user]) }}">
                        Volver
                        </a>
                    </div>
                @endauth
                <div class="border-2 border-gray-100 rounded-md">

                    {{-- Crear nueva publicación --}}
                    <div class="p-4 bg-gray-100 shadow-md">

                            <section class="flex flex-col gap-2 mb-4">
                                <label class="text-xs text-gray-700" for="title">Título</label>
                                <input type="text" name="title" value="{{ old('title', $post->title) }}" id="title"
                                    placeholder="Ingresa tu Título" autocomplete="off" readonly
                                    class="rounded-md p-2 {{ e_class('title') }} bg-blue-50 shadow-sm" />
                                @error('title')
                                    <p class="text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </section>

                            <section class="flex flex-col gap-2 mb-4">
                                <label class="text-xs text-gray-700" for="content">Contenido</label>
                                <textarea name="content" id="content" placeholder="Ingresa tu contenido"
                                    autocomplete="off" readonly
                                    class="rounded-md p-2 {{ e_class('content') }} bg-blue-50 shadow-sm">{{ old('content', $post->content) }}</textarea>
                                @error('content')
                                    <p class="text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </section>

                            <section x-data="{ status: {{ old('tags', $post->status) }} }"  class="flex flex-col gap-2 mb-4">
                                <label class="text-xs text-gray-700">Estados emocionales</label>

                                <!-- Estados -->
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="(state, index) in status" :key="index">
                                        <span class="bg-gray-200 px-2 py-1 rounded-full text-xs">
                                            <span x-text="state"></span>
                                        </span>
                                    </template>
                                </div>
                            </section>

                            <section x-data="{ tags: {{ old('tags', $post->tags) }} }" class="flex flex-col gap-2 mb-4">
                                <label class="text-xs text-gray-700">Etiquetas</label>

                                <!-- Etiquetas -->
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="(tag, index) in tags" :key="index">
                                        <span class="bg-gray-200 px-2 py-1 rounded-full text-xs">
                                            <span x-text="tag"></span>
                                        </span>
                                    </template>
                                </div>
                            </section>
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
                            <strong>{{ $discussions->count() }}</strong>
                        </span>
                    </div>
                    <h4>Discusiones</h4>
                </div>
                <section class="grid grid-cols-1 gap-2 mx-2 scroll-plumr">
                    @if($discussions->count() <= 0)
                        <div class="border-2 border-gray-100 rounded-md">
                            <article class="p-4">
                                <p class="text-center">
                                    No hay discusiones aún
                                </p>
                            </article>
                        </div>
                    @else

                    @guest
                        <div class="border-2 border-gray-100 rounded-md">
                            <article class="p-4">
                                <p class="text-center">
                                    Hay <span class="font-bold">({{$discussions->count()}})</span> discusiones
                                    en marcha en está publicación...
                                </p>
                                <p class="text-center text-green-500 font-bold">
                                    <a href="{{ route('auth.login') }}">
                                        Agregar mi postura
                                    </a>
                                </p>
                            </article>
                        </div>
                    @endguest

                    @auth
                        @foreach ($discussions as $item)
                        <div class="border-2 border-gray-100 rounded-md">
                            <article class="p-4">
                                {{-- Botones --}}
                                <section class="flex flex-row justify-between gap-1 py-2">
                                    <a href="{{ route("post.show", [$user, $item]) }}"><i class="bi bi-chat-square-quote"></i></a>
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="#" class="bg-yellow-100 p-2 rounded-md">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <a href="#" class="bg-red-100 p-2 rounded-md">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </a>
                                    </div>
                                </section>

                                <h1 class="font-bold">{{ $item->title }}</h1>

                                <p>
                                    {{ $item->content }}
                                </p>

                                {{-- Información de la publicación --}}
                                <section class="flex flex-row gap-1 py-2">
                                    <p class="text-xs">Creado {{ $item->created_at->diffForHumans() }}</p>
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
                    @endauth
                    @endif
                </section>
            </section>
        </section>

        @guest
        <section class="bg-green-700 h-100 my-4 py-4 rounded-sm shadow-sm flex gap-4 justify-around">
            <div class="text-center">
                <p class="text-white font-extrabold">
                    Bienvenido al portal de PLUMR
                </p>
                <p class="text-gray-400 font-light">
                    Ingresa al portal para poder compartir tus opiniones
                </p>
                <p class="text-gray-400 font-light">
                    acerca de los acontencimientos del momento...
                </p>
                <p class="text-gray-300 font-light">
                    Haz que tu voz sea escuchada !!!
                </p>
            </div>
            <div class="text-center">
                <p class="text-white font-extrabold">
                    Bienvenido al portal de PLUMR
                </p>
                <p class="text-gray-400 font-light">
                    Ingresa al portal para poder compartir tus opiniones
                </p>
                <p class="text-gray-400 font-light">
                    acerca de los acontencimientos del momento...
                </p>
                <p class="text-gray-300 font-light">
                    Haz que tu voz sea escuchada !!!
                </p>
            </div>
        </section>
        @endguest
    </x-main>
@endsection
