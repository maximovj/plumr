@extends('plumr.layout.app')

@section('main')
    <x-main class="m-0 h-screen flex flex-col items-center justify-center">
        <div class="w-full h-auto max-w-xl border-2 p-14 shadow-sm rounded-lg bg-white">


            <form action="{{ route('auth.login') }}" method="POST">
                @csrf
                @method('POST')
                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="email">Correo electrónico</label>
                    <input type="text" name="email" value="{{ old('email') }}" id="email"
                        placeholder="Ingresa tu correo electrónico" autocomplete="off"
                        class="rounded-md p-2 {{ e_class('email') }} bg-blue-50 shadow-sm" />
                    @error('email')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="password">Contraseña</label>
                    <input type="password" name="password" value="{{ old('password') }}" id="password"
                        placeholder="Ingresa tu contraseña" autocomplete="off"
                        class="rounded-md p-2 {{ e_class('password') }} bg-blue-50 shadow-sm" />
                    @error('password')
                    <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </section>

                <div class="text-center py-4">
                    <hr>
                </div>


                <div class="mb-5">
                    <input type="checkbox" name="remember" /> <label>Recuerdame</label>
                </div>


                @if(session('error_auth'))
                <div class="bg-red-500 text-white py-2 px-4 rounded-sm mb-4">
                    <p>{{ session('error_auth') }}</p>
                </div>
                @endif

                <button class="bg-blue-500 hover:bg-blue-600 py-2 px-4 rounded-sm text-white text-sm">Iniciar
                    sesión</button>
            </form>


        </div>
    </x-main>
@endsection
