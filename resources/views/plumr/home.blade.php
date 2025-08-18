@extends('plumr.layout.app')

@section('main')
<x-main>
    <!-- Hero principal -->
    <section class="bg-gradient-to-r from-indigo-50 via-white to-indigo-50 shadow-lg rounded-2xl p-8 text-center relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-pink-200 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-pulse"></div>

        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight">
            Bienvenido a <span class="text-indigo-600">Plumr</span> 🚀
        </h1>
        <p class="text-gray-600 mt-4 text-lg max-w-2xl mx-auto">
            Tu espacio para <span class="font-semibold text-indigo-500">compartir</span> y
            <span class="font-semibold text-pink-500">descubrir</span> artículos interesantes ✍️
        </p>

        <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('register') }}"
               class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl shadow hover:bg-indigo-700 transition">
                Crear cuenta gratuita
            </a>
            <a href="{{ route('login') }}"
               class="px-6 py-3 bg-white border border-indigo-600 text-indigo-600 font-medium rounded-xl shadow hover:bg-indigo-50 transition">
                Iniciar sesión
            </a>
        </div>
    </section>

    <!-- Sección de funcionalidades -->
    <section class="mt-12 grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Publicar -->
        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
            <div class="w-12 h-12 flex items-center justify-center bg-indigo-100 text-indigo-600 rounded-xl mb-4 text-2xl">
                ✍️
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Publica tus ideas</h3>
            <p class="text-gray-600 mt-2">
                Comparte pensamientos rápidos, reflexiones personales o momentos del día en tu muro.
            </p>
        </div>

        <!-- Artículos -->
        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
            <div class="w-12 h-12 flex items-center justify-center bg-pink-100 text-pink-600 rounded-xl mb-4 text-2xl">
                📚
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Escribe artículos</h3>
            <p class="text-gray-600 mt-2">
                Crea contenido más elaborado y profundo, comparte tu conocimiento y ayuda a otros.
            </p>
        </div>

        <!-- Amigos -->
        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
            <div class="w-12 h-12 flex items-center justify-center bg-green-100 text-green-600 rounded-xl mb-4 text-2xl">
                🤝
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Encuentra amigos</h3>
            <p class="text-gray-600 mt-2">
                Conecta con personas que comparten tus intereses e ideas, y amplía tu red social.
            </p>
        </div>

        <!-- Debates -->
        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
            <div class="w-12 h-12 flex items-center justify-center bg-yellow-100 text-yellow-600 rounded-xl mb-4 text-2xl">
                💬
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Únete a discusiones</h3>
            <p class="text-gray-600 mt-2">
                Participa en conversaciones abiertas, comparte tu perspectiva y aprende de otros.
            </p>
        </div>

        <!-- Nuevos enfoques -->
        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
            <div class="w-12 h-12 flex items-center justify-center bg-purple-100 text-purple-600 rounded-xl mb-4 text-2xl">
                🔎
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Explora distintos enfoques</h3>
            <p class="text-gray-600 mt-2">
                Descubre opiniones diversas y amplía tu forma de pensar con nuevas perspectivas.
            </p>
        </div>
    </section>
</x-main>
@endsection
