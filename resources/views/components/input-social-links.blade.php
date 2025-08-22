@props([
    'name' => 'network_social',
    'label' => 'Redes sociales',
    'networks' => [
        'youtube' => 'Ingresa tu canal de YouTube',
        'twitter-x' => 'Ingresa tu cuenta de X',
        'linkedin' => 'Ingresa tu cuenta de LinkedIn',
        'link' => 'Ingresa un enlace externo',
    ],
    'values' => [], // Valores iniciales (ej: $article->network_social)
    'inputClass' => 'p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm',
    'labelClass' => 'text-sm font-medium text-gray-700',
])

<section class="flex flex-col gap-2 w-full md:w-1/2">
    <label class="{{ $labelClass }}">{{ $label }}</label>
    <span class="text-xs text-gray-500">Escribe el enlace / URL (puede dejar en vacío)</span>

    @foreach ($networks as $key => $placeholder)
        @php
            $fieldKey = is_string($key) ? $key : 'link'.$key;
            $fieldName = $name.'.'.$fieldKey;
        @endphp

        <input type="text"
            id="{{ $name }}_{{ $fieldKey }}"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            class="{{ $inputClass }} {{ e_class($fieldName) }}"
            wire:model.defer="{{ $name }}.{{ $fieldKey }}"
            value="{{ $values[$fieldKey] ?? '' }}"
        />

        @error($fieldName)
            <p class="text-red-500 text-xs my-2">{{ $message }}</p>
        @enderror
    @endforeach
</section>
