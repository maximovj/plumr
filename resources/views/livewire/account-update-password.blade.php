<div class="space-y-4 border-2 border-gray-200 p-4 border-opacity-20 ">
    <span class="block text-black font-semibold">Modificar contraseña</span>

    @if (session()->has('message'))
        <div class="p-2 bg-green-100 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

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

    <button wire:click="updatePassword"
            class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg">
        Modificar contraseña
    </button>
</div>
