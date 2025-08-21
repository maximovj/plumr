@extends('plumr.layout.app')

@section('main')
<x-main>
    <section class="px-6 py-6 max-h-[90vh] overflow-y-auto">

        <!-- Encabezado -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex flex-col gap-1">
                <div class="text-sm text-gray-500 flex items-center gap-2">
                    <i class="bi bi-journal-text"></i>
                    <strong>{{ $articles->count() }}</strong> artículos
                </div>

                @owner($user)
                    <a href="{{ route('articles.create', $user) }}"
                        class="text-green-600 font-semibold hover:underline text-sm animate__animated animate__pulse animate__infinite">
                        + Crear artículo
                    </a>
                @endowner
            </div>

            <div>
                @owner($user)
                    <h4 class="text-lg font-semibold text-gray-800">Mis artículos</h4>
                @else
                    <h4 class="text-lg text-gray-800">
                        Artículos de
                        <a href="{{ route('main_account', ['user' => $user]) }}" class="font-bold hover:underline">
                            {{ '@' . $user->username }}
                        </a>
                    </h4>
                @endowner
            </div>
        </div>

        @if ($articles->isEmpty())
            <div class="bg-white border border-gray-200 rounded-md p-6 shadow-sm text-center text-gray-400 animate__animated animate__fadeIn">
                No hay artículos aún
            </div>
        @else
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-6">
                @foreach ($articles as $article)
                    <article x-data="{ hover: false }"
                             class="relative bg-white border border-gray-200 rounded-2xl shadow-md overflow-hidden min-h-[550px] w-full"
                             @mouseenter="hover = true" @mouseleave="hover = false">

                        <!-- Imagen de portada -->
                        @if($article->cover_url)
                            <div class="overflow-hidden rounded-t-2xl">
                                <img src="{{ asset($article->cover_url) }}" alt="{{ $article->title }}"
                                     class="object-cover w-full h-56 transform transition duration-500 hover:scale-105">
                            </div>
                        @endif

                        <div class="p-5 flex flex-col">

                            <!-- Título, subtítulo y acciones -->
                            <div class="mb-3 flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    <h5 class="font-bold text-gray-800 text-lg truncate">{{ $article->title }}</h5>
                                    @if($article->subtitle)
                                        <p class="text-gray-500 text-sm truncate">{{ $article->subtitle }}</p>
                                    @endif
                                </div>

                                @owner($user)
                                    <div class="flex flex-row gap-2">
                                        <a href="{{ route('articles.edit', [$user, $article]) }}"
                                           class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded hover:bg-yellow-200 text-sm flex items-center gap-2 transform transition hover:scale-105 whitespace-nowrap">
                                           <i class="bi bi-pencil"></i> Editar
                                        </a>

                                        <button
                                            x-data
                                            x-on:click="Livewire.emit('confirmDeleteModelClass',
                                                'App\\Models\\Article', // Clase del modelo
                                                {{ $article->id }},     // ID del registro
                                                '{{ route('articles.index', $user) }}', // Redirect (opcional)
                                                '¿Eliminar artículo?',  // Título (opcional)
                                                'Este artículo se eliminará permanentemente.' // Mensaje (opcional)
                                            )"
                                            class="flex items-center gap-2 bg-red-100 text-red-700 px-3 py-1 rounded hover:bg-red-200 transition"
                                        >
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>

                                        {{-- @livewire('confirm-delete-model', [
                                            'model' => $article,
                                            'redirect' => route('articles.index', [$user]),
                                            'title' => '¿Eliminar artículo?',
                                            'message' => 'Este artículo se eliminará permanentemente.'
                                        ], key($article->slug.$article->id)) --}}
                                    </div>
                                @endowner
                            </div>

                            <!-- Tags -->
                            @if($article->tags)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($article->tags as $tag)
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Resumen -->
                            <div class="mb-2 h-40 bg-opacity-50 bg-gray-100 p-2 text-gray-700 text-sm rounded">
                                {!! $article->summary !!}
                            </div>

                            <!-- Botón seguir leyendo -->
                            <div class="mb-2">
                                <a href="{{ route('articles.show', [$user, $article]) }}"
                                   class="text-xs text-blue-400 font-bold inline-block  transition">
                                   Seguir leyéndolo...
                                </a>
                            </div>



                            <!-- Redes Sociales -->
                            {{-- @if($article->network_social)
                                <div class="flex gap-3 mb-4 text-gray-500 text-xs flex-wrap">
                                    @foreach($article->network_social as $key => $link)
                                        <a href="{{ $link }}" target="_blank" class="hover:text-blue-500 capitalize">{{ $key }}</a>
                                    @endforeach
                                </div>
                            @endif --}}

                            <!-- Footer -->
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center text-xs text-gray-500 mt-auto gap-4">
                                <!-- Información del autor -->
                                <div class="space-y-1">
                                    <p><i class="bi bi-person"></i> {{ $article->author ?? 'Autor desconocido' }} - {{ $article->profession ?? 'Profesión' }}</p>
                                    <p><i class="bi bi-clock"></i> Creado: {{ $article->created_at->diffForHumans() }}</p>
                                    @if($article->is_publish)
                                        <p><i class="bi bi-check-circle-fill text-green-500"></i> Publicado: {{ $article->published_at ?? '—' }}</p>
                                    @else
                                        <p><i class="bi bi-x-circle-fill text-red-500"></i> Borrador</p>
                                    @endif
                                </div>

                                <!-- Estadísticas -->
                                <div class="flex gap-3 flex-wrap md:gap-4">
                                    <p class="flex items-center gap-1"><i class="bi bi-chat-left-text"></i> <strong>0</strong></p>
                                    <p class="flex items-center gap-1"><i class="bi bi-hand-thumbs-up"></i> <strong>0</strong></p>
                                    <p class="flex items-center gap-1"><i class="bi bi-hand-thumbs-down"></i> <strong>0</strong></p>
                                    <p class="flex items-center gap-1"><i class="bi bi-dash-circle"></i> <strong>0</strong></p>
                                </div>
                            </div>
                        </div>

                        <!-- Badge animado según estado -->
                        <div x-show="hover"
                             class="absolute top-4 right-4 px-3 py-1 rounded text-sm font-semibold animate__animated animate__fadeIn"
                             :class="{'bg-green-100 text-green-700': {{ $article->is_publish }}, 'bg-red-100 text-red-700': !{{ $article->is_publish }}}">
                            {{ $article->is_publish ? 'Publicado' : 'Borrador' }}
                        </div>
                    </article>
                @endforeach
            </section>
        @endif

    </section>
</x-main>
@endsection
