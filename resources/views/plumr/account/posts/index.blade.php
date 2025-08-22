@extends('plumr.layout.app')

@section('main')
<x-main>
    <section class="grid grid-cols-1 gap-6">

        <!-- Mis publicaciones -->
        <section class="px-4" style="max-height: 1134px; overflow-y: auto;">
            <div class="flex justify-between items-center mb-4">
                <div class="text-sm text-gray-600 flex items-center gap-2">
                    <i class="bi bi-file-post-fill"></i> <strong>{{ $posts->count() }}</strong>
                </div>
                @owner($user)
                <div>
                    <h4 class="text-lg font-semibold">Mis publicaciones</h4>
                    <button
                    x-data
                    x-on:click="Livewire.emit('postModalForm', {
                        mode: 'create',
                        redirect: '{{ route('post.index', [$user]) }}'
                    })"
                    class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md transition">
                        <i class="bi bi-plus-circle"></i> Nueva publicación
                    </button>
                </div>
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
                                        <button
                                        x-data
                                        x-on:click="Livewire.emit('postModalForm', {
                                            mode: 'edit',
                                            postId: '{{ $post->id }}',
                                            redirect: '{{ route('post.index', [$user]) }}'
                                        })"
                                        class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm flex items-center gap-1 hover:bg-yellow-200 transition">
                                            <i class="bi bi-pencil"></i> Editar
                                        </button>

                                        <button
                                        x-data
                                        x-on:click="Livewire.emit('confirmDeleteModelClass',
                                            'App\\Models\\Post', // Clase del modelo
                                            {{ $post->id }},     // ID del registro
                                            '{{ route('post.index', $user) }}', // Redirect (opcional)
                                            '¿Eliminar publicación?',  // Título (opcional)
                                            'Esta publicación se eliminará permanentemente.' // Mensaje (opcional)
                                        )"
                                        class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm flex items-center gap-1 hover:bg-red-200 transition">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    @endowner
                                </div>
                            </div>

                            <!-- Contenido con scroll si es largo -->
                            <section class="prose prose-lg max-w-none p-4 rounded-md overflow-auto max-h-48 mb-4">
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
