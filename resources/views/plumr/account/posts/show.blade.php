@extends('plumr.layout.app')

@section('main')
    <x-main>
        {{-- Banner de bienvenida (solo invitados) --}}
        @guest
        <section class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white rounded-xl shadow-lg my-6 p-6 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="space-y-2 text-center md:text-left">
                <h1 class="text-2xl font-bold">Bienvenido a <span class="text-yellow-300">PLUMR</span></h1>
                <p class="text-sm opacity-90">Comparte tus ideas, debates y opiniones sobre los acontecimientos actuales.</p>
                <p class="text-sm opacity-75">Haz que tu voz sea escuchada 🚀</p>
            </div>
            <div>
                <a href="{{ route('auth.login') }}"
                   class="bg-white text-indigo-600 px-5 py-2 rounded-full font-semibold shadow hover:bg-gray-100 transition">
                    Iniciar sesión
                </a>
            </div>
        </section>
        @endguest


        {{-- Layout principal --}}
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

            {{-- Columna izquierda: Publicación principal --}}
            <section class="md:col-span-2 space-y-6">

                @auth
                    {{-- Mensajes de error o éxito --}}
                    @if(session('error'))
                        <div class="bg-red-500 text-white px-4 py-2 rounded-lg shadow">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="bg-green-500 text-white px-4 py-2 rounded-lg shadow">
                            {{ session('success') }}
                        </div>
                    @endif
                @endauth

                {{-- Publicación principal --}}
                <div class="bg-white rounded-xl shadow p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-800">Mi publicación</h2>
                        <div class="flex gap-2 items-center">
                            <a href="{{ route('posts.index', ['user' => $user]) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg shadow transition">
                                ← Volver
                            </a>

                            {{-- Acciones solo para el dueño --}}
                            @owner($user)
                                <button x-data
                                    x-on:click="Livewire.emit('postModalForm', {
                                        mode: 'edit',
                                        postId: '{{ $post->id }}',
                                        redirect: '{{ route('posts.index', [$user]) }}'
                                    })"
                                    class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm flex items-center gap-1 hover:bg-yellow-200 transition">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>

                                <button x-data
                                    x-on:click="Livewire.emit('confirmDeleteModelClass',
                                        'App\\Models\\Post', // Clase del modelo
                                        {{ $post->id }},     // ID del registro
                                        '{{ route('posts.index', $user) }}', // Redirect (opcional)
                                        '¿Eliminar publicación?',  // Título (opcional)
                                        'Esta publicación se eliminará permanentemente.' // Mensaje (opcional)
                                    )"
                                    class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm flex items-center gap-1 hover:bg-red-200 transition">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            @endowner
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-600">Título</label>
                            <input type="text" readonly value="{{ old('title', $post->title) }}"
                                class="w-full rounded-md p-3 bg-gray-100 border border-gray-200 text-gray-700 shadow-sm" />
                        </div>

                        {{-- Estados,  Enlaces y Etiquetas --}}
                        {{-- @if(!empty($post->status))
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($post->status as $emotion)
                                    <span class="flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                                                hover:shadow hover:scale-105 transition cursor-default bg-gray-100 text-gray-800">
                                        <i class="bi bi-emoji-neutral"></i>
                                        {{ $emotion }}
                                    </span>
                                @endforeach
                            </div>
                        @endif --}}

                        @if(!empty($post->status))
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($post->status as $emotion)
                                    <span class="flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium border-2 border-gradient-r from-indigo-400 via-pink-400 to-yellow-400 text-gray-700 bg-white/30 backdrop-blur-sm hover:scale-105 transition cursor-default">
                                        <i class="bi bi-emoji-neutral"></i>
                                        {{ $emotion }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        @if(!empty($post->links))
                            <div class="flex gap-3 mt-2">
                                @foreach($post->links as $link)
                                    <a href="{{ $link }}" target="_blank"
                                    class="relative group w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-full hover:border-indigo-500 text-gray-600 hover:text-indigo-600 transition text-xl"
                                    title="{{ $link }}">
                                        <i class="bi bi-link"></i>
                                        <span class="absolute bottom-full mb-1 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
                                            {{ $link }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        @if(!empty($post->tags))
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($post->tags as $tag)
                                <span class="flex items-center gap-1 bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-medium  hover:shadow hover:bg-gray-200 transition hover:scale-105 cursor-pointer">
                                    <i class="bi bi-tag-fill"></i> {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                        @endif

                        <div x-data="{ progress: 0 }" class="relative w-full">

                            {{-- Barra de progreso --}}
                            <div class="h-1 bg-gray-200 rounded-full overflow-hidden mb-2">
                                <div class="h-1 bg-indigo-600 transition-all"
                                    :style="'width:' + progress + '%'">
                                </div>
                            </div>

                            {{-- Contenido con scroll --}}
                            <section
                                class="prose prose-lg max-w-none p-4 rounded-md overflow-auto max-h-64 bg-gray-50 border border-gray-200 shadow-sm"
                                x-ref="content"
                                x-on:scroll="
                                    let el = $refs.content;
                                    let scrollTop = el.scrollTop;
                                    let scrollHeight = el.scrollHeight - el.clientHeight;
                                    progress = Math.round((scrollTop / scrollHeight) * 100);
                                ">
                                <div class="ql-snow">
                                    <div class="ql-editor" contenteditable="false">
                                        {!! $post->content !!}
                                    </div>
                                </div>
                            </section>
                        </div>

                    </div>
                </div>

                {{-- Discusiones --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-chat-dots"></i> Discusiones ({{ $discussions->count() }})
                    </h3>

                    <div class="mt-4 space-y-4">
                        @if($discussions->count() <= 0)
                            <p class="text-center text-gray-500">No hay discusiones aún</p>
                        @else
                            @guest
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                                    <p class="text-gray-700">
                                        Hay <strong>{{ $discussions->count() }}</strong> discusiones en esta publicación
                                    </p>
                                    <a href="{{ route('auth.login') }}"
                                       class="text-indigo-600 font-bold hover:underline">Inicia sesión para participar</a>
                                </div>
                            @endguest

                            @auth
                                @foreach ($discussions as $item)
                                    <article class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm space-y-3">
                                        {{-- Encabezado --}}
                                        <div class="flex justify-between items-center">
                                            <a href="{{ route("posts.show", [$user, $item]) }}"
                                               class="text-indigo-600 font-semibold hover:underline flex items-center gap-1">
                                                <i class="bi bi-chat-square-quote"></i> {{ $item->title }}
                                            </a>
                                            <small class="text-gray-400">{{ $item->created_at->diffForHumans() }}</small>
                                        </div>

                                        {{-- Contenido --}}
                                        <p class="text-gray-700">{{ $item->content }}</p>

                                        {{-- Acciones --}}
                                        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                            <div class="flex gap-3 text-sm text-gray-500">
                                                <span><i class="bi bi-wechat"></i> 0 Discusiones</span>
                                                <span><i class="bi bi-hand-thumbs-up"></i> 0 Apoyo</span>
                                                <span><i class="bi bi-hand-thumbs-down"></i> 0 Difiero</span>
                                                <span><i class="bi bi-circle"></i> 0 Neutral</span>
                                            </div>

                                            <div class="flex gap-2">
                                                <button class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 text-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            @endauth
                        @endif
                    </div>
                </div>
            </section>

            {{-- Columna derecha: botones de compartir --}}
            <aside class="space-y-6">
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="font-bold text-gray-800 mb-4">Compartir publicación</h3>
                    <div x-data="shareButtons()" class="flex flex-col gap-3">
                        <button @click="shareFacebook()" class="bg-blue-600 text-white py-2 rounded-lg flex items-center justify-center gap-2 hover:bg-blue-700">
                            <i class="bi bi-facebook"></i> Facebook
                        </button>
                        <button @click="shareTwitter()" class="bg-gray-800 text-white py-2 rounded-lg flex items-center justify-center gap-2 hover:bg-gray-900">
                            <i class="bi bi-twitter"></i> Twitter / X
                        </button>
                        <button @click="shareWhatsapp()" class="bg-green-500 text-white py-2 rounded-lg flex items-center justify-center gap-2 hover:bg-green-600">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </button>
                        <button @click="shareLinkedin()" class="bg-blue-700 text-white py-2 rounded-lg flex items-center justify-center gap-2 hover:bg-blue-800">
                            <i class="bi bi-linkedin"></i> LinkedIn
                        </button>
                    </div>
                </div>
            </aside>
        </section>
    </x-main>
@endsection
