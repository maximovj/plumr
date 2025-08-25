<div x-data="{ open: false }" x-cloak>

    <button @click="open = true"
        class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-500">
        Eliminar cuenta
    </button>

    <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-semibold text-red-600">¿Seguro que deseas eliminar tu cuenta?</h2>
            <p class="text-sm text-gray-600">Esta acción no se puede deshacer.</p>

            <input type="password" wire:model.defer="password"
                   placeholder="Confirma con tu contraseña"
                   class="w-full mt-3 p-2 border rounded-lg">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <div class="flex justify-end space-x-2 mt-4">
                <button @click="open = false"
                        class="px-4 py-2 bg-gray-300 rounded-lg">Cancelar</button>
                <button wire:click="deleteAccount"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg">Eliminar</button>
            </div>
        </div>
    </div>
</div>
