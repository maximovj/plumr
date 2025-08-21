@extends('plumr.layout.app')

@section('main')
<style>
html { scroll-behavior: smooth; }
</style>

<x-main>
    <article class="max-w-6xl mx-auto px-4 py-6 space-y-8">

        {{-- CONTADOR DE LECTURA / PROGRESO --}}
        <div class="fixed top-0 left-0 h-1 bg-blue-500 z-50" x-ref="progressBar"></div>

        <!-- Botón de volver y título -->
        <section class="mb-4 flex justify-between">
            <a href="{{ route('articles.index', [$user]) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg shadow transition">
                ← Volver
            </a>

            <div>
                @owner($user)
                <h4 class="text-lg font-semibold text-gray-800">Mi artículo</h4>
                @else
                <h4 class="text-lg text-gray-800">
                    Artículo de
                    <a href="{{ route('main_account', ['user' => $user]) }}" class="font-bold hover:underline">
                        {{ '@' . $user->username }}
                    </a>
                </h4>
                @endowner
            </div>
        </section>

        <!-- Imagen de portada -->
        @if (!empty($article->cover_url))
        <div class="mb-8 overflow-hidden rounded-2xl shadow-md animate__animated animate__fadeIn">
            <img src="{{ asset($article->cover_url) }}" alt="{{ $article->title }}" class="w-full h-80 object-cover">
        </div>
        @endif

        <!-- Encabezado del artículo -->
        <header class="mb-6 space-y-4 animate__animated animate__fadeIn">
            <div class="flex flex-col lg:flex-row justify-between items-end gap-4">

                <!-- Perfil de usuario -->
                <section class="hidden lg:flex flex-col items-center" x-data="{ open: false }">
                    <div @mouseenter="open = true" @mouseleave="open = false" class="relative">
                        <a href="{{ route('main_account', [$user]) }}">
                            <img src="{{ asset('img/user_default.png') }}" alt="Foto de usuario" class="w-20 h-20 rounded-full border-2 border-gray-200 shadow-md transition transform hover:scale-105">
                        </a>

                        <!-- Tarjeta interactiva -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute left-1/2 transform -translate-x-1/2 w-56 bg-white rounded-xl shadow-lg border p-4 text-center z-10 animate__animated animate__fadeIn animate__fast">

                            <h3 class="font-semibold text-gray-800">{{ $user->profile->fullname ?? 'Nombre' }}</h3>
                            <p class="text-xs text-gray-500 mb-2">{{ '@' . $user->username }}</p>

                            <div class="grid grid-rows-4 grid-flow-col space-x-1 text-xs text-gray-600 mb-3">
                                <span class="font-semibold">{{ $user->followers->count() ?? 0 }}</span>
                                <span>Seguidores</span>
                                <span class="font-semibold">{{ $user->articles->count() ?? 0 }}</span>
                                <span>Artículos</span>
                                <span class="font-semibold">{{ $user->posts->count() ?? 0 }}</span>
                                <span>Publicaciones</span>
                                <span class="font-semibold">{{ $user->followings->count() ?? 0 }}</span>
                                <span>Seguidos</span>
                            </div>

                            @if (Auth::check() && Auth::id() !== $user->id)
                            <div>
                                @livewire('follow-button', ['user' => $user])
                            </div>
                            @endif
                        </div>
                    </div>

                    <span class="mt-2 text-xs text-gray-500">{{ '@' . $user->username }}</span>
                </section>

                <!-- Información del artículo -->
                <section class="flex-1 space-y-1">
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">{{ $article->title ?? 'Sin título' }}</h1>
                    @if (!empty($article->subtitle))
                    <h2 class="text-lg text-gray-500">{{ $article->subtitle }}</h2>
                    @endif

                    <div class="flex flex-wrap items-center text-sm text-gray-500 gap-4 mt-2">
                        <span><i class="bi bi-person"></i> {{ $article->author ?? 'Autor desconocido' }}</span>
                        <span><i class="bi bi-briefcase"></i> {{ $article->profession ?? 'Profesión' }}</span>
                        <span><i class="bi bi-clock"></i>
                            {{ $article->published_at ? $article->published_at->diffForHumans() : $article->created_at->diffForHumans() }}
                        </span>
                        @if ($article->is_publish)
                        <span class="flex items-center gap-1 text-green-600"><i class="bi bi-check-circle-fill"></i>
                            Publicado</span>
                        @else
                        <span class="flex items-center gap-1 text-red-600"><i class="bi bi-x-circle-fill"></i>
                            Borrador</span>
                        @endif
                    </div>
                </section>

                <!-- Botones de acción -->
                <section class="flex flex-row gap-2 mt-4 lg:mt-0">
                    @owner($user)
                    <a href="{{ route('articles.edit', [$user, $article]) }}" class="flex items-center gap-2 bg-yellow-100 text-yellow-700 px-3 py-1 rounded hover:bg-yellow-200 transition">
                        <i class="bi bi-pencil"></i> Editar
                    </a>

                    <button
                        x-data
                        x-on:click="Livewire.emit('confirmDeleteModelClass',
                            'App\\Models\\Article', // Clase del modelo
                            {{ $article->id }},     // ID del registro
                            '{{ route('articles.index', $user) }}', // Redirect (opcional)
                            '¿Eliminar artículo?',  // Título (opcional)
                            'Este artículo se eliminará permanentemente.' // Mensaje (opcional)
                        )"
                        class="flex items-center gap-2 bg-red-100 text-red-700 px-3 py-1 rounded hover:bg-red-200 transition"
                    >
                        <i class="bi bi-trash"></i> Eliminar
                    </button>

                    {{-- @livewire('confirm-delete-model', [
                        'user' => $user,
                        'model' => $article,
                        'redirect' => route('articles.index', [$user]),
                        'title' => '¿Eliminar artículo?',
                        'message' => 'Este artículo se eliminará permanentemente.'
                    ], key($article->id)) --}}
                    @endowner
                </section>

            </div>
        </header>

        {{-- TABLA DE CONTENIDO EXPANDIBLE FUNCIONAL --}}
        <nav
            x-data="{ show: false, toc: initTOC(), expanded: false }"
            x-init="window.addEventListener('scroll', () => show = window.scrollY > 300)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-[-20px]"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-[-20px]"
            :class="expanded ? 'w-48' : 'w-14'"
            class="hidden z-50 lg:block fixed left-3 top-60 p-4 bg-white dark:bg-gray-800 rounded shadow transition-all duration-300"
            style="max-height: calc(100vh - 10rem); overflow-y:auto;"
        >
            <div class="flex justify-between items-center mb-2">
                <h5 class="font-semibold text-gray-700 dark:text-gray-200" x-show="expanded">Contenido</h5>
                <button @click="expanded = !expanded" class="text-gray-500 hover:text-gray-800 dark:hover:text-white text-sm">
                    <template x-if="expanded"><i class="bi bi-chevron-left"></i></template>
                    <template x-if="!expanded"><i class="bi bi-chevron-right"></i></template>
                </button>
            </div>

            <ul class="space-y-1">
                <template x-for="item in toc" :key="item.id">
                    <li>
                        <a :href="`#${item.id}`"
                        class="flex items-center justify-start text-xs text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 p-1 rounded"
                        x-bind:title="!expanded ? item.text : ''"
                        x-text="expanded ? item.text : '•'">
                        </a>
                    </li>
                </template>
            </ul>
        </nav>


        <!-- Redes sociales -->
        @if (!empty($article->network_social))
        <div class="mt-6 flex flex-wrap gap-3">
            @foreach ($article->network_social as $key => $link)
            <a href="{{ $link }}" target="_blank" class="flex items-center gap-2 px-3 py-2 rounded-lg border text-sm hover:bg-gray-100 transition">
                <i class="bi bi-{{ $key }}"></i> {{ ucfirst($key) }}
            </a>
            @endforeach
        </div>
        @endif

        <div class="flex items-center my-6">
            <hr class="flex-grow border-t border-gray-300">
            <span class="mx-3 text-gray-500"><i class="bi bi-star"></i></span>
            <hr class="flex-grow border-t border-gray-300">
        </div>

        <!-- Header personalizado -->
        @if (!empty($article->header))
        <div class="prose prose-lg max-w-none mb-6 p-4 bg-gray-50 text-gray-800">
            <div class="ql-snow">
                <div class="ql-editor" contenteditable="false">{!! $article->header !!}</div>
            </div>
        </div>
        @endif

        <!-- Contenido principal -->
        <section class="prose prose-lg max-w-none mb-6 p-4 bg-gray-50 text-gray-800">
            <div class="ql-snow">
                <div class="ql-editor" contenteditable="false">{!! $article->content !!}</div>
            </div>
        </section>

        <!-- Footer personalizado -->
        @if (!empty($article->footer))
        <footer class="prose prose-lg max-w-none mb-6 p-4 bg-gray-50 text-gray-800">
            <div class="ql-snow">
                <div class="ql-editor" contenteditable="false">{!! $article->footer !!}</div>
            </div>
        </footer>
        @endif

        <div class="flex items-center my-6">
            <hr class="flex-grow border-t border-gray-300">
            <span class="mx-3 text-gray-500"><i class="bi bi-star-fill"></i></span>
            <hr class="flex-grow border-t border-gray-300">
        </div>

        <!-- Tags -->
        @if (!empty($article->tags))
        <div class="mt-6 flex flex-wrap gap-2">
            @foreach ($article->tags as $tag)
            <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full">{{ $tag }}</span>
            @endforeach
        </div>
        @endif

        @php
        $shareUrl = urlencode(url()->current());
        $articleTitle = urlencode($article->title ?? '');
        @endphp

        <section class="mt-8 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 p-4 bg-gray-50 rounded-lg shadow animate__animated animate__fadeIn">

            <!-- Botones de redes sociales -->
            <div class="flex flex-wrap gap-2 items-center">
                <span class="text-sm font-semibold text-gray-700">Compartir:</span>

                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" class="flex items-center gap-2 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                    <i class="bi bi-facebook"></i> Facebook
                </a>

                <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $articleTitle }}" target="_blank" class="flex items-center gap-2 px-3 py-2 bg-blue-400 text-white rounded-lg hover:bg-sky-500 transition text-sm">
                    <i class="bi bi-twitter"></i> Twitter
                </a>

                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" class="flex items-center gap-2 px-3 py-2 bg-blue-800 text-white rounded-lg hover:bg-blue-900 transition text-sm">
                    <i class="bi bi-linkedin"></i> LinkedIn
                </a>
            </div>

            <!-- Insertar artículo -->
            <div x-data="{
                    open: false,
                    copied: false,
                    articleCode: `<iframe src='{{ url()->current() }}' width='600' height='400' style='border:none;'></iframe>`,
                    async copy() {
                        try {
                            await navigator.clipboard.writeText(this.articleCode);
                            this.copied = true;
                        } catch (e) {
                            try {
                                const ta = document.createElement('textarea');
                                ta.value = this.articleCode;
                                ta.setAttribute('readonly', '');
                                ta.style.position = 'fixed';
                                ta.style.opacity = '0';
                                document.body.appendChild(ta);
                                ta.select();
                                document.execCommand('copy');
                                document.body.removeChild(ta);
                                this.copied = true;
                            } catch (err) {
                                this.copied = false;
                                console.error('No se pudo copiar:', err);
                            }
                        }
                        setTimeout(() => this.copied = false, 2000);
                    }
                }" class="relative w-full lg:w-auto mt-4 lg:mt-0">
                <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition text-sm">
                    <i class="bi bi-code-slash"></i> Código embebido
                </button>

                <div x-cloak x-show="open" x-transition @keydown.escape.window="open = false" class="absolute -left-28 lg:right-0 bottom-full mb-2 w-full lg:w-96 bg-white border rounded-xl shadow-lg p-4 z-20 animate__animated animate__fadeInDown">
                    <p class="text-xs text-gray-500 mb-2">Copia este código para insertar el artículo en otra página:
                    </p>

                    <textarea x-model="articleCode" class="w-full h-24 p-2 text-xs border rounded resize-none focus:ring focus:ring-blue-200" readonly></textarea>

                    <button @click="copy()" class="mt-3 px-3 py-1.5 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm w-full flex items-center justify-center gap-2">
                        <i class="bi bi-clipboard"></i>
                        <span x-show="!copied">Copiar código</span>
                        <span x-show="copied" class="flex items-center gap-1">
                            <i class="bi bi-check2-circle"></i> ¡Copiado!
                        </span>
                    </button>
                </div>
            </div>
        </section>

        <!-- SEO / Open Graph -->
        @owner($user ?? null)
        <div class="mt-10 p-4 bg-gray-50 border rounded-lg text-xs text-gray-600">
            <h3 class="font-semibold mb-2">SEO / Open Graph</h3>
            <p><strong>Título SEO:</strong> {{ $article->seo_title }}</p>
            <p><strong>Descripción SEO:</strong> {{ $article->seo_description }}</p>
            <p><strong>Keywords:</strong> {{ $article->seo_keywords }}</p>
            <p><strong>OG Title:</strong> {{ $article->og_title }}</p>
            <p><strong>OG Description:</strong> {{ $article->og_description }}</p>
            @if (!empty($article->cover_url))
            <img src="{{ asset($article->cover_url) }}" alt="Open Graph Image" class="w-40 h-40 object-cover mt-2 rounded">
            @endif
        </div>
        @endowner

        <!-- Botón "Ir al inicio" minimalista -->
        <div x-data="{ show: false }"
            x-init="window.addEventListener('scroll', () => show = (window.scrollY > 300))"
            class="fixed bottom-6 right-6 z-50">

            <button x-show="show"
                    @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                    x-transition:enter="animate__animated animate__fadeInUp"
                    x-transition:leave="animate__animated animate__fadeOutDown"
                    class="relative group w-14 h-14 flex items-center justify-center rounded-full
                        bg-white shadow text-gray-700 animate__animated animate__pulse hover:shadow-md
                        hover:bg-opacity-100 transition-all duration-300 transform hover:scale-110">

                <!-- Icono -->
                <i class="bi bi-arrow-up text-2xl"></i>

                <!-- Tooltip -->
                <span class="absolute -top-3 right-1/2 translate-x-1/2 bg-gray-800 text-white text-xs px-3 py-1 rounded-lg
                            opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap shadow-lg">
                    Ir al inicio
                </span>
            </button>
        </div>

    </article>

    <script>
        function initTOC() {
            var headers = document.querySelectorAll('.ql-editor h1, .ql-editor h2, .ql-editor h3, .ql-editor h4');
            var temp = [];
            for (var i = 0; i < headers.length; i++) {
                if (!headers[i].id) headers[i].id = 'toc-' + i;
                temp.push({ id: headers[i].id, text: headers[i].innerText });
            }
            return temp;
        }
    </script>

    <script>
        // Contador de lectura
        window.addEventListener('scroll', () => {
            const bar = document.querySelector('[x-ref="progressBar"]');
            const scrollTop = window.scrollY;
            const docHeight = document.body.scrollHeight - window.innerHeight;
            const progress = (scrollTop / docHeight) * 100;
            bar.style.width = progress + '%';
        });
    </script>

</x-main>
@endsection
