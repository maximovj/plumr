@extends('plumr.layout.app')

@section('main')
    <x-main>
        <!-- Mis Artículos -->
        <section class="px-4" style="max-height: 90vh; overflow-y: auto;">
            <section class="flex justify-between items-center mb-4">
                <div class="text-sm text-gray-600 flex items-center gap-2">
                    <i class="bi bi bi-perplexity"></i> <strong>{{ $articles->count() }}</strong>
                </div>
                @owner($user)
                    <h4 class="text-lg font-semibold">Mis artículos</h4>
                @else
                    <h4>Artículos de
                        <a href="{{ route('main_account', ['user' => $user]) }}">
                            <span class="font-bold">{{ '@' . $user->username }}</span>
                        </a>
                    </h4>
                @endowner
            </section>

            @if ($articles->count() <= 0)
                <div class="bg-white border border-gray-200 rounded-md p-4 shadow-sm text-center text-gray-500">
                    No hay artículos aún
                </div>
            @else
                <section class="grid grid-cols-1 gap-4 @owner($user) md:grid-cols-1 @endowner">
                    @foreach ($articles as $article)
                        <article class="bg-white border border-gray-200 rounded-md shadow-sm p-4">

                            <div class="flex justify-between items-start mb-2">
                                {{-- <h5 class="font-bold text-gray-800">{{ $article->title }}</h5> --}}
                                <div class="flex gap-2">
                                    <a href="{{ route('post.show', [$user, $article]) }}"
                                        class="text-gray-600 hover:text-blue-500"><i
                                            class="bi bi-file-richtext"></i></a>
                                    @owner($user)
                                        <a href="#"
                                            class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded hover:bg-yellow-200 text-xs flex items-center gap-1"><i
                                                class="bi bi-pencil"></i> Editar</a>
                                        <a href="#"
                                            class="bg-red-100 text-red-700 px-2 py-1 rounded hover:bg-red-200 text-xs flex items-center gap-1"><i
                                                class="bi bi-trash"></i> Eliminar</a>
                                    @endowner
                                </div>
                            </div>

                            {{-- <p class="text-gray-700">{{ $article->content }}</p> --}}
                            <div class="flex justify-between text-xs text-gray-500 mt-2">
                                {{-- <p>Creado {{ $article->created_at->diffForHumans() }}</p> --}}
                                <div class="flex gap-3">
                                    <p><i class="bi bi-wechat"></i> <strong>0</strong> Discusiones</p>
                                    <p><i class="bi bi-1-square"></i> <strong>0</strong> Apoyo</p>
                                    <p><i class="bi bi-1-square"></i> <strong>0</strong> Difiero</p>
                                    <p><i class="bi bi-1-square"></i> <strong>0</strong> Neutral</p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </section>
            @endif
        </section>
    </x-main>
@endsection
