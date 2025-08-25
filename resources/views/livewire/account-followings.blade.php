<section
    class="scroll-plumr"
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
    <div class="flex flex-row justify-between px-2 mb-4">
        <div class="px-2 rounded-full text-center flex justify-center">
            <span class="text-xs">
                <i class="bi bi-file-post-fill"></i>
                <strong>{{ $followings->total() }}</strong>
            </span>
        </div>
        <h4>Seguidos de
            <a href="{{ route('main_account', ['user' => $user]) }}">
                <span class="font-bold">{{ "@".$user->username  }}</span>
            </a>
        </h4>
    </div>

    @if($followings->isEmpty())
        <div class="border-2 border-gray-100 rounded-md p-4">
            <p class="text-center">No hay seguidos aún</p>
        </div>
    @else
        {{-- Lista de seguidos --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
        @foreach ($followings as $account)
        <!-- Tarjeta de perfil -->
        <section class="mx-4 bg-white rounded-2xl shadow overflow-hidden relative mb-4">

            <!-- Imagen de portada -->
            <div class="relative h-40">
                @if($account->profile->cover)
                    <div class="h-full w-full bg-top bg-contain bg-no-repeat" style="background-image: url('{{ asset($account->profile->cover_url) }}')"></div>
                @else
                    <div class="h-full w-full bg-center bg-cover" style="background-image: url('{{ asset('img/fondo.jpg') }}')"></div>
                @endif

                <!-- Foto de usuario -->
                <a href="{{ route('main_account', ['user' => $account]) }}">
                    <div class="absolute -bottom-12 left-6">
                        <img src="{{ asset($account->profile->photo_url) }}" alt="Foto de usuario"
                            class="w-28 h-28 rounded-full border-4 border-white shadow-lg cursor-pointer hover:scale-105 transition transform">
                    </div>
                </a>
            </div>

            <!-- Información principal -->
            <div class="mt-16 px-6 pb-4">
                <a href="{{ route('main_account', ['user' => $account]) }}">
                    <p class="font-bold text-xl">{{ $account->profile->fullname }}</p>
                    <h1 class="text-sm text-gray-500">@<span class="font-medium">{{ $account->username }}</span></h1>
                </a>

                <!-- Bio -->
                @isset($account->profile->bio)
                    <p class="text-gray-500 text-sm italic mt-2 border-l-2 border-gray-300 pl-2">{{ $account->profile->bio }}</p>
                @endisset

                <!-- Información adicional -->
                <div class="flex flex-wrap gap-3 mt-3 text-xs text-gray-600">
                    @isset($account->profile->number_phone)<span><i class="bi bi-telephone-fill"></i> {{ $account->profile->number_phone }}</span>@endisset
                    <span><i class="bi bi-calendar-fill"></i> {{ $account->profile->birthday->format('d/m/Y') }}</span>
                    <span><i class="bi bi-gender-ambiguous"></i> {{ $account->profile->sex }}</span>
                    @isset($account->profile->country)<span><i class="bi bi-geo-alt-fill"></i> {{ $account->profile->country }}</span>@endisset
                    @isset($account->profile->city)<span><i class="bi bi-building"></i> {{ $account->profile->city }}</span>@endisset
                    @isset($account->profile->address)<span><i class="bi bi-house-fill"></i> {{ $account->profile->address }}</span>@endisset
                </div>

                <!-- Botones de acciones (solo para el dueño) -->
                <div class="mt-4 flex justify-between items-center gap-2">
                    @if(Auth::check() && Auth::user()->id !== $account->id)
                        @livewire('follow-button', ['user' => $account], key('follow-button-'.$account->id))
                    @else
                        <span></span>
                    @endif

                    @livewire('list-followers', ['user' => $account], key('list-followers-'.$account->id))
                </div>
            </div>

        </section>
        @endforeach
        </div>
    @endif

    {{-- Loader --}}
    <div wire:loading class="text-center p-4 text-gray-500">
        <i class="bi bi-arrow-repeat animate-spin"></i> Cargando más seguidores...
    </div>
</section>
