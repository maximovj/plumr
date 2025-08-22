<section
    class="scroll-plumr relative"
    style="height: 100vh; max-height: 100vh; overflow: auto;"
    x-data="{
        isLoading: false,
        scrollObserver() {
            let el = this.$el;
            el.addEventListener('scroll', () => {
                if (!this.isLoading && el.scrollTop + el.clientHeight >= el.scrollHeight - 50) {
                    this.isLoading = true;
                    @this.emit('load-more');
                }
            });

            Livewire.on('loaded-more', () => {
                this.isLoading = false;
            });
        }
    }"
    x-init="scrollObserver"
>
    {{-- Cabecera --}}
    <div class="sticky top-0 bg-gray-50 w-full flex flex-row justify-between items-center mb-4 p-2 z-10">
        <div class="rounded-full text-center flex justify-center">
            <span class="text-xs">
                <i class="bi bi-file-post-fill"></i>
                <strong>{{ $posts->total() }}</strong>
            </span>
        </div>
        <h4>Publicaciones</h4>
    </div>

    @if($posts->isEmpty())
        <div class="border-2 border-gray-100 rounded-md p-4">
            <p class="text-center">No hay publicaciones aún</p>
        </div>
    @else
        {{-- Lista de posts --}}
        @foreach ($posts as $post)
            <div class="border-2 border-gray-100 rounded-lg mt-4">
                <article class="p-4">
                    <section class="flex flex-row justify-start gap-2 py-2">
                        @if($post->author[0]->profile->photo)
                        @else
                            <a href="{{ route('main_account', ['user' => $post->author[0]->username]) }}">
                                <div
                                class="w-6 h-6 bg-white rounded-full text-center border-2 border-gray-400
                                        flex items-center justify-center">
                                {{ strtoupper(mb_substr($post->author[0]->username, 0, 1)) }}
                                </div>
                            </a>
                        @endif

                        <a href="{{ route("posts.show", [$post->author[0]->username, $post]) }}"><i class="bi bi-chat-square-quote"></i></a>
                    </section>

                    <h1 class="font-bold">{{ $post->title }}</h1>
                    <div class="ql-snow">
                        <div class="ql-editor" contenteditable="false">
                            {!! $post->content !!}
                        </div>
                    </div>

                    <section class="flex flex-row gap-1 py-2">
                        <p class="text-xs">
                            Creado por
                            {{ $user->username === $post->author[0]->username ? 'mí' : $post->author[0]->username }}
                            {{ $post->created_at->diffForHumans() }}
                        </p>
                    </section>
                </article>
            </div>
        @endforeach
    @endif

    {{-- Loader --}}
    <div wire:loading class="text-center p-4 text-gray-500">
        <i class="bi bi-arrow-repeat animate-spin"></i> Cargando más publicaciones...
    </div>
</section>

<!---
<section class="grid grid-cols-1 gap-4 mx-4 scroll-plumr" style="height: 100vh; max-height: 100vh; overflow: auto;">
                <div class="flex flex-row justify-between items-center px-2">
                    <div
                        class="px-2 rounded-full text-center
                        flex items-center justify-center">
                        <span class="text-xs">
                            <i class="bi bi-file-post-fill"></i>
                            <strong>1 000</strong>
                        </span>
                    </div>
                    <h4>Mis publicaciones</h4>
                </div>
                <div class="border-2 border-gray-100 rounded-md">

                    {{-- Crear nuevo publicación --}}
                    <article class="p-4 bg-gray-100 shadow-md">
                        <form>
                            <section class="flex flex-col gap-2 mb-4">
                                <label class="text-xs text-gray-700" for="title">Título</label>
                                <input type="text" name="title" value="{{ old('title') }}" id="title"
                                    placeholder="Ingresa tu Título" autocomplete="off"
                                    class="rounded-md p-2 {{ e_class('title') }} bg-blue-50 shadow-sm" />
                                @error('title')
                                    <p class="text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </section>

                            <section class="flex flex-col gap-2 mb-4">
                                <label class="text-xs text-gray-700" for="content">Contenido</label>
                                <textarea type="text" name="content" value="{{ old('content') }}" id="content"
                                    placeholder="Ingresa tu contenido" autocomplete="off"
                                    class="rounded-md p-2 {{ e_class('content') }} bg-blue-50 shadow-sm"
                                ></textarea>
                                @error('content')
                                    <p class="text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </section>
                        </form>

                        {{-- Botones --}}
                        <section>
                            <button class="bg-green-100 p-2 rounded-md">
                                <span class="text-xs"><i class="bi bi-save"></i> Crear artículo</span>
                            </button>
                        </section>
                    </article>
                </div>
                @foreach ([1,2,3,4,5,6,7,8,9,10] as $posts)
                <div class="border-2 border-gray-100 rounded-md">
                    <article class="p-4">
                        {{-- Botones --}}
                        <section class="flex flex-row justify-between gap-1 py-2">
                            <i class="bi bi-chat-square-quote"></i>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="#" class="bg-yellow-100 p-2 rounded-md">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <a href="#" class="bg-red-100 p-2 rounded-md">
                                    <i class="bi bi-trash"></i> Eliminar
                                </a>
                            </div>
                        </section>

                        <h1 class="font-bold">Lorem ipsum dolor sit amet consectetur, adipisicing elit.</h1>
                        <p>
                            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatum mollitia error porro labore quos soluta
                            natus eum impedit quod deserunt architecto consequatur voluptatibus unde, minus ipsam veniam aspernatur et
                            vel?
                        </p>

                        {{-- Información de la publicación --}}
                        <section class="flex flex-row gap-1 py-2">
                            <p class="text-xs">Creado hace 5 minutos</p>
                        </section>

                        {{-- Estadísticas --}}
                        <section class="flex flex-row gap-1 py-2">
                            <p class="text-sm"><i class="bi bi-wechat"></i>&nbsp;<strong>1 000</strong>&nbsp;Discusiones</p>
                            <p class="text-sm"><i class="bi bi-1-square"></i></i>&nbsp;<strong>1 000</strong>&nbsp;Apoyo</p>
                            <p class="text-sm"><i class="bi bi-1-square"></i></i>&nbsp;<strong>1 000</strong>&nbsp;Difiero</p>
                            <p class="text-sm"><i class="bi bi-1-square"></i></i>&nbsp;<strong>1 000</strong>&nbsp;Neutral</p>
                        </section>

                        {{-- Botones --}}
                        <section class="hidden">
                            <button class="bg-green-100 p-2 rounded-md">
                                <i class="bi bi-1-square"></i> Apoyo
                            </button>
                            <button class="bg-red-100 p-2 rounded-md">
                                <i class="bi bi-1-square"></i> Difiero
                            </button>
                            <button class="bg-blue-100 p-2 rounded-md">
                                <i class="bi bi-1-square"></i> Neutral
                            </button>
                        </section>
                    </article>
                </div>
                @endforeach
            </section>
--->
