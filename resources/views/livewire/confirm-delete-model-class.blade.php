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
    x-init="$watch('open', value => @this.set('showModal', value))"
    x-cloak
>
    <!-- Overlay -->
    <div x-show="open"
         x-ref="overlay"
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 animate__animated animate__faster animate__fadeIn">

        <!-- Modal -->
        <div x-ref="modal"
             class="bg-white rounded-lg shadow-lg p-6 w-96 animate__animated animate__faster animate__zoomIn">

            <div class="flex items-center mb-4">
                <i class="bi bi-exclamation-triangle-fill text-yellow-500 text-2xl mr-2"></i>
                <h2 class="text-lg font-bold">{{ $title }}</h2>
            </div>

            <p class="text-gray-600">{{ $message }}</p>

            <div class="mt-6 flex justify-end space-x-2">
                <button type="button" @click="close('cancel')"
                        class="px-4 py-2 bg-gray-300 rounded">
                    Cancelar
                </button>
                <button type="button" wire:click="delete" @click="close('confirm')"
                        class="px-4 py-2 bg-red-600 text-white rounded">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>
