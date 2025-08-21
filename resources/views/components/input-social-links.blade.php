@props([
    'name' => 'network_social',
    'label' => 'Redes sociales',
    'networks' => [
        // Definimos las redes sociales y placeholders por defecto
        'youtube' => 'Ingresa tu canal de YouTube',
        'twitter-x' => 'Ingresa tu cuenta de X',
        'linkedin' => 'Ingresa tu cuenta de LinkedIn',
        'link' => 'Ingresa un enlace externo',
    ],
    'values' => [], // Valores iniciales (ej: $article->network_social)
    'inputClass' =>
        'p-3 rounded-lg bg-blue-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm',
    'labelClass' => 'text-sm font-medium text-gray-700',
])

<section class="flex flex-col gap-2 w-full md:w-1/2">
    <label class="{{ $labelClass }}">{{ $label }}</label>
    <span class="text-xs text-gray-500">Escribe el enlace / URL (puede dejar en vacío)</span>

    @foreach ($networks as $key => $placeholder)
        @php
            $field = $name . '.' . (is_string($key) ? $key : 'link'.$key);
        @endphp

        <input type="text" name="{{ $name }}[{{ is_string($key) ? $key : 'link' . $key }}]"
            id="{{ $name }}_{{ is_string($key) ? $key : 'link' . $key }}"
            value="{{ old($name . '.' . (is_string($key) ? $key : 'link' . $key), $values[$key] ?? '') }}"
            placeholder="{{ $placeholder }}" autocomplete="off"
            class="{{ $inputClass }} {{ e_class($name . '.' . (is_string($key) ? $key : 'link' . $key)) }}" />

        @error($field)
            <p class="text-red-500 text-xs my-2">{{ $message }}</p>
        @enderror
    @endforeach
</section>
