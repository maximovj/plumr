@extends('plumr.layout.app')

@section('main')
    <x-main class="m-0 h-screen flex items-center justify-center">
        <div class="w-full h-auto max-w-prose border-2 p-14 shadow-sm rounded-lg bg-white">
            <form action="{{  route('register.attempt') }}" method="POST">
                @csrf @method('POST')
                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="name">Nombre completo</label>
                    <input type="text" name="name" value="{{ old('name') }}" id="name"
                        placeholder="Ingresa tu nombre completo" autocomplete="off" autofocus
                        class="rounded-md p-2 bg-blue-50 {{ e_class('name') }} shadow-sm"
                        />
                    @error('name')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="birthday">Fecha de cumpleaños (D/M/YYYY)</label>
                    <div class="grid grid-cols-3 gap-4">
                        <input type="number" placeholder="Día" name="birthday_day" value="{{ old('birthday_day', 1) }}"
                            min="1" max="32" id="birthday" autocomplete="off"
                            class="rounded-md p-2 bg-blue-50 {{ e_class('birthday_day') }} shadow-sm" />
                        <input type="number" placeholder="Mes" name="birthday_month" value="{{ old('birthday_month', 1) }}"
                            min="1" max="12" id="birthday" autocomplete="off"
                            class="rounded-md p-2 bg-blue-50 {{ e_class('birthday_month') }} shadow-sm" />
                        <input type="number" placeholder="Año" name="birthday_year"
                            value="{{ old('birthday_year', date('Y') - 18) }}" min="{{ date('Y') - 100 }}"
                            max="{{ date('Y') }}" id="birthday" autocomplete="off"
                            class="rounded-md p-2 bg-blue-50 {{ e_class('birthday_year') }} shadow-sm" />
                    </div>
                    @error('birthday_day')
                    <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                    @error('birthday_month')
                    <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                    @error('birthday_year')
                    <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="sex">Sexo</label>
                    <input type="text" name="sex" value="{{ old('sex') }}" id="sex" list="select-sex"
                        placeholder="Ingresa tu sexo" autocomplete="off"
                        class="rounded-md p-2 bg-blue-50 {{ e_class('sex') }} shadow-sm" />
                    <datalist id="select-sex">
                        <option value="Hombre"></option>
                        <option value="Mujer"></option>
                    </datalist>
                    @error('sex')
                    <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="username">Usuario</label>
                    <input type="text" name="username" value="{{ old('username') }}" id="username"
                        placeholder="Ingresa tu nombre de usuario" autocomplete="off"
                        class="rounded-md p-2 bg-blue-50 {{ e_class('username') }} shadow-sm" />
                    @error('username')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                        @endif
                    @enderror
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="email">Correo electrónico</label>
                    <input type="text" name="email" value="{{ old('email') }}" id="email"
                        placeholder="Ingresa tu correo electrónico" autocomplete="off"
                        class="rounded-md p-2 bg-blue-50 {{ e_class('email') }} shadow-sm" />
                    @error('email')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="password">Contraseña</label>
                    <input type="password" name="password" value="{{ old('password') }}" id="password"
                        placeholder="Ingresa una contraseña" autocomplete="off"
                        class="rounded-md p-2 bg-blue-50 {{ e_class('password') }} shadow-sm" />
                    @error('password')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </section>

                <section class="flex flex-col gap-2">
                    <label class="text-base text-gray-700" for="password_confirmation">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}"
                        id="password_confirmation" placeholder="Confirma su contraseña" autocomplete="off"
                        class="rounded-md p-2 bg-blue-50 {{ e_class('password_confirmation') }} shadow-sm" />
                    @error('password_confirmation')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </section>

                <div class="text-center py-4">
                    <hr>
                </div>

                <button class="bg-green-500 hover:bg-green-600 py-2 px-4 rounded-sm text-white text-sm">Registrarme</button>
            </form>
        </div>
    </x-main>
@endsection
