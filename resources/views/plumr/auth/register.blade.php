@extends('plumr.layout.app')

@section('main')
<x-main class="m-0 h-screen flex items-center justify-center bg-gray-50">

    <div class="w-full max-w-md p-10 bg-white rounded-2xl shadow-lg">
        <h1 class="text-2xl font-bold text-gray-900 text-center mb-6">Crea tu cuenta en Plumr</h1>

        <form action="{{ route('register.attempt') }}" method="POST" class="space-y-5">
            @csrf @method('POST')

            <!-- Nombre completo -->
            <div class="flex flex-col">
                <label for="name" class="text-gray-700 mb-1">Nombre completo</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    placeholder="Ingresa tu nombre completo" autocomplete="off" autofocus
                    class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('name') }}">
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fecha de cumpleaños -->
            <div class="flex flex-col">
                <label class="text-gray-700 mb-1">Fecha de cumpleaños (D/M/YYYY)</label>
                <div class="grid grid-cols-3 gap-3">
                    <input type="number" name="birthday_day" value="{{ old('birthday_day', 1) }}" min="1" max="31"
                        placeholder="Día" autocomplete="off"
                        class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('birthday_day') }}">
                    <input type="number" name="birthday_month" value="{{ old('birthday_month', 1) }}" min="1" max="12"
                        placeholder="Mes" autocomplete="off"
                        class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('birthday_month') }}">
                    <input type="number" name="birthday_year" value="{{ old('birthday_year', date('Y')-18) }}"
                        min="{{ date('Y')-100 }}" max="{{ date('Y') }}" placeholder="Año" autocomplete="off"
                        class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('birthday_year') }}">
                </div>
                @error('birthday_day') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                @error('birthday_month') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                @error('birthday_year') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Sexo -->
            <div class="flex flex-col">
                <label for="sex" class="text-gray-700 mb-1">Sexo</label>
                <input type="text" name="sex" id="sex" list="select-sex" value="{{ old('sex') }}"
                    placeholder="Ingresa tu sexo" autocomplete="off"
                    class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('sex') }}">
                <datalist id="select-sex">
                    <option value="Hombre"></option>
                    <option value="Mujer"></option>
                </datalist>
                @error('sex')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Usuario -->
            <div class="flex flex-col">
                <label for="username" class="text-gray-700 mb-1">Usuario</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}"
                    placeholder="Ingresa tu nombre de usuario" autocomplete="off"
                    class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('username') }}">
                @error('username')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @if(session('username_suggestions'))
                        <div class="text-xs text-gray-700 mt-2">
                            <p class="font-semibold">Nombres disponibles:</p>
                            <ul class="list-disc ml-4">
                                @foreach(session('username_suggestions') as $suggestion)
                                    <li>{{ $suggestion }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @enderror
            </div>

            <!-- Correo electrónico -->
            <div class="flex flex-col">
                <label for="email" class="text-gray-700 mb-1">Correo electrónico</label>
                <input type="text" name="email" id="email" value="{{ old('email') }}"
                    placeholder="Ingresa tu correo electrónico" autocomplete="off"
                    class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('email') }}">
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contraseña -->
            <div class="flex flex-col">
                <label for="password" class="text-gray-700 mb-1">Contraseña</label>
                <input type="password" name="password" id="password"
                    placeholder="Ingresa una contraseña" autocomplete="off"
                    class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('password') }}">
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmar contraseña -->
            <div class="flex flex-col">
                <label for="password_confirmation" class="text-gray-700 mb-1">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    placeholder="Confirma tu contraseña" autocomplete="off"
                    class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('password_confirmation') }}">
                @error('password_confirmation')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-lg shadow transition">
                Registrarme
            </button>
        </form>

        <p class="mt-6 text-center text-gray-600 text-sm">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-green-600 font-semibold hover:underline">Inicia sesión aquí</a>
        </p>
    </div>

</x-main>
@endsection
