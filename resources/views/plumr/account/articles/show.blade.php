@extends('plumr.layout.app')

@section('main')
<x-main>
    <article class="max-w-6xl mx-auto px-2 py-0">


        <!-- Botón de volver -->
        <section class="mb-4">
            <a href="{{ route('articles.index', [$user]) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md shadow-sm">
                ← Volver
            </a>
        </section>

        <!-- Imagen de portada -->
        @if($article->cover_url)
            <div class="mb-8">
                <img src="{{ asset($article->cover_url) }}" alt="{{ $article->title }}"
                     class="w-full h-80 object-cover rounded-2xl shadow-md">
            </div>
        @endif

        <!-- Encabezado del artículo -->
        <header class="mb-6">
            <div class="mb-3 flex justify-between items-start gap-4">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $article->title }}</h1>
                    @if($article->subtitle)
                        <h2 class="text-lg text-gray-500 mb-4">{{ $article->subtitle }}</h2>
                    @endif
                </div>

                @owner($user)
                    <div class="flex flex-row gap-2">
                        <a href="{{ route('articles.edit', [$user, $article]) }}"
                            class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded hover:bg-yellow-200 text-sm flex items-center gap-2 transform transition hover:scale-105 whitespace-nowrap">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="#"
                            class="bg-red-100 text-red-700 px-3 py-1 rounded hover:bg-red-200 text-sm flex items-center gap-2 transform transition hover:scale-105 whitespace-nowrap">
                            <i class="bi bi-trash"></i> Eliminar
                        </a>
                    </div>
                @endowner
            </div>

            <div class="flex flex-wrap items-center text-sm text-gray-500 gap-4">
                <span><i class="bi bi-person"></i> {{ $article->author ?? 'Autor desconocido' }}</span>
                <span><i class="bi bi-briefcase"></i> {{ $article->profession ?? 'Profesión' }}</span>
                <span><i class="bi bi-clock"></i> {{ $article->published_at ? $article->published_at->diffForHumans() : $article->created_at->diffForHumans() }}</span>
                @if($article->is_publish)
                    <span class="flex items-center gap-1 text-green-600"><i class="bi bi-check-circle-fill"></i> Publicado</span>
                @else
                    <span class="flex items-center gap-1 text-red-600"><i class="bi bi-x-circle-fill"></i> Borrador</span>
                @endif
            </div>
        </header>

        <!-- Header personalizado -->
        @if($article->header)
            <div class="prose prose-lg max-w-none mb-6">
                <div class="ql-snow">
                <div class="ql-editor" contenteditable="false" data-placeholder="Bienvenido a mi artículo">{!! $article->header !!}</div>
                </div>
            </div>
        @endif

        <!-- Contenido principal -->
        <section class="prose prose-lg max-w-none mb-8 text-gray-800">
            <div class="ql-snow">
                <div class="ql-editor" contenteditable="false" data-placeholder="Bienvenido a mi artículo">{!! $article->content !!}</div>
            </div>
        </section>

        <!-- Footer personalizado -->
        @if($article->footer)
        <footer class="prose max-w-none border-t pt-6 mt-6 text-gray-700">
            <div class="ql-snow">
                <div class="ql-editor" contenteditable="false" data-placeholder="Bienvenido a mi artículo">{!! $article->footer !!}</div>
            </div>
        </footer>
        @endif

        <!-- Tags -->
        @if($article->tags)
            <div class="mt-6 flex flex-wrap gap-2">
                @foreach($article->tags as $tag)
                    <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full">{{ $tag }}</span>
                @endforeach
            </div>
        @endif

        <!-- Redes sociales -->
        @if($article->network_social)
            <div class="mt-6 flex flex-wrap gap-3">
                @foreach($article->network_social as $key => $link)
                    <a href="{{ $link }}" target="_blank"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg border text-sm hover:bg-gray-100 transition">
                        <i class="bi bi-{{ $key }}"></i> {{ ucfirst($key) }}
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Info SEO (opcional, visible solo para el autor/admin) -->
        @owner($user ?? null)
            <div class="mt-10 p-4 bg-gray-50 border rounded-lg text-xs text-gray-600">
                <h3 class="font-semibold mb-2">SEO / Open Graph</h3>
                <p><strong>Título SEO:</strong> {{ $article->seo_title }}</p>
                <p><strong>Descripción SEO:</strong> {{ $article->seo_description }}</p>
                <p><strong>Keywords:</strong> {{ $article->seo_keywords }}</p>
                <p><strong>OG Title:</strong> {{ $article->og_title }}</p>
                <p><strong>OG Description:</strong> {{ $article->og_description }}</p>
                @if($article->cover_url)
                    <img src="{{ asset($article->cover_url) }}" alt="Open Graph Image" class="w-40 h-40 object-cover mt-2 rounded">
                @endif
            </div>
        @endowner

    </article>
</x-main>
@endsection
