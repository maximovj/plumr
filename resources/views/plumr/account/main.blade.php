@extends('plumr.layout.app')

@section('main')
    <x-main>
        <section class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-0">
            <section class="mx-4 shadow-md" x-data="{ showOptions: false }">
                <section x-data="{ showOptions: false }"
                    class="flex flex-row justify-start items-center gap-4 p-4 relative
                    bg-center bg-cover bg-no-repeat"
                    style="background-image: url('{{ asset('img/fondo.jpg') }}')">
                    <div class="transition-all duration-500 overflow-hidden" :class="showOptions ? 'h-60' : 'h-44'">
                        <img src="{{ asset('img/user_default.png') }}" alt="Foto de usuario"
                            class="w-32 pt-2 cursor-pointer rounded-full hover:shadow-lg" @click="showOptions = !showOptions" />
                        <p class="font-bold rounded-md pt-2">Fabian Martinez Lopez Perez</p>
                    </div>

                    <!-- Menú flotante con animaciones -->
                    <div class="bg-gray-800 absolute inset-x-0 bottom-0 h-20 px-4" x-show="showOptions"
                        x-transition:enter="animate__animated animate__backInDown"
                        x-transition:leave="animate__animated animate__fadeOutUp">
                        <ul>
                            <li><a href="#" class="text-white hover:text-green-300 text-sm">Cambiar foto de perfil</a>
                            </li>
                            <li><a href="#" class="text-white hover:text-green-300 text-sm">Cambiar portada</a></li>
                            <li><a href="#" class="text-white hover:text-green-300 text-sm">Modificar información</a>
                            </li>
                        </ul>
                    </div>
                </section>

                {{-- Seguidores --}}
                <section class="flex flex-row justify-between items-center px-4 py-2">
                    <div>
                        <a href="#"
                            class="w-10 h-10 bg-blue-500 rounded-full text-center
                                flex items-center justify-center">
                            <i class="bi bi-gear text-white"></i>
                        </a>
                    </div>
                    <div class="flex flex-row items-center px-4 py-2">
                        @foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] as $item)
                            <div
                                class="w-10 h-10 bg-white rounded-full text-center border-2 border-gray-400
                                flex items-center justify-center
                                transform transition ease-out duration-700
                                hover:-translate-y-1.5 hover:shadow-md {{ !$loop->first ? '-ml-4' : '' }}">
                                {{ $item }}
                            </div>
                        @endforeach
                        <div
                            class="w-10 h-10 bg-white rounded-full text-center border-2 border-gray-400
                            flex items-center justify-center -ml-4 z-10">
                            +32</div>
                    </div>
                </section>

                {{-- Información de perfil  --}}
                <section class="flex flex-col gap-1 px-4 py-2">
                    <p class="py-2 text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias, eaque!</p>
                    <h1 class="font-extrabold">
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-blue-500">
                            <i class="bi bi-at"></i>{{ $user->username }}
                        </span>
                    </h1>
                    <p class="text-xs">20/06/1994</p>
                    <p class="text-xs">Hombre</p>
                    <p class="text-xs">Ciudad de México</p>
                </section>

                <hr>

                {{-- Información de perfil  --}}
                <section class="flex flex-col gap-1 px-4 py-2">
                    <p class="text-sm"><i class="bi bi-people-fill">&nbsp;</i><strong>1 000</strong>&nbsp;Seguidores</p>
                    <p class="text-sm"><i class="bi bi-file-post-fill">&nbsp;</i><strong>1 000</strong>&nbsp;Publicaciones</p>
                    <p class="text-sm"><i class="bi bi-perplexity">&nbsp;</i><strong>1 000</strong>&nbsp;Artículos</p>
                    <p class="text-sm"><i class="bi bi-collection">&nbsp;</i><strong>1 000</strong>&nbsp;Multimedia</p>
                    <p class="text-sm"><i class="bi bi-people">&nbsp;</i><strong>1 000</strong>&nbsp;Seguidos</p>
                </section>
            </section>
            <section class="grid grid-cols-1 gap-4 mx-4 scroll-plumr" style="height: 100vh; max-height: 100vh; overflow: auto;">
                <div class="flex flex-row justify-between items-center px-2">
                    <div
                        class="px-2 rounded-full text-center
                        flex items-center justify-center">
                        <span class="text-xs">
                            <i class="bi bi-file-post-fill"></i>
                            <strong>1 000</strong>
                        </span>
                    </div>
                    <h4>Mis publicaciones</h4>
                </div>
                <div class="border-2 border-gray-100 rounded-md">

                    {{-- Crear nuevo publicación --}}
                    <article class="p-4 bg-gray-100 shadow-md">
                        <form>
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
                                <textarea type="text" name="content" value="{{ old('content') }}" id="content"
                                    placeholder="Ingresa tu contenido" autocomplete="off"
                                    class="rounded-md p-2 {{ e_class('content') }} bg-blue-50 shadow-sm"
                                ></textarea>
                                @error('content')
                                    <p class="text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </section>
                        </form>

                        {{-- Botones --}}
                        <section>
                            <button class="bg-green-100 p-2 rounded-md">
                                <span class="text-xs"><i class="bi bi-save"></i> Crear artículo</span>
                            </button>
                        </section>
                    </article>
                </div>
                @foreach ([1,2,3,4,5,6,7,8,9,10] as $articles)
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

                        <h1 class="font-bold">Lorem ipsum dolor sit amet consectetur, adipisicing elit.</h1>
                        <p>
                            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatum mollitia error porro labore quos soluta
                            natus eum impedit quod deserunt architecto consequatur voluptatibus unde, minus ipsam veniam aspernatur et
                            vel?
                        </p>

                        {{-- Información de la publicación --}}
                        <section class="flex flex-row gap-1 py-2">
                            <p class="text-xs">Creado hace 5 minutos</p>
                        </section>

                        {{-- Estadísticas --}}
                        <section class="flex flex-row gap-1 py-2">
                            <p class="text-sm"><i class="bi bi-wechat"></i>&nbsp;<strong>1 000</strong>&nbsp;Discusiones</p>
                            <p class="text-sm"><i class="bi bi-1-square"></i></i>&nbsp;<strong>1 000</strong>&nbsp;Apoyo</p>
                            <p class="text-sm"><i class="bi bi-1-square"></i></i>&nbsp;<strong>1 000</strong>&nbsp;Difiero</p>
                            <p class="text-sm"><i class="bi bi-1-square"></i></i>&nbsp;<strong>1 000</strong>&nbsp;Neutral</p>
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
            </section>
        </section>
    </x-main>
@endsection
