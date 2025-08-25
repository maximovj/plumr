@extends('plumr.layout.app')

@section('main')
    <x-main class="m-0 h-screen flex items-center justify-center bg-gray-50">

        <div class="relative w-full max-w-prose p-10 bg-white rounded-2xl shadow-lg space-y-6">

            <!-- Botón Volver -->
            <div>
                <a href="{{ route('main_account', ['user' => $user]) }}"
                    class="inline-block bg-gray-700 hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition">
                    ← Volver
                </a>
            </div>

            <!-- Formulario de actualización -->
            <section class="space-y-5">
                <!-- Usuario -->
                <div class="flex flex-col">
                    <label for="username" class="text-gray-700 font-medium">Nombre de usuario</label>
                    <input disabled type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                        placeholder="Ingresa un nuevo nombre de usuario"
                        class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('username') }}">
                    <p class="text-xs text-gray-500 mt-1">Este nombre de usuario se usa para identificarte.</p>
                </div>

                <!-- Email -->
                <div class="flex flex-col">
                    <label for="email" class="text-gray-700 font-medium">Correo electrónico</label>
                    <input disabled type="text" name="email" id="email" value="{{ old('email', $user->email) }}"
                        placeholder="Ingresa tu correo electrónico"
                        class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('email') }}">
                    <p class="text-xs text-gray-500 mt-1">Este correo se usa para autenticarte.</p>
                </div>

            </section>

            <!-- Botón de acciones secundarias -->
            <div x-cloak x-data="{ open: false }" class="absolute top-4 right-8">
                <button @click="open = !open"
                    class="h-10 w-10 p-2 rounded-full bg-gray-200 hover:bg-gray-300 focus:outline-none shadow">
                    <!-- Icono de tres puntos -->
                    <i class="bi bi-three-dots-vertical"></i>
                </button>

                <!-- Menú desplegable -->
                <div x-show="open" @click.away="open = false"
                    class="mt-2 w-60 bg-white shadow-lg rounded-lg border border-gray-200 py-2 absolute right-0">
                    <form action="{{ route('auth.logout') }}" method="POST">
                        @csrf @method('POST')
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-gray-700">
                            Cerrar sesión
                        </button>
                    </form>

                    <div>
                        @livewire('account-update-password')
                    </div>

                    <div>
                        @livewire('delete-account')
                    </div>

                </div>
            </div>

        </div>
    </x-main>
@endsection
