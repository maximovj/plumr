@extends('plumr.layout.app')

@section('main')
<x-main>
    <!-- Hero principal -->
    <section class="bg-gradient-to-r from-indigo-50 via-white to-indigo-50 shadow-lg rounded-2xl p-8 text-center relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse">
        </div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-pink-200 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-pulse">
        </div>

        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight">
            Bienvenido a <span class="text-indigo-600">Plumr</span> 🚀
        </h1>
        <p class="text-gray-600 mt-4 text-lg max-w-2xl mx-auto">
            Tu espacio para <span class="font-semibold text-indigo-500">compartir</span> y
            <span class="font-semibold text-pink-500">descubrir</span> artículos interesantes ✍️
        </p>

        <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('register') }}" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl shadow hover:bg-indigo-700 transition">
                Crear cuenta gratuita
            </a>
            <a href="{{ route('login') }}" class="px-6 py-3 bg-white border border-indigo-600 text-indigo-600 font-medium rounded-xl shadow hover:bg-indigo-50 transition">
                Iniciar sesión
            </a>
        </div>
    </section>

    <!-- Sección de funcionalidades -->
    <section class="mt-16 bg-gray-50 py-12 rounded-2xl">
        <div class="max-w-5xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-900">Plumr es más que una red social</h2>
            <p class="text-gray-600 mt-3">Descubre todo lo que puedes hacer:</p>
        </div>

        <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="p-6 bg-white rounded-xl shadow hover:shadow-md transition">
                <div class="text-4xl mb-3">✍️</div>
                <h3 class="font-semibold text-gray-800">Publica ideas</h3>
                <p class="text-gray-600 mt-1">
                    Comparte tus pensamientos con el mundo, reflexiones personales o momentos del día en tu muro.
                </p>
            </div>
            <div class="p-6 bg-white rounded-xl shadow hover:shadow-md transition">
                <div class="text-4xl mb-3">📚</div>
                <h3 class="font-semibold text-gray-800">Escribe artículos</h3>
                <p class="text-gray-600 mt-1">Crea contenido más elaborado y profundo, comparte tu conocimiento y ayuda
                    a otros.</p>
            </div>
            <div class="p-6 bg-white rounded-xl shadow hover:shadow-md transition">
                <div class="text-4xl mb-3">🤝</div>
                <h3 class="font-semibold text-gray-800">Encuentra amigos</h3>
                <p class="text-gray-600 mt-1">Conecta con personas que comparten tus intereses e ideas, y amplía tu red
                    social.</p>
            </div>
            <div class="p-6 bg-white rounded-xl shadow hover:shadow-md transition">
                <div class="text-4xl mb-3">🤝</div>
                <h3 class="font-semibold text-gray-800">Únete a discusiones</h3>
                <p class="text-gray-600 mt-1">Participa en conversaciones abiertas, comparte tu perspectiva y aprende de
                    otros.</p>
            </div>
            <div class="p-6 bg-white rounded-xl shadow hover:shadow-md transition">
                <div class="text-4xl mb-3">🤝</div>
                <h3 class="font-semibold text-gray-800">Explora distintos enfoques</h3>
                <p class="text-gray-600 mt-1">Descubre opiniones diversas y amplía tu forma de pensar con nuevas
                    perspectivas.</p>
            </div>
        </div>
    </section>

    <section class="mt-16 max-w-5xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-gray-900">¿Cómo funciona?</h2>
        <div class="mt-10 grid md:grid-cols-3 gap-8">
            <div class="p-6 bg-white rounded-xl shadow">
                <div class="text-4xl mb-3">📝</div>
                <h3 class="font-semibold text-gray-800">1. Regístrate</h3>
                <p class="text-gray-600">Crea tu cuenta gratuita en pocos segundos.</p>
            </div>
            <div class="p-6 bg-white rounded-xl shadow">
                <div class="text-4xl mb-3">💡</div>
                <h3 class="font-semibold text-gray-800">2. Comparte</h3>
                <p class="text-gray-600">Publica tus ideas, artículos o debates.</p>
            </div>
            <div class="p-6 bg-white rounded-xl shadow">
                <div class="text-4xl mb-3">🌎</div>
                <h3 class="font-semibold text-gray-800">3. Conecta</h3>
                <p class="text-gray-600">Encuentra personas y comunidades afines.</p>
            </div>
        </div>
    </section>

    <section class="mt-16 max-w-5xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-gray-900">Lo que dicen nuestros usuarios</h2>
        <div class="mt-8 grid md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-gray-600">“Plumr me permitió conectar con personas que piensan como yo. ¡Una gran
                    experiencia!”</p>
                <div class="mt-4 font-semibold text-indigo-600">María P.</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-gray-600">“La mejor plataforma para escribir y compartir artículos. Muy intuitiva.”</p>
                <div class="mt-4 font-semibold text-indigo-600">Carlos R.</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-gray-600">“Me encanta leer diferentes perspectivas, ¡aprendo algo nuevo cada día!”</p>
                <div class="mt-4 font-semibold text-indigo-600">Laura G.</div>
            </div>
        </div>
    </section>

    <section class="mt-16 bg-gray-100 py-12">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-900">Próximamente en Plumr</h2>
            <p class="text-gray-600 mt-3">Estamos trabajando en nuevas funcionalidades:</p>
            <ul class="mt-6 space-y-3 text-gray-700">
                <li>✅ Chats privados en tiempo real</li>
                <li>✅ Sistema de grupos temáticos</li>
                <li>✅ Gamificación con insignias y logros</li>
                <li>✅ Recomendaciones personalizadas</li>
            </ul>
        </div>
    </section>

    <section class="mt-16 bg-indigo-50 py-12">
        <div class="max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <h3 class="text-3xl font-bold text-indigo-600">+10k</h3>
                <p class="text-gray-600">Usuarios activos</p>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-indigo-600">+25k</h3>
                <p class="text-gray-600">Artículos publicados</p>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-indigo-600">+1M</h3>
                <p class="text-gray-600">Comentarios</p>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-indigo-600">+120</h3>
                <p class="text-gray-600">Comunidades activas</p>
            </div>
        </div>
    </section>

    <section class="mt-16 py-12 bg-indigo-600 text-center text-white rounded-2xl">
        <h2 class="text-3xl font-bold">¿Listo para unirte a Plumr?</h2>
        <p class="mt-2 text-indigo-200">Empieza hoy mismo y comparte tu voz con el mundo.</p>
        <div class="mt-6">
            <a href="{{ route('register') }}" class="px-6 py-3 bg-white text-indigo-600 font-semibold rounded-xl shadow hover:bg-gray-100 transition">
                Crear cuenta gratuita
            </a>
        </div>
    </section>
</x-main>
@endsection
