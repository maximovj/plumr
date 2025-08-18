@extends('plumr.layout.app')

@section('main')
<x-main class="m-0 h-screen flex items-center justify-center bg-gray-50">

    <div class="w-full max-w-2xl p-10 bg-white rounded-2xl shadow-lg space-y-6">

        <!-- Botón Volver -->
        <div>
            <a href="{{ route('main_account', ['user' => $user]) }}"
               class="inline-block bg-gray-700 hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition">
                ← Volver
            </a>
        </div>

        <!-- Formulario de perfil -->
        <form action="{{ route('profile.update', ['user' => $user]) }}" method="POST" class="space-y-5">
            @csrf @method('POST')

            <!-- Nombre completo -->
            <div class="flex flex-col">
                <label for="fullname" class="text-gray-700 font-medium">Nombre completo</label>
                <input type="text" name="fullname" id="fullname"
                       value="{{ old('fullname', $profile->fullname) }}"
                       placeholder="Ingresa tu nombre completo"
                       class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('fullname') }}">
                @error('fullname')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Fecha de cumpleaños -->
            <div class="flex flex-col">
                <label class="text-gray-700 font-medium">Fecha de cumpleaños (D/M/YYYY)</label>
                <div class="grid grid-cols-3 gap-4 mt-1">
                    <input type="number" name="birthday_day" value="{{ old('birthday_day', $profile->birthday->format('d')) }}"
                           placeholder="Día" min="1" max="31"
                           class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('birthday_day') }}">
                    <input type="number" name="birthday_month" value="{{ old('birthday_month', $profile->birthday->format('m')) }}"
                           placeholder="Mes" min="1" max="12"
                           class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('birthday_month') }}">
                    <input type="number" name="birthday_year" value="{{ old('birthday_year', $profile->birthday->format('Y')) }}"
                           min="{{ date('Y') - 100 }}" max="{{ date('Y') }}"
                           placeholder="Año"
                           class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('birthday_year') }}">
                </div>
                @error('birthday_day')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                @error('birthday_month')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                @error('birthday_year')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Sexo -->
            <div class="flex flex-col">
                <label for="sex" class="text-gray-700 font-medium">Sexo</label>
                <input type="text" name="sex" id="sex" list="select-sex"
                       value="{{ old('sex', $profile->sex) }}"
                       placeholder="Ingresa tu sexo"
                       class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('sex') }}">
                <datalist id="select-sex">
                    <option value="Hombre"></option>
                    <option value="Mujer"></option>
                </datalist>
                @error('sex')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Número de teléfono -->
            <div class="flex flex-col">
                <label for="number_phone" class="text-gray-700 font-medium">Número de teléfono</label>
                <input type="text" name="number_phone" id="number_phone"
                       value="{{ old('number_phone', $profile->number_phone) }}"
                       placeholder="Ingresa tu número de teléfono"
                       class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('number_phone') }}">
                <p class="text-xs text-gray-500 mt-1">Solo se mostrará al público si lo deseas.</p>
            </div>

            <!-- País -->
            <div class="flex flex-col">
                <label for="country" class="text-gray-700 font-medium">País</label>
                <input type="text" name="country" id="country"
                       value="{{ old('country', $profile->country) }}"
                       placeholder="Ingresa tu país"
                       class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('country') }}">
                <p class="text-xs text-gray-500 mt-1">Solo se mostrará al público si lo deseas.</p>
            </div>

            <!-- Ciudad -->
            <div class="flex flex-col">
                <label for="city" class="text-gray-700 font-medium">Ciudad</label>
                <input type="text" name="city" id="city"
                       value="{{ old('city', $profile->city) }}"
                       placeholder="Ingresa tu ciudad"
                       class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('city') }}">
                <p class="text-xs text-gray-500 mt-1">Solo se mostrará al público si lo deseas.</p>
            </div>

            <!-- Dirección -->
            <div class="flex flex-col">
                <label for="address" class="text-gray-700 font-medium">Dirección</label>
                <input type="text" name="address" id="address"
                       value="{{ old('address', $profile->address) }}"
                       placeholder="Ingresa tu dirección"
                       class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('address') }}">
                <p class="text-xs text-gray-500 mt-1">Solo se mostrará al público si lo deseas.</p>
            </div>

            <!-- Bio -->
            <div class="flex flex-col">
                <label for="bio" class="text-gray-700 font-medium">Bio</label>
                <input type="text" name="bio" id="bio"
                       value="{{ old('bio', $profile->bio) }}"
                       placeholder="Cuéntale al mundo a lo que te dedicas"
                       class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('bio') }}">
                <p class="text-xs text-gray-500 mt-1">Solo se mostrará al público si lo deseas.</p>
            </div>

            <div class="text-center py-4">
                <hr>
            </div>

            <!-- Botón Guardar -->
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-lg shadow transition">
                Guardar cambios
            </button>
        </form>
    </div>

</x-main>
@endsection
