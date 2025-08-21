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
                <a href="{{ route('main_account', ['user' => $user]) }}" class="inline-block bg-gray-700 hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition">
                    ← Volver
                </a>
                <h4 class="text-lg font-semibold">Crear una nueva publicación</h4>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-md p-6">
                <form action="{{ route('post.store', ['user' => $user]) }}" method="POST" class="space-y-5">
                    @csrf @method('POST')

                    <!-- Título -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Ingresa tu Título" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 p-2 @error('title') border-red-500 @enderror">
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Contenido --}}
                    <section class="flex flex-col">
                        <label for="content" class="block text-sm font-medium text-gray-700">Contenido</label>
                        @livewire('advanced-editor', [
                        'editorId' => 'content_cmp1',
                        'placeholder' => 'Escribe un texto para contenido',
                        'fieldName' => 'content',
                        'content' => old('content', $post->content ?? ''),
                        ])
                        @error('content')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </section>

                    <!-- Input de estados emocionales -->
                    <x-input-array name="status" label="Estados de ánimo" placeholder="Escribe un estado y presiona Enter" :suggestions="['Feliz', 'Triste', 'Emocionado']" inputClass="w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 p-2" />

                    <!-- Etiquetas -->
                    <x-input-array name="tags" label="Etiquetas" placeholder="Escribe un etiqueta y presiona Enter" inputClass="w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 p-2" />

                    <!-- Redes sociales -->
                    <x-input-social-links name="links" label="Enlances externos" :networks="[
                            'Ingresa tu enlace externo',
                            'Ingresa tu enlace externo',
                            'Ingresa tu enlace externo',
                        ]" />

                    <!-- Botón crear -->
                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md transition flex items-center justify-center gap-2">
                        <i class="bi bi-save"></i> Crear publicación
                    </button>
                </form>
            </div>
        </section>
        @endowner

        <!-- Mis publicaciones -->
        <section class="px-4" style="max-height: 1134px; overflow-y: auto;">
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

            @if($posts->count() <= 0) <div class="bg-white border border-gray-200 rounded-md p-4 shadow-sm text-center text-gray-500">
                No hay publicaciones aún
                </div>
                @else
                <div class="grid @owner($user) grid-cols-1 @else grid-cols-2 @endowner gap-4">
                    @foreach ($posts as $post)
                        <div class="bg-white shadow rounded-2xl p-6 mb-4">

                            <!-- Header: avatar, nombre y acciones -->
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center gap-3">
                                    <!-- Avatar ficticio -->
                                    <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($user->profile->fullname, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->profile->fullname }}</p>
                                        <p class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('post.show', [$user, $post]) }}" class="text-gray-500 hover:text-blue-500">
                                        <i class="bi bi-chat-square-quote text-lg"></i>
                                    </a>
                                    @owner($user)
                                        <a href="#" class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm flex items-center gap-1 hover:bg-yellow-200 transition">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <a href="#" class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm flex items-center gap-1 hover:bg-red-200 transition">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </a>
                                    @endowner
                                </div>
                            </div>

                            <!-- Contenido con scroll si es largo -->
                            <section class="prose prose-lg max-w-none bg-gray-50 text-gray-800 p-4 rounded-md overflow-auto max-h-48 mb-4">
                                <div class="ql-snow">
                                    <div class="ql-editor" contenteditable="false">
                                        {!! $post->content !!}
                                    </div>
                                </div>
                            </section>

                            <!-- Links -->
                            @if(!empty($post->links))
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach ($post->links as $link)
                                    @if(!empty($link))
                                        <a href="{{ $link }}" target="_blank" class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm hover:bg-blue-200 transition">
                                            <i class="bi bi-link-45deg"></i> {{ $link }}
                                        </a>
                                    @endif
                                    @endforeach
                                </div>
                            @endif

                            <!-- Status -->
                            @if(!empty($post->status))
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach ($post->status as $status)
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">{{ $status }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Tags -->
                            @if(!empty($post->tags))
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach ($post->tags as $tag)
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">#{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Footer: estadísticas tipo social y fechas -->
                            <div class="flex justify-between items-center mt-4 text-gray-500 text-sm">
                                <!-- Estadísticas -->
                                <div class="flex gap-4">
                                    <p class="flex items-center gap-1"><i class="bi bi-wechat"></i> <strong>0</strong> Discusiones</p>
                                    <p class="flex items-center gap-1"><i class="bi bi-1-square"></i> <strong>0</strong> Apoyo</p>
                                    <p class="flex items-center gap-1"><i class="bi bi-1-square"></i> <strong>0</strong> Difiero</p>
                                    <p class="flex items-center gap-1"><i class="bi bi-1-square"></i> <strong>0</strong> Neutral</p>
                                </div>

                                <!-- Fechas -->
                                @owner($user)
                                <div class="text-xs text-gray-400 text-right">
                                    <p>Actualizado: {{ $post->updated_at->diffForHumans() }}</p>
                                    @if($post->deleted_at)
                                        <p>Eliminado: {{ $post->deleted_at->diffForHumans() }}</p>
                                    @endif
                                </div>
                                @endowner
                            </div>
                        </div>
                    @endforeach
                </div>

                @endif
        </section>
    </section>
</x-main>
@endsection
