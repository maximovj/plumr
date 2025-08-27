<div x-data="{
        open: @entangle('showModal'),
        closing: false,
        close(type) {
            this.closing = true;

            // Animaciones opcionales (solo para visual)
            this.$refs.overlay.classList.remove('animate__fadeIn');
            this.$refs.modal.classList.remove('animate__zoomIn','animate__bounceIn','animate__slideInDown');

            if(type === 'cancel') {
                this.$refs.modal.classList.add('animate__slideOutDown');
            } else if(type === 'confirm') {
                this.$refs.modal.classList.add('animate__zoomOut','animate__fadeOut');
            }

            // Cerrar inmediatamente
            this.open = false;
            this.closing = false;

            this.$refs.modal.classList.remove('animate__zoomOut','animate__fadeOut','animate__slideOutDown');
            this.$refs.overlay.classList.remove('animate__fadeOut');
        }
    }"
    x-init="
    $watch('open', value => @this.set('showModal', value));
    "
    x-cloak
>
    <!-- Overlay -->
    <div x-show="open"
         x-ref="overlay"
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 animate__animated animate__faster animate__fadeIn">

        <!-- Modal -->
        <div x-ref="modal"
            class="
            flex flex-col justify-between items-stretch
            bg-white rounded-lg shadow-lg p-6 w-96 animate__animated animate__faster animate__zoomIn"
            style="height: 80vh !important;">

            <!-- -->
            <section>
                <h4 class="font-semibold py-2">Lista de álbum</h4>

                <div class="block" x-show="$wire.albums_selected.length === 0">
                    <i class="bi bi-exclamation-triangle-fill text-yellow-500 text-2xl mr-2"></i>
                    <span class="font-bold">El elemento estará disponible en la página multimedias</span>
                </div>

                <div class="w-full h-96 overflow-auto relative">
                    <ul class="space-y-4">
                        <template x-for="album in $wire.albums" :key="album['id']">
                            <li class="flex justify-start items-center gap-4 w-full">
                                <input type="checkbox" name="album[]" :value="album['id']"
                                x-model="$wire.albums_selected"
                                class="w-6 h-6 inline">
                                <span x-text="album['title']" class="block"></span>
                            </li>
                        </template>
                    </ul>

                </div>
            </section>

            <div class="mt-6 flex justify-end space-x-2">
                <button @click="close('cancel')"
                        class="px-4 py-2 bg-gray-300 rounded">
                    Cancelar
                </button>
                <button
                    wire:click="moveToAlbum"
                    x-on:click="
                        //$wire.moveToAlbum(albums_selected);
                        close('confirm');
                    "
                        class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>
