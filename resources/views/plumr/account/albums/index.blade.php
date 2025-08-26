@extends('plumr.layout.app')

@section('main')
<x-main>
    <section class="px-6 py-6 max-h-[90vh] overflow-y-auto">

        <!-- Encabezado -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex flex-col gap-1">
                <div class="text-sm text-gray-500 flex items-center gap-2">
                    <i class="bi bi-journal-text"></i>
                    <strong>{{ $albums->count() }}</strong> álbumes
                </div>

                @owner($user)
                <a href="{{ route('albums.create', $user) }}" class="text-green-600 font-semibold hover:underline text-sm animate__animated animate__pulse animate__infinite">
                    + Crear album
                </a>
                @endowner
            </div>

            <div>
                @owner($user)
                <h4 class="text-lg font-semibold text-gray-800">Mis álbumes</h4>
                @else
                <h4 class="text-lg text-gray-800">
                    Álbumes de
                    <a href="{{ route('main_account', ['user' => $user]) }}" class="font-bold hover:underline">
                        {{ '@' . $user->username }}
                    </a>
                </h4>
                @endowner
            </div>
        </div>

        @if ($albums->isEmpty())
        <div class="bg-white border border-gray-200 rounded-md p-6 shadow-sm text-center text-gray-400 animate__animated animate__fadeIn">
            No hay álbumes aún
        </div>
        @else

        <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($albums as $album)
            <!-- Tarjetas de álbumes -->
            <div
                x-data="{
                    hover: false,
                }"
                class="
                grid grid-flow-row xl:grid-cols-2 gap-4
                bg-gray-50 border-2 p-4 rounded-md shadow max-full h-full
                hover:shadow-lg hover:border-green-500 transform transition-all ease-linear duration-300
                overflow-hidden relative"
                @mouseleave="hover = false"
                @mouseenter="hover = true"
                x-cloak>

                <!-- Sección para Portada, Acciones, Tipo de visibilidad -->
                <div class="order-first xl:block w-full max-h-full lg:h-60 relative">
                    <!-- Portada -->
                    <img loading="lazy" src="{{ asset($album->cover_url) }}" alt="portada.jpg" class="w-full h-full object-fill rounded-md" />

                    <!-- Acciones -->
                    <div class="absolute top-2 right-4 flex flex-col space-y-1">

                        @owner($user)
                        <a href="{{ route('albums.edit', [$user, $album]) }}">
                            <div class="flex justify-center content-center items-center
                            rounded-full bg-black bg-opacity-30 w-8 h-8
                            text-center p-1 border-2 transform hover:scale-110 hover:shadow-lg">
                                <i class="bi bi-pencil-fill text-base text-white"></i>
                            </div>
                        </a>
                        @endowner

                        <a href="{{ route('albums.show', [$user, $album]) }}">
                            <div class="flex justify-center content-center items-center
                            rounded-full bg-black bg-opacity-30 w-8 h-8
                            text-center p-1 border-2 transform hover:scale-110 hover:shadow-lg">
                                <i class="bi bi-eye-fill text-base text-white "></i>
                            </div>
                        </a>
                    </div>

                    <!-- Tipo de visibilidad -->
                    <div
                    x-show="hover"
                    class="absolute flex top-2 left-4 animate__animated animate__fadeIn ">
                            <span
                            class="font-semibold text-xs border text-center p-2 rounded"
                            :class="{
                                'bg-green-100 text-green-700' : {{ $album->visibility == 'public' ? 1 : 0 }},
                                'bg-gray-100 text-gray-700' : {{ $album->visibility == 'private' ? 1 : 0 }},
                                'bg-red-100 text-red-700' : {{ $album->visibility == 'followers_only' ? 1 : 0 }},
                            }"
                            >
                                {{ $album->visibility == 'public' ? 'Público' : '' }}
                                {{ $album->visibility == 'private' ? 'Privado' : '' }}
                                {{ $album->visibility == 'followers_only' ? 'Protegido' : '' }}
                            </span>
                    </div>
                </div>

                <!-- Contenido principal -->
                <div class="flex flex-col justify-between items-baseline">
                    <div>
                        <h4 class="font-bold text-base">{{ $album->title }}</h3>
                        <p class="text-justify whitespace-normal break-words text-sm py-3 pl-1">
                            {{ $album->description ?? 'Sin descripción' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 space-y-2">
                        @if ($album->tags)
                        <div class="flex gap-2 flex-wrap">
                            @foreach ($album->tags as $tag)

                            @if($account_tag = is_account_tag($tag))
                            <a href="{{ route('main_account', [$account_tag]) }}">
                                <span class="bg-indigo-500 hover:bg-indigo-600 border-2 text-white rounded-full px-2 py-1 text-xs">
                                    <i class="bi bi-person-fill"></i> {{ $account_tag->username }}
                                </span>
                            </a>
                            @else
                            <span class="bg-gray-400 border-2 text-white rounded-full px-2 py-1 text-xs">
                                <i class="bi bi-tag-fill"></i> {{ $tag }}
                            </span>
                            @endif
                            @endforeach
                        </div>
                        @else
                        <span class="bg-gray-400 border-2 text-white rounded-full px-2 py-1 text-xs">
                            <i class="bi bi-tag-fill"></i> Sin etiquetas
                        </span>
                        @endif

                        <span class="text-xs text-gray-400">Creado en
                            {{ $album->created_at->diffForHumans() }}</span>
                    </div>
                </div>

            </div>
            @endforeach
        </section>
        @endif

    </section>
</x-main>
@endsection
