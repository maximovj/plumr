@extends('plumr.layout.app')

@section('main')
    <x-main class="m-0 h-screen flex items-center justify-center">
        <div class="w-full h-auto max-w-xl border-2 p-14 shadow-sm rounded-lg bg-white">
            <form action="{{ route('auth.login.attempt') }}" method="POST">
                @csrf
                @method('POST')
                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="email">Correo electrónico</label>
                    <input type="text" name="email" old="{{ old('email') }}" id="email"
                        placeholder="Ingresa tu correo electrónico" autocomplete="off"
                        class="rounded-md p-2 border-1 border-gray-600 bg-blue-50 shadow-sm" />
                    <p class="text-xs text-red-400 hidden">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Accusamus ipsum sit laboriosam sint? In aliquid voluptates incidunt ad eveniet eum provident
                        maiores eaque, reprehenderit beatae totam est neque, culpa nihil.</p>
                </section>

                <section class="flex flex-col gap-2 mb-4">
                    <label class="text-base text-gray-700" for="password">Contraseña</label>
                    <input type="text" name="password" value="{{ old('password') }}" id="password"
                        placeholder="Ingresa tu contraseña" autocomplete="off"
                        class="rounded-md p-2 border-1 border-gray-600 bg-blue-50 shadow-sm" />
                    <p class="text-xs text-red-400 hidden">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Accusamus ipsum sit laboriosam sint? In aliquid voluptates incidunt ad eveniet eum provident
                        maiores eaque, reprehenderit beatae totam est neque, culpa nihil.</p>
                </section>

                <div class="text-center py-4">
                    <hr>
                </div>

                <button class="bg-blue-500 hover:bg-blue-600 py-2 px-4 rounded-sm text-white text-sm">Iniciar
                    sesión</button>
            </form>
        </div>
    </x-main>
@endsection
