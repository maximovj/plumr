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
                        <p class="font-bold rounded-md pt-2">{{ $profile->fullname }}</p>
                    </div>

                    <!-- Menú flotante con animaciones -->
                    <div class="bg-gray-800 absolute inset-x-0 bottom-0 h-20 px-4" x-show="showOptions"
                        x-transition:enter="animate__animated animate__backInDown"
                        x-transition:leave="animate__animated animate__fadeOutUp">
                        <ul>
                            <li><a href="#" class="text-white hover:text-green-300 text-sm">Cambiar foto de perfil</a>
                            </li>
                            <li><a href="#" class="text-white hover:text-green-300 text-sm">Cambiar portada</a></li>
                            <li><a href="{{ route('profile.edit', ['user' => $user]) }}" class="text-white hover:text-green-300 text-sm">Modificar información</a>
                            </li>
                        </ul>
                    </div>
                </section>

                {{-- Seguidores --}}
                <section class="flex flex-row justify-between items-center px-4 py-2">
                    <div>
                        <a href="{{ route('account.edit', ['user' => $user]) }}"
                            class="w-10 h-10 bg-blue-500 rounded-full text-center
                                flex items-center justify-center">
                            <i class="bi bi-gear text-white"></i>
                        </a>
                    </div>

                    @livewire('list-followers',  ['user' => $user])

                </section>

                {{-- Información de perfil  --}}
                <section class="flex flex-col gap-1 px-4 py-2">
                    @isset($profile->bio)
                    <p class="py-2 text-sm text-gray-500 italic border-l border-gray-500 pl-1">{{ $profile->bio }}</p>
                    @endisset
                    <h1 class="font-extrabold">
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-blue-500">
                            <i class="bi bi-at"></i>{{ $user->username }}
                        </span>
                    </h1>

                    @livewire('follow-button', ['user' => $user])

                    @isset($profile->number_phone)
                    <p class="text-xs">{{ $profile->number_phone }}</p>
                    @endisset
                    <p class="text-xs">{{ $profile->birthday->format('d/m/Y') }}</p>
                    <p class="text-xs">{{ $profile->sex }}</p>
                    @isset($profile->country)
                    <p class="text-xs">{{ $profile->country }}</p>
                    @endisset
                    @isset($profile->city)
                    <p class="text-xs">{{ $profile->city }}</p>
                    @endisset
                    @isset($profile->address)
                    <p class="text-xs">{{ $profile->address }}</p>
                    @endisset
                </section>

                <hr>

                {{-- Información de perfil  --}}
                <section class="flex flex-col gap-1 px-4 py-2">
                    <p class="text-sm"><i class="bi bi-people-fill">&nbsp;</i>
                        <strong>{{ $followings->count() }}</strong>&nbsp;Seguidores</p>
                    <a href="{{ route('post.index', ['user' => $user]) }}">
                        <p class="text-sm"><i class="bi bi-file-post-fill">&nbsp;</i><strong>{{ $user->posts->count() }}</strong>&nbsp;Publicaciones</p>
                    </a>
                    <p class="text-sm"><i class="bi bi-perplexity">&nbsp;</i><strong>1 000</strong>&nbsp;Artículos</p>
                    <p class="text-sm"><i class="bi bi-collection">&nbsp;</i><strong>1 000</strong>&nbsp;Multimedia</p>
                    <p class="text-sm"><i class="bi bi-people">&nbsp;</i>
                        <strong>{{ $followers->count() }}</strong>&nbsp;Seguidos</p>
                </section>
            </section>

            @livewire('posts-feed', ['user' => $user])

        </section>
    </x-main>
@endsection
