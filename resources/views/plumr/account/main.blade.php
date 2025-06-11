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
                            {{ $user->username }}
                        </span>
                    </h1>
                    <p class="text-sm">20/06/1994</p>
                    <p class="text-sm">Hombre</p>
                    <p class="text-sm">Ciudad de México</p>
                </section>
            </section>
            <section class="mx-4">
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatum mollitia error porro labore quos soluta
                natus eum impedit quod deserunt architecto consequatur voluptatibus unde, minus ipsam veniam aspernatur et
                vel?
            </section>
        </section>
    </x-main>
@endsection
