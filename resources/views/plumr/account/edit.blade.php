@extends('plumr.layout.app')

@section('main')
    <x-main class="m-0 h-screen flex items-center justify-center">
        <div class="w-full h-auto max-w-5xl border-2 p-14 shadow-sm rounded-lg bg-white">

            <section class="mb-4">
                <a
                    class="bg-gray-700 py-2 px-4 rounded-sm text-white text-sm"
                    role="button"
                    href="{{ route('main_account', ['user' => $user]) }}">
                    Volver
                </a>
            </section>

            <form action="{{  route('account.update', ['user' => $user]) }}" method="POST">
                @csrf @method('POST')

                 <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="username">Nombre de usuario</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" id="username"
                        placeholder="Ingresa tu ciudad" autocomplete="off" autofocus
                        class="rounded-md p-2 bg-blue-50 {{ e_class('username') }} shadow-sm"
                        />
                        <p class="text-xs text-gray-400">Ingrese un nuevo nombre de usuario disponible</p>
                        <p class="text-xs text-gray-400">NOTA: Solo se puede modificar una vez</p>
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="email">Correo electrónico</label>
                    <input type="text" name="email" value="{{ old('email', $user->email) }}" id="email"
                        placeholder="Ingresa tu ciudad" autocomplete="off" autofocus
                        class="rounded-md p-2 bg-blue-50 {{ e_class('email') }} shadow-sm"
                        />
                        <p class="text-xs text-gray-400">Ingrese su correo electrónico.</p>
                        <p class="text-xs text-gray-400">NOTA: Este correo electrónico se usa para autenticarte</p>
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="password">Contraseña</label>
                    <input type="text" name="password" value="{{ old('password') }}" id="password"
                        placeholder="Ingresa tu ciudad" autocomplete="off" autofocus
                        class="rounded-md p-2 bg-blue-50 {{ e_class('password') }} shadow-sm"
                        />
                        <p class="text-xs text-gray-400">Ingrese un nuevo contraseña solo en caso de cambiar</p>
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="password_confirmation">Confirmar contraseña</label>
                    <input type="text" name="password_confirmation" value="{{ old('password_confirmation') }}" id="password_confirmation"
                        placeholder="Ingresa tu ciudad" autocomplete="off" autofocus
                        class="rounded-md p-2 bg-blue-50 {{ e_class('password_confirmation') }} shadow-sm"
                        />
                        <p class="text-xs text-gray-400">Ingrese un nuevo contraseña solo en caso de cambiar</p>
                </section>

                <div class="text-center py-4">
                    <hr>
                </div>

                <button class="bg-green-500 hover:bg-green-600 py-2 px-4 rounded-sm text-white text-sm">Guardar</button>
            </form>

            <button class="bg-red-500 hover:bg-red-600 py-2 px-4 mt-2 rounded-sm text-white text-sm">Eliminar cuenta</button>

            <form action="{{ route('auth.logout') }}" method="POST">
            @csrf @method('POST')
                <button class="bg-blue-500 hover:bg-blue-600 py-2 px-4 mt-2 rounded-sm text-white text-sm">Cerrar sesión</button>
            </form>
        </div>
    </x-main>
@endsection
