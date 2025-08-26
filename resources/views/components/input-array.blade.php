@props([
    'name' => 'status',
    'label' => 'Estados emocionales',
    'placeholder' => 'Escribe y presiona Enter',
    'inputClass' => 'w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 p-2',
    'labelClass' => 'text-sm font-medium text-gray-700',
    'suggestions' => [],
    'items' => [],
    'type' => 'wire',
])

@if($type == 'wire')
<div x-data="{
        items: @entangle($name).defer,
        newItem: '',
        addItem() {
            let value = this.newItem.trim();
            if (value !== '' && !this.items.includes(value)) {
                this.items.push(value);
            }
            this.newItem = '';
        },
        removeItem(index) {
            this.items.splice(index, 1);
        }
    }" class="flex flex-col gap-2">
@else
<div x-data="{
        items: {{ collect($items) }},
        newItem: '',
        addItem() {
            let value = this.newItem.trim();
            if (value !== '' && !this.items.includes(value)) {
                this.items.push(value);
            }
            this.newItem = '';
        },
        removeItem(index) {
            this.items.splice(index, 1);
        }
}" class="flex flex-col gap-2">
@endif

    <!-- Label -->
    <label class="{{ $labelClass }}" for="{{ $name }}">{{ $label }}</label>

    <!-- Lista de items -->
    <div class="flex flex-wrap gap-2 mb-2">
        <template x-for="(item, index) in items" :key="index">
            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs flex items-center gap-1">
                <span x-text="item"></span>
                <button type="button"
                        @click="removeItem(index)"
                        class="text-red-500 font-bold hover:text-red-700 transition">×</button>
            </span>
        </template>
    </div>

    <!-- Input con datalist de sugerencias -->
    <input type="text"
           x-model="newItem"
           placeholder="{{ $placeholder }}"
           list="list-{{ $name }}"
           @keydown.enter.prevent="addItem()"
           class="{{ $inputClass }}">

    @if($type == 'component')
        <template x-for="(item, index) in items" :key="index">
            <input type="hidden" :name="'{{ $name }}['+index+']'" :value="item">
        </template>
    @endif

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
