@extends('plumr.layout.app')

@section('main')
<x-main class="m-0 h-screen flex items-center justify-center bg-gray-50">

    <div class="w-full max-w-md p-10 bg-white rounded-2xl shadow-lg">
        <h1 class="text-2xl font-bold text-gray-900 text-center mb-6">Inicia sesión en Plumr</h1>

        @if(session('error_auth'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error_auth') }}
            </div>
        @endif

        <form action="{{ route('auth.login') }}" method="POST" class="space-y-5">
            @csrf
            @method('POST')

            <div class="flex flex-col">
                <label for="email" class="text-gray-700 mb-1">Correo electrónico</label>
                <input type="text" name="email" id="email" value="{{ old('email') }}"
                    placeholder="Ingresa tu correo electrónico"
                    autocomplete="off"
                    class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400 shadow-sm {{ e_class('email') }}">
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col">
                <label for="password" class="text-gray-700 mb-1">Contraseña</label>
                <input type="password" name="password" id="password" value="{{ old('password') }}"
                    placeholder="Ingresa tu contraseña"
                    autocomplete="off"
                    class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400 shadow-sm {{ e_class('password') }}">
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-sm text-gray-600">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" class="rounded border-gray-300">
                    Recuerdame
                </label>
                <a href="#" class="hover:underline text-indigo-600">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg shadow transition">
                Iniciar sesión
            </button>
        </form>

        <p class="mt-6 text-center text-gray-600 text-sm">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:underline">Regístrate aquí</a>
        </p>
    </div>

</x-main>
@endsection
