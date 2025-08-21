@props([
    'name' => 'status',
    'label' => 'Estados emocionales',
    'placeholder' => 'Escribe y presiona Enter',
    'inputClass' => 'w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 p-2',
    'suggestions' => [], // NUEVO: sugerencias para datalist
])

<div x-data="{
        status: {{ json_encode(old($name, [])) }},
        newState: '',
        addState() {
            let value = this.newState.trim();
            if (value !== '' && !this.status.includes(value)) {
                this.status.push(value);
            }
            this.newState = '';
        }
    }" class="flex flex-col gap-2">

    <!-- Label -->
    <label class="text-sm font-medium text-gray-700" for="{{ $name }}">{{ $label }}</label>

    <!-- Lista de estados -->
    <div class="flex flex-wrap gap-2 mb-2">
        <template x-for="(s, index) in status" :key="index">
            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs flex items-center gap-1">
                <span x-text="s"></span>
                <button type="button"
                        @click="status.splice(index, 1)"
                        class="text-red-500 font-bold hover:text-red-700 transition">×</button>
                <!-- Hidden input para enviar el arreglo al backend -->
                <input type="hidden" :name="'{{ $name }}[]'" :value="s">
            </span>
        </template>
    </div>

    <!-- Input con datalist de sugerencias -->
    <input type="text"
           x-model="newState"
           placeholder="{{ $placeholder }}"
           list="list-{{ $name }}"
           @keydown.enter.prevent="addState()"
           class="{{ $inputClass }}">

    <datalist id="list-{{ $name }}">
        @foreach($suggestions as $item)
            <option value="{{ $item }}"></option>
        @endforeach
    </datalist>

    <!-- Mensaje de error -->
    @error($name)
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
