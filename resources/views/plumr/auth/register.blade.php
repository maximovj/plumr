@extends('plumr.layout.app')

@section('main')
    <x-main class="m-0 h-screen flex items-center justify-center">
        <div class="w-full h-auto max-w-prose border-2 p-14 shadow-sm rounded-lg bg-white">
            <form action="{{  route('auth.register.attempt') }}" method="POST">
                @csrf @method('POST')
                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="username">Nombre completo</label>
                    <input type="text" name="username" value="{{ old('username') }}" id="username"
                        placeholder="Ingresa tu nombre completo" autocomplete="off" autofocus
                        class="rounded-md p-2 bg-blue-50 border-1 border-gray-600 shadow-sm" />
                    <p class="text-xs text-red-400 hidden">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Accusamus ipsum sit laboriosam sint? In aliquid voluptates incidunt ad eveniet eum provident maiores
                        eaque, reprehenderit beatae totam est neque, culpa nihil.</p>
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="birthday">Fecha de cumpleaños (D/M/YYYY)</label>
                    <div class="grid grid-cols-3 gap-4">
                        <input type="number" placeholder="Día" name="birthday_day" value="{{ old('birthday_day', 1) }}"
                            min="1" max="32" id="birthday" autocomplete="off"
                            class="rounded-md p-2 bg-blue-50 border-1 border-gray-600 shadow-sm" />
                        <input type="number" placeholder="Mes" name="birthday_month" value="{{ old('birthday_month', 1) }}"
                            min="1" max="12" id="birthday" autocomplete="off"
                            class="rounded-md p-2 bg-blue-50 border-1 border-gray-600 shadow-sm" />
                        <input type="number" placeholder="Año" name="birthday_year"
                            value="{{ old('birthday_year', date('Y') - 18) }}" min="{{ date('Y') - 100 }}"
                            max="{{ date('Y') }}" id="birthday" autocomplete="off"
                            class="rounded-md p-2 bg-blue-50 border-1 border-gray-600 shadow-sm" />
                    </div>
                    <p class="text-xs text-red-400 hidden">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Accusamus ipsum sit laboriosam sint? In aliquid voluptates incidunt ad eveniet eum provident maiores
                        eaque, reprehenderit beatae totam est neque, culpa nihil.</p>
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="sex">Sexo</label>
                    <input type="text" name="sex" value="{{ old('sex') }}" id="sex" list="select-sex"
                        placeholder="Ingresa tu sexo" autocomplete="off"
                        class="rounded-md p-2 bg-blue-50 border-1 border-gray-600 shadow-sm" />
                    <datalist id="select-sex">
                        <option value="Hombre"></option>
                        <option value="Mujer"></option>
                    </datalist>
                    <p class="text-xs text-red-400 hidden">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Accusamus ipsum sit laboriosam sint? In aliquid voluptates incidunt ad eveniet eum provident maiores
                        eaque, reprehenderit beatae totam est neque, culpa nihil.</p>
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="username">Usuario</label>
                    <input type="text" name="username" value="{{ old('username') }}" id="username"
                        placeholder="Ingresa tu nombre de usuario" autocomplete="off"
                        class="rounded-md p-2 bg-blue-50 border-1 border-gray-600 shadow-sm" />
                    <p class="text-xs text-red-400 hidden">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Accusamus ipsum sit laboriosam sint? In aliquid voluptates incidunt ad eveniet eum provident maiores
                        eaque, reprehenderit beatae totam est neque, culpa nihil.</p>
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="email">Correo electrónico</label>
                    <input type="text" name="email" old="{{ old('email') }}" id="email"
                        placeholder="Ingresa tu correo electrónico" autocomplete="off"
                        class="rounded-md p-2 bg-blue-50 border-1 border-gray-600 shadow-sm" />
                    <p class="text-xs text-red-400 hidden">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Accusamus ipsum sit laboriosam sint? In aliquid voluptates incidunt ad eveniet eum provident maiores
                        eaque, reprehenderit beatae totam est neque, culpa nihil.</p>
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="password">Contraseña</label>
                    <input type="text" name="password" value="{{ old('password') }}" id="password"
                        placeholder="Ingresa una contraseña" autocomplete="off"
                        class="rounded-md p-2 bg-blue-50 border-1 border-gray-600 shadow-sm" />
                    <p class="text-xs text-red-400 hidden">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Accusamus ipsum sit laboriosam sint? In aliquid voluptates incidunt ad eveniet eum provident maiores
                        eaque, reprehenderit beatae totam est neque, culpa nihil.</p>
                </section>

                <section class="flex flex-col gap-2">
                    <label class="text-base text-gray-700" for="confirm_password">Confirmar contraseña</label>
                    <input type="text" name="confirm_password" value="{{ old('confirm_password') }}"
                        id="confirm_password" placeholder="Confirma su contraseña" autocomplete="off"
                        class="rounded-md p-2 bg-blue-50 border-1 border-gray-600 shadow-sm" />
                    <p class="text-xs text-red-400 hidden">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Accusamus ipsum sit laboriosam sint? In aliquid voluptates incidunt ad eveniet eum provident maiores
                        eaque, reprehenderit beatae totam est neque, culpa nihil.</p>
                </section>

                <div class="text-center py-4">
                    <hr>
                </div>

                <button class="bg-green-500 hover:bg-green-600 py-2 px-4 rounded-sm text-white text-sm">Registrarme</button>
            </form>
        </div>
    </x-main>
@endsection
