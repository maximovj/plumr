<div x-data="{ height: {{ $height }} }" class="relative space-y-1">

    <input type="range" x-model="height" min="0" max="840" step="5" />

    <!-- Toolbar Quill -->
    <div wire:ignore id="toolbar-{{ $editorId }}" class="flex flex-wrap gap-2 p-2 bg-gray-100 rounded border shadow-sm">
        <!-- Encabezados -->
        <select class="ql-header border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-indigo-400">
            <option value="1">H1</option>
            <option value="2">H2</option>
            <option value="3">H3</option>
            <option selected>Normal</option>
        </select>

        <!-- Tamaño de fuente -->
        <select class="ql-size border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-indigo-400">
            <option value="small">Pequeño</option>
            <option selected>Normal</option>
            <option value="large">Grande</option>
            <option value="huge">Enorme</option>
        </select>

        <!-- Fuente -->
        <select class="ql-font border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-indigo-400">
            <option selected>Sans</option>
            <option value="serif">Serif</option>
            <option value="monospace">Monospace</option>
        </select>

        <!-- Colores -->
        <button class="ql-color ql-picker ql-color-picker border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-indigo-400"></button>
        <button class="ql-background ql-picker ql-color-picker border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-indigo-400"></button>

        <!-- Formato básico -->
        <button class="ql-clean px-2 py-1 rounded border hover:bg-gray-200 transition">🧹</button>
        <button class="ql-bold px-2 py-1 rounded border hover:bg-gray-200 transition">B</button>
        <button class="ql-italic px-2 py-1 rounded border hover:bg-gray-200 transition">I</button>
        <button class="ql-underline px-2 py-1 rounded border hover:bg-gray-200 transition">U</button>
        <button class="ql-strike px-2 py-1 rounded border hover:bg-gray-200 transition">S</button>

        <!-- Sub/superíndice -->
        <button class="ql-script" value="sub" class="px-2 py-1 rounded border hover:bg-gray-200 transition">Sub</button>
        <button class="ql-script" value="super" class="px-2 py-1 rounded border hover:bg-gray-200 transition">Sup</button>

        <!-- Listas y sangría -->
        <button class="ql-list" value="ordered" class="px-2 py-1 rounded border hover:bg-gray-200 transition">OL</button>
        <button class="ql-list" value="bullet" class="px-2 py-1 rounded border hover:bg-gray-200 transition">UL</button>
        <button class="ql-indent" value="-1" class="px-2 py-1 rounded border hover:bg-gray-200 transition">-</button>
        <button class="ql-indent" value="+1" class="px-2 py-1 rounded border hover:bg-gray-200 transition">+</button>

        <!-- Alineación -->
        <button class="ql-align border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-indigo-400">
            <option selected></option>
            <option value="center">Centro</option>
            <option value="right">Derecha</option>
            <option value="justify">Justificado</option>
        </button>

        <!-- Bloques especiales -->
        <button class="ql-blockquote px-2 py-1 rounded border hover:bg-gray-200 transition">❝</button>
        <button class="ql-code-block px-2 py-1 rounded border hover:bg-gray-200 transition">{ }</button>

        <!-- Multimedia -->
        <button class="ql-link px-2 py-1 rounded border hover:bg-gray-200 transition">🔗</button>
        <button class="ql-image px-2 py-1 rounded border hover:bg-gray-200 transition">🖼</button>
        <button class="ql-video px-2 py-1 rounded border hover:bg-gray-200 transition">🎥</button>
    </div>

    <!-- Editor Quill -->
    <div wire:ignore :style="`height: ${height}px`" id="quill-editor-{{ $editorId }}" class="border rounded shadow-sm bg-white p-2 overflow-y-auto">
        {!! $content !!}
    </div>
    {{-- Input oculto que se enviará en el form --}}
    <input type="hidden" name="{{ $fieldName }}" value="{{ $content }}">

</div>

<script>
document.addEventListener('livewire:load', function () {
    Quill.register('modules/imageResize', QuillResizeModule);

    // Inicializar Quill con toolbar personalizada
    var toolbarOptions = [
        [{ header: [1, 2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        ['link', 'image'],
        [{ list: 'ordered' }, { list: 'bullet' }]
    ];

    var quill = new Quill('#quill-editor-{{ $editorId }}', {
        theme: 'snow',
        placeholder: '{{ $placeholder }}',
        readOnly: false,
        modules: {
            imageResize: {
                displaySize: true
            },
            counter: true,
            toolbar: '#toolbar-{{ $editorId }}',
        }
    });

    quill.root.innerHTML = @json($content);

    quill.on('text-change', function() {
        // Actualiza propiedad 'content' del componente Livewire
        @this.set('content', quill.root.innerHTML);
        @this.content = quill.root.innerHTML;

        // Emitimos un evento global para que Alpine u otros componentes lo escuchen
        Livewire.emit('quillContentUpdated', '{{ $editorId }}', quill.root.innerHTML);

        // Opcional: actualiza input oculto para envío en forms
        document.querySelector("input[name='{{ $fieldName }}']").value = quill.root.innerHTML;
    });

    // Interceptar la inserción de imágenes
    quill.getModule('toolbar').addHandler('image', () => {
        let input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = () => {
            let file = input.files[0];
            let reader = new FileReader();
            reader.onload = () => {
                Livewire.emit('uploadImage', reader.result, '{{ $editorId }}');
            };
            reader.readAsDataURL(file);
        };
    });


    // Recibir contenido desde un componente padre usando ()
    Livewire.on('updateQuillContent', content => {
        quill.root.innerHTML = content;
    });

    // Recibir URL de imagen subida y agregar a Quill
    Livewire.on('imageUploaded', (url,  editorId) => {
        if (editorId !== '{{ $editorId }}') return;

        let range = quill.getSelection();
        quill.insertEmbed(range.index, 'image', url);
    });
});
</script>
