@extends('plumr.layout.app')

@section('main')
    <x-main class="m-0 h-screen flex items-center justify-center">
        <div class="w-full h-auto max-w-5xl border-2 p-14 shadow-sm rounded-lg bg-white">
            <form action="{{  route('profile.update', ['user' => $user]) }}" method="POST">
                @csrf @method('POST')

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="fullname">Nombre completo</label>
                    <input type="text" name="fullname" value="{{ old('fullname', $profile->fullname) }}" id="fullname"
                        placeholder="Ingresa tu nombre completo" autocomplete="off" autofocus
                        class="rounded-md p-2 bg-blue-50 {{ e_class('fullname') }} shadow-sm"
                        />
                    @error('fullname')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="birthday">Fecha de cumpleaños (D/M/YYYY)</label>
                    <div class="grid grid-cols-3 gap-4">
                        <input type="number" placeholder="Día" name="birthday_day" value="{{ old('birthday_day', $profile->birthday->format('d')) }}"
                            min="1" max="32" id="birthday" autocomplete="off"
                            class="rounded-md p-2 bg-blue-50 {{ e_class('birthday_day') }} shadow-sm" />
                        <input type="number" placeholder="Mes" name="birthday_month" value="{{ old('birthday_month', $profile->birthday->format('m')) }}"
                            min="1" max="12" id="birthday" autocomplete="off"
                            class="rounded-md p-2 bg-blue-50 {{ e_class('birthday_month') }} shadow-sm" />
                        <input type="number" placeholder="Año" name="birthday_year"
                            value="{{ old('birthday_year', $profile->birthday->format('Y')) }}" min="{{ date('Y') - 100 }}"
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
                    <input type="text" name="sex" value="{{ old('sex', $profile->sex) }}" id="sex" list="select-sex"
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
                    <label class="text-base text-gray-700" for="number_phone">Número de teléfono</label>
                    <input type="text" name="number_phone" value="{{ old('number_phone', $profile->number_phone) }}" id="number_phone"
                        placeholder="Ingresa tu número de teléfono" autocomplete="off" autofocus
                        class="rounded-md p-2 bg-blue-50 {{ e_class('number_phone') }} shadow-sm"
                        />
                        <p class="text-xs text-gray-400">Ingrese su número de teléfono solo en caso de querer mostrarlo al publico en general</p>
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="country">País</label>
                    <input type="text" name="country" value="{{ old('country', $profile->country) }}" id="country"
                        placeholder="Ingresa tu ciudad" autocomplete="off" autofocus
                        class="rounded-md p-2 bg-blue-50 {{ e_class('country') }} shadow-sm"
                        />
                        <p class="text-xs text-gray-400">Ingrese su ciudad solo en caso de querer mostrarlo al publico en general</p>
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="city">Ciudad</label>
                    <input type="text" name="city" value="{{ old('city', $profile->city) }}" id="city"
                        placeholder="Ingresa tu ciudad" autocomplete="off" autofocus
                        class="rounded-md p-2 bg-blue-50 {{ e_class('city') }} shadow-sm"
                        />
                        <p class="text-xs text-gray-400">Ingrese su ciudad solo en caso de querer mostrarlo al publico en general</p>
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="address">Dirección</label>
                    <input type="text" name="address" value="{{ old('address', $profile->address) }}" id="address"
                        placeholder="Ingresa tu dirección" autocomplete="off" autofocus
                        class="rounded-md p-2 bg-blue-50 {{ e_class('address') }} shadow-sm"
                        />
                        <p class="text-xs text-gray-400">Ingrese su dirección solo en caso de querer mostrarlo al publico en general</p>
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="bio">Bio (Cuéntale al mundo a lo que te dedicas)</label>
                    <input type="text" name="bio" value="{{ old('bio', $profile->bio) }}" id="bio"
                        placeholder="Ingresa tu bio" autocomplete="off" autofocus
                        class="rounded-md p-2 bg-blue-50 {{ e_class('bio') }} shadow-sm"
                        />
                        <p class="text-xs text-gray-400">Ingrese su bio solo en caso de querer mostrarlo al publico en general</p>
                </section>

                <div class="text-center py-4">
                    <hr>
                </div>

                <button class="bg-green-500 hover:bg-green-600 py-2 px-4 rounded-sm text-white text-sm">Guardar</button>
            </form>
        </div>
    </x-main>
@endsection
