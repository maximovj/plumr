@extends('plumr.layout.app')

@section('main')
<x-main>
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2">

        <!-- Tarjeta de perfil -->
        <section class="mx-4 bg-white rounded-2xl shadow-lg overflow-hidden relative">

            <!-- Imagen de portada -->
            <div class="relative h-40">
                <div class="h-full w-full bg-center bg-cover" style="background-image: url('{{ $user->profile->cover_url }}')"></div>

                <div class="absolute -bottom-12 left-6">
                    <!-- Foto de usuario -->
                    <img src="{{ $user->profile->photo_url }}" alt="Foto de usuario"
                    class="w-28 h-28 rounded-full border-4 border-white shadow-lg cursor-pointer hover:scale-105 transition transform">

                    <!-- Botones de acciones (solo para el dueño) -->
                    <div class="absolute -bottom-1 right-1  mt-4 flex justify-between items-center gap-2">
                        @if(Auth::check() && Auth::user()->id === $user->id)
                            <a href="{{ route('account.edit', ['user' => $user]) }}"
                                class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white shadow hover:bg-blue-600 transition">
                                <i class="bi bi-gear"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Botón de acciones (solo para el dueño) -->
                @if(Auth::check() && Auth::user()->id === $user->id)
                    <div class="absolute bottom-2 right-2" x-data="{ showOptions: false }">
                        <button @click="showOptions = !showOptions"
                            class="bg-gray-700 text-white w-10 h-10 p-2 rounded-full hover:bg-gray-800 focus:outline-none relative">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>

                        <!-- Menú desplegable -->
                        <div x-show="showOptions" @click.outside="showOptions = false"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90"
                            class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg z-50">
                            <ul class="flex flex-col py-2">
                                <li>
                                    <a href="{{ route('account.edit_photo', [$user]) }}" class="block px-4 py-2 text-gray-700 hover:bg-green-100 rounded">Cambiar foto de perfil</a>
                                </li>
                                <li>
                                    <a href="{{ route('account.edit_cover', [$user]) }}" class="block px-4 py-2 text-gray-700 hover:bg-green-100 rounded">Cambiar portada</a>
                                </li>
                                <li>
                                    <a href="{{ route('profile.edit', ['user' => $user]) }}" class="block px-4 py-2 text-gray-700 hover:bg-green-100 rounded">Modificar información</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Información principal -->
            <div class="mt-16 px-6">
                <p class="font-bold text-xl">{{ $profile->fullname }}</p>
                <h1 class="text-sm text-gray-500">@<span class="font-medium">{{ $user->username }}</span></h1>

                <!-- Bio -->
                @isset($profile->bio)
                    <p class="text-gray-500 text-sm italic mt-2 border-l-2 border-gray-300 pl-2">{{ $profile->bio }}</p>
                @endisset

                <!-- Información adicional -->
                <div class="flex flex-wrap gap-3 mt-3 text-xs text-gray-600">
                    @isset($profile->number_phone)<span><i class="bi bi-telephone-fill"></i> {{ $profile->number_phone }}</span>@endisset
                    <span><i class="bi bi-calendar-fill"></i> {{ $profile->birthday->format('d/m/Y') }}</span>
                    <span><i class="bi bi-gender-ambiguous"></i> {{ $profile->sex }}</span>
                    @isset($profile->country)<span><i class="bi bi-geo-alt-fill"></i> {{ $profile->country }}</span>@endisset
                    @isset($profile->city)<span><i class="bi bi-building"></i> {{ $profile->city }}</span>@endisset
                    @isset($profile->address)<span><i class="bi bi-house-fill"></i> {{ $profile->address }}</span>@endisset
                </div>

                @livewire('list-followers', ['user' => $user])
            </div>

            <hr class="my-2">

            <!-- Estadísticas -->
            <div class="px-6 pb-4 flex flex-col flex-wrap gap-2 text-sm text-gray-700">
                <a href="{{ route('account.followers', ['user' => $user]) }}">
                    <p><i class="bi bi-people-fill"></i> <strong>{{ $user->followers->count() }}</strong> Seguidores</p>
                </a>

                <a href="{{ route('posts.index', ['user' => $user]) }}">
                    <p><i class="bi bi-file-post-fill"></i> <strong>{{ $user->posts->count() }}</strong> Publicaciones</p>
                </a>

                <a href="{{ route('articles.index', ['user' => $user]) }}">
                    @owner($user)
                    <p><i class="bi bi-perplexity"></i> <strong>{{ $user->articles->count() }}</strong> Artículos</p>
                    @else
                    <p><i class="bi bi-perplexity"></i> <strong>{{ $user->articles()->where('is_publish', true)->count() }}</strong> Artículos</p>
                    @endowner
                </a>

                <a href="{{ route('albums.index', ['user' => $user]) }}">
                    @owner($user)
                    <p><i class="bi bi-file-post-fill"></i> <strong>{{ $user->albums->count() }}</strong> Álbumes</p>
                    @else
                        @isfollower(auth()->user(), $user)
                            <p><i class="bi bi-file-post-fill"></i> <strong>{{ $user->albums()->whereIn('visibility', ['public', 'followers_only'])->count() }}</strong> Álbumes</p>
                        @else
                            <p><i class="bi bi-file-post-fill"></i> <strong>{{ $user->albums()->where('visibility', 'public')->count() }}</strong> Álbumes</p>
                        @endisfollower
                    @endowner
                </a>

                <p><i class="bi bi-collection"></i> <strong>1 000</strong> Multimedia</p>

                <a href="{{ route('account.followings', ['user' => $user]) }}">
                    <p><i class="bi bi-people"></i>
                    <strong>{{ $user->followings->count() }}</strong> Seguidos
                    </p>
                </a>
            </div>

            <!-- Botón de seguir -->
            <section class="px-6 pb-4">
                @if(Auth::check() && Auth::user()->id !== $user->id)
                    @livewire('follow-button', ['user' => $user])
                @endif
            </section>
        </section>

        <!-- Feed de posts -->
        @livewire('posts-feed', ['user' => $user])

    </section>
</x-main>
@endsection
