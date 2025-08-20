@extends('plumr.layout.app')

@section('main')
<x-main>
    <!-- Crear un nuevo artículo -->
    <section style="max-height: 90vh; overflow-y: auto;">
        <section class="flex justify-between items-center">
            <p class="text-lg font-semibold">Editar artículo</p>
            <span>{{ $article->title }}</span>
        </section>
    </section>

    <!-- Formularios de editores -->
    <form
    action="{{ route('articles.update', [$user, $article]) }}"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-4"
    x-data="{ imagePreview: '{{ $article->cover_url ?? '' }}' }">
        @csrf
        @method('PUT')

        <!-- Autor y Profesión en fila -->
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Autor -->
            <section class="flex flex-col w-full md:w-1/2">
                <label class="text-gray-700 mb-1">Autor</label>
                <input type="text" name="author" id="author" value="{{ old('author', $article->author ?? '') }}" placeholder="Ingresa autor del artículo" autocomplete="off" autofocus class="p-3 rounded-lg bg-blue-50 border border-gray-300
                       focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('author') }}" />
                @error('author')
                <p class="text-red-500 text-xs my-2">{{ $message }}</p>
                @enderror
            </section>

            <!-- Profesión -->
            <section class="flex flex-col w-full md:w-1/2">
                <label class="text-gray-700 mb-1">Profesión</label>
                <input type="text" name="profession" id="profession" value="{{ old('profession', $article->profession ?? '') }}" placeholder="Ingresa un profesión para el artículo" autocomplete="off" class="p-3 rounded-lg bg-blue-50 border border-gray-300
                       focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('profession') }}" />
                @error('profession')
                <p class="text-red-500 text-xs my-2">{{ $message }}</p>
                @enderror
            </section>
        </div>

        <!-- Título y Subtítulo en fila -->
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Título -->
            <section class="flex flex-col w-full md:w-1/2">
                <label class="text-gray-700 mb-1">Título</label>
                <input type="text" name="title" id="title" value="{{ old('title', $article->title ?? '') }}" placeholder="Ingresa un título para el artículo" autocomplete="off" autofocus class="p-3 rounded-lg bg-blue-50 border border-gray-300
                       focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('title') }}" />
                @error('title')
                <p class="text-red-500 text-xs my-2">{{ $message }}</p>
                @enderror
            </section>

            <!-- Subtítulo -->
            <section class="flex flex-col w-full md:w-1/2">
                <label class="text-gray-700 mb-1">Subtítulo</label>
                <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle', $article->subtitle ?? '') }}" placeholder="Ingresa un subtítulo para el artículo" autocomplete="off" class="p-3 rounded-lg bg-blue-50 border border-gray-300
                       focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('subtitle') }}" />
                @error('subtitle')
                <p class="text-red-500 text-xs my-2">{{ $message }}</p>
                @enderror
            </section>
        </div>

        <!-- Contenido -->
        <section class="flex flex-col gap-1">
            <label class="text-sm text-gray-700" for="summary">Resumen</label>
            <textarea name="summary" id="summary" placeholder="Ingresa un resumen del artículo" autocomplete="off" min="15" max="255" class="rounded-md p-2 shadow-sm bg-white border {{ e_class('summary') }}">{{ old('summary',$article->summary ?? '') }}</textarea>
            @error('summary')
            <p class="text-xs text-red-500">{{ $message }}</p>
            @enderror
        </section>

        {{-- Inicio --}}
        <section class="flex flex-col">
            <label for="header" class="block text-gray-700 mb-1">Inicio</label>
            @livewire('advanced-editor', [
            'editorId' => 'header_cmp1',
            'placeholder' => 'Escribe un texto para inicio',
            'fieldName' => 'header',
            'content' => old('header', $article->header ?? ''),
            ])
            @error('header')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </section>

        {{-- Desarrollo --}}
        <section class="flex flex-col">
            <label for="content" class="block text-gray-700 mb-1">Desarrollo</label>
            @livewire('advanced-editor', [
            'editorId' => 'content_cmp1',
            'placeholder' => 'Escribe un texto para desarrollo',
            'fieldName' => 'content',
            'content' => old('content', $article->content ?? ''),
            ])
            @error('content')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </section>

        {{-- Conclusión --}}
        <section class="flex flex-col">
            <label for="footer" class="block text-gray-700 mb-1">Conclusión</label>
            @livewire('advanced-editor', [
            'editorId' => 'footer_cmp1',
            'placeholder' => 'Escribe un texto para conclusión',
            'fieldName' => 'footer',
            'content' => old('footer', $article->footer ?? ''),
            ])
            @error('footer')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </section>

        {{-- Portada --}}
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Portada -->
            <section class="flex flex-col w-full md:w-1/2">
                <label class="text-gray-700 mb-1">Portada</label>
                <input type="file" accept="image/*" name="cover" id="cover"
                @change="imagePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                value="{{ old('cover',$article->cover_url ?? '') }}" placeholder="Ingresa fecha de publicación" autocomplete="off" class="p-3 rounded-lg bg-blue-50 border border-gray-300
                       focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('cover') }}" />
                @error('cover')
                <p class="text-red-500 text-xs my-2">{{ $message }}</p>
                @enderror
            </section>

            {{-- Portada Vista Previa --}}
            <section class="flex flex-col w-full md:w-1/2">
                <template x-if="imagePreview">
                    <div class="mt-4">
                        <p class="text-sm text-gray-500 mb-1">Vista previa:</p>
                        <img :src="imagePreview" alt="Imagen previa" class="h-40 w-auto rounded border">
                    </div>
                </template>
            </section>
        </div>

        <!-- Etiquetas y Redes Sociales en fila -->
        <div class="flex flex-col md:flex-row gap-4">

            <!-- Publicado en -->
            <section class="flex flex-col w-full md:w-1/2">
                <label class="text-gray-700 mb-1">Publicado en</label>
                <input
                    type="datetime-local"
                    name="published_at"
                    id="published_at"
                    value="{{ old('published_at', optional($article->published_at)->format('Y-m-d\TH:i') ?? '') }}"
                    placeholder="Ingresa fecha de publicación"
                    autocomplete="off"
                    class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('published_at') }}"
                />
                @error('published_at')
                    <p class="text-red-500 text-xs my-2">{{ $message }}</p>
                @enderror
            </section>

            <!-- Etiquetas -->
            <section class="flex flex-col w-full md:w-1/2">
                <label class="text-gray-700 mb-1">Etiquetas</label>
                <input type="text" name="tags" id="tags" value="{{ old('tags', $article->tags_str ?? '') }}" placeholder="Ingresa tus etiquetas" autocomplete="off" autofocus class="p-3 rounded-lg bg-blue-50 border border-gray-300
                       focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('tags') }}" />
                <span class="text-xs text-gray-500 mt-2">Escribe etiquetas separadas con comas(,)</span>
                @error('tags')
                <p class="text-red-500 text-xs my-2">{{ $message }}</p>
                @enderror
            </section>
        </div>

        <!-- Publico y Publicado en en fila -->
        <div class="flex flex-col md:flex-row gap-4">

            <!-- Publico -->
            <section class="flex flex-col w-full md:w-1/2">
                <label class="text-gray-700 mb-1">¿Deseas publicarlo?</label>
                <div x-data="{ is_publish: '{{ old('is_publish', $article->is_publish ?? false) ? 'true' : 'false' }}' }" class="flex items-center space-x-2">
                    <!-- Label -->
                    <span class="text-gray-700">Publicar</span>

                    <!-- Input oculto para enviar el valor -->
                    <input type="hidden" name="is_publish" :value="is_publish === 'true' ? 1 : 0">

                    <!-- Switch -->
                    <button
                        type="button"
                        @click="is_publish = is_publish === 'true' ? 'false' : 'true'"
                        :class="is_publish === 'true' ? 'bg-green-500' : 'bg-gray-300'"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                    >
                        <span
                            :class="is_publish === 'true' ? 'translate-x-6' : 'translate-x-1'"
                            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                        ></span>
                    </button>
                </div>
            </section>


            <!-- Redes sociales -->
            <section class="flex flex-col gap-2 w-full md:w-1/2">
                <label class="text-gray-700 mb-1">Redes sociales</label>
                <span class="text-xs text-gray-500">Escribe el enlace / URL</span>

                <input type="text" name="network_social[youtube]" id="network_social_youtube"
                    value="{{ old('network_social.youtube', $article->network_social['youtube'] ?? '') }}"
                    placeholder="Ingresa tu canal de YouTube" autocomplete="off"
                    class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('network_social.youtube') }}" />

                <input type="text" name="network_social[twitter-x]" id="network_social_twitter"
                    value="{{ old('network_social.twitter-x', $article->network_social['twitter-x'] ?? '') }}"
                    placeholder="Ingresa tu cuenta de X" autocomplete="off"
                    class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('network_social.twitter-x') }}" />

                <input type="text" name="network_social[linkedin]" id="network_social_linkedin"
                    value="{{ old('network_social.linkedin', $article->network_social['linkedin'] ?? '') }}"
                    placeholder="Ingresa tu cuenta de LinkedIn" autocomplete="off"
                    class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('network_social.linkedin') }}" />

                <input type="text" name="network_social[link]" id="network_social_link"
                    value="{{ old('network_social.link', $article->network_social['link'] ?? '') }}"
                    placeholder="Ingresa un enlace externo" autocomplete="off"
                    class="p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm {{ e_class('network_social.link') }}" />

                @error('network_social')
                    <p class="text-red-500 text-xs my-2">{{ $message }}</p>
                @enderror
            </section>

        </div>

        <button type="submit" class=" bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-2 rounded-lg shadow transition">Actualizar artículo</button>
    </form>
</x-main>
@endsection
