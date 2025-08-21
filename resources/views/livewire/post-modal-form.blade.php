<div x-data="{
    open: @entangle('showModal'),
    closing: false,
    close(type) {
        this.closing = true;

        // Animaciones opcionales (solo para visual)
        this.$refs.overlay.classList.remove('animate__fadeIn');
        this.$refs.modal.classList.remove('animate__zoomIn', 'animate__bounceIn', 'animate__slideInDown');

        if (type === 'cancel') {
            this.$refs.modal.classList.add('animate__slideOutDown');
        } else if (type === 'confirm') {
            this.$refs.modal.classList.add('animate__zoomOut', 'animate__fadeOut');
        }

        // Cerrar inmediatamente
        this.open = false;
        this.closing = false;

        this.$refs.modal.classList.remove('animate__zoomOut', 'animate__fadeOut', 'animate__slideOutDown');
        this.$refs.overlay.classList.remove('animate__fadeOut');
    }
}" x-init="$watch('open', value => @this.set('showModal', value))" x-cloak>
    <!-- Overlay -->
    <div x-show="open" x-ref="overlay"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 animate__animated animate__faster animate__fadeIn">

        <!-- Modal -->
        <div x-ref="modal"
            class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 flex flex-col animate__animated animate__fadeInDown"
            style="max-height: 90vh;">

            <!-- Botón cerrar -->
            <button @click="$wire.showModal = false"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                <i class="bi bi-x-lg"></i>
            </button>

            <!-- Encabezado -->
            <h3 class="text-lg font-semibold mb-4 shrink-0">
                {{ $mode === 'create' ? 'Crear publicación' : 'Editar publicación' }}
            </h3>

            <!-- Contenido scroll -->
            <div class="overflow-y-auto flex-1 pr-2">
                <form wire:submit.prevent="save" class="space-y-5">

                    <!-- Título -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Título</label>
                        <input type="text" wire:model="title" placeholder="Escribe un título"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 p-2">
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Contenido -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contenido</label>
                        @livewire(
                            'advanced-editor',
                            [
                                'editorId' => $editorId,
                                'placeholder' => 'Escribe un texto para contenido',
                                'fieldName' => 'content',
                                'content' => $content, // Propiedad de tu componente Livewire
                            ]
                        )
                        @error('content')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data>
                        <button
                        type="button"
                        class="bg-indigo-700 hover:bg-indigo-600 px-3 py-2 rounded text-white"
                        x-on:click="Livewire.emit('updateQuillContent', 'Hola mi nombre es {{ Auth::user()->profile->fullname ?? '' }}, sabes estoy pensando en lo siguiente: ')">
                            Di quien soy
                        </button>
                    </div>

                    <!-- Estados -->
                    <x-input-array name="status" label="Estados de ánimo"
                        placeholder="Escribe un estado y presiona Enter" :suggestions="['Feliz', 'Triste', 'Emocionado']"
                        inputClass="w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 p-2" />

                    <!-- Etiquetas -->
                    <x-input-array name="tags" label="Etiquetas" placeholder="Escribe una etiqueta y presiona Enter"
                        inputClass="w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 p-2" />

                    <!-- Enlaces externos -->
                    <x-input-social-links name="links" label="Enlaces externos" :networks="['Ingresa tu enlace externo', 'Ingresa tu enlace externo', 'Ingresa tu enlace externo']" />

                    <!-- Botón submit -->
                    <button type="submit"
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md transition flex items-center justify-center gap-2">
                        <i class="bi bi-save"></i>
                        {{ $mode === 'create' ? 'Crear publicación' : 'Actualizar publicación' }}
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
