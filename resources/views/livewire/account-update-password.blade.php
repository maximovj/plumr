<div x-data="{ open: false, openModal() { this.open = true; Livewire.emit('executeResetForm'); } }" wire:id="{{ $this->id }}" x-cloak>
    <button @click="openModal"
        class="w-full text-left px-4 py-2 hover:bg-gray-100 ">
        Cambiar contraseña
    </button>

    <div
    x-show="open"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div class="relative bg-white p-6 rounded-lg shadow-lg w-96 space-y-4">
        @if (session()->has('message'))
            <div class="p-2 bg-green-100 text-green-700 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <!-- Botón cerrar -->
        <button wire:click="resetForm" @click="open = false;"
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            <i class="bi bi-x-lg"></i>
        </button>

        <div>
            <label class="block font-medium text-gray-700">Contraseña actual</label>
            <input type="password" wire:model.defer="current_password"
                class="w-full p-2 border rounded-lg">
            @error('current_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium text-gray-700">Nueva contraseña</label>
            <input type="password" wire:model.defer="password"
                class="w-full p-2 border rounded-lg">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            <p class="text-xs text-gray-500 mt-1">Completa solo si deseas cambiar la contraseña.</p>
        </div>

        <div>
            <label class="block font-medium text-gray-700">Confirmar contraseña</label>
            <input type="password" wire:model.defer="password_confirmation"
                class="w-full p-2 border rounded-lg">
            <p class="text-xs text-gray-500 mt-1">Completa solo si deseas cambiar la contraseña.</p>
        </div>

        <div class="">
            <button wire:click="resetForm" @click="open = false;" class=" bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg">
                Cancelar
            </button>
            <button wire:click="updatePassword"
                    class=" bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg">
                Modificar contraseña
            </button>
        </div>
        </div>
    </div>
</div>


