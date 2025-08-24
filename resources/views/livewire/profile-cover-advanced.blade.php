<div x-data="coverEditor()"
    x-init="
    $watch('croppedPhoto', value => @this.set('croppedPhoto', value));
    $watch('mode', () => { draw(); updatePreview(); });
    window.addEventListener('resize', () => { resizeCanvas(); });
    "
    class="space-y-6 max-w-full mx-auto p-6 bg-white rounded-2xl shadow-lg"
    x-cloak>

    <!-- Header -->
    <div class="flex justify-between items-center space-x-4">
        <div class="flex items-center space-x-4">
            <img src="{{ $user->profile->photo_url }}" alt="Foto de usuario"
                class="w-20 h-20 rounded-full border-4 border-blue-500 shadow-lg hover:scale-105 transition-transform cursor-pointer">
            <div>
                <h2 class="text-xl font-bold">{{ $user->profile->fullname }}</h2>
                <h4 class="text-xs">{{ '@'.$user->username }}</h4>
                <p class="text-gray-500">Actualiza tu foto de perfil</p>
            </div>
        </div>
        <div>
            <a href="{{ route('main_account', ['user' => $user]) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg shadow transition">
            ← Volver
            </a>
        </div>
    </div>

    <!-- Botón flotante central -->
    <div class="flex flex-col justify-center items-center content-center gap-4">
        <!-- Vista previa circular con glow -->
        <div class="flex flex-col items-center gap-3 w-40 cursor-pointer">
            <div class="relative w-20 h-20 rounded-full overflow-hidden shadow-lg transition-shadow duration-300 group">
                <label class="absolute inset-0 flex items-center justify-center cursor-pointer">
                    <div class="w-10 h-10 bg-blue-500 rounded-full shadow-md flex items-center justify-center hover:bg-blue-600 transform hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <input type="file" accept="image/*" @change="loadImage" class="hidden">
                </label>
            </div>
        </div>
        <p class="text-xs text-gray-500 text-center">
            Haz click en el centro para subir tu imagen.
        </p>
    </div>

    <!-- Vista previa final y Selector de modo  -->
    <div
    x-ref="actionsCover"
    x-show="showActions"
    class="flex flex-col items-center gap-6 w-full animate__animated animate__fast animate__zoomIn">
        <!-- Vista previa final -->
        <div class="flex flex-col items-center w-full lg:w-2/5 max-w-xl">
            <span class="text-sm font-semibold text-gray-800">Vista previa final</span>
            <div class="relative w-full" style="aspect-ratio: 628/160;">
                <canvas id="preview" class="absolute inset-0 w-full h-full border rounded-lg shadow-inner"></canvas>
            </div>
        </div>

        <!-- Selector de modo -->
        <div class="flex gap-4">
            <button @click="mode = 'auto'"
                :class="mode==='auto' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700'"
                class="px-4 py-2 rounded-lg shadow hover:bg-blue-600 hover:text-white transition">
                Ajustar automáticamente
            </button>
            <button @click="mode = 'crop'"
                :class="mode==='crop' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700'"
                class="px-4 py-2 rounded-lg shadow hover:bg-blue-600 hover:text-white transition">
                Recortar manualmente
            </button>
        </div>
    </div>

    <!-- Área principal -->
    <div
    x-ref="mainCover"
    x-show="showActions"
    wire:ignore
    class="flex flex-col items-center gap-6 w-full animate__animated animate__fast animate__fadeIn">

        <!-- Canvas responsive -->
        <div class="relative w-full max-w-5xl border border-gray-300 rounded-xl overflow-hidden shadow-inner bg-gray-50"
            x-ref="canvasWrapper"
            style="aspect-ratio: 1256/640;">
            <canvas id="canvas" class="absolute inset-0 w-full h-full"></canvas>

            <!-- Caja de recorte solo en modo crop -->
            <div class="absolute border-2 border-blue-500 bg-blue-200 bg-opacity-20 rounded"
                x-show="image && mode==='crop'"
                :style="`
                    left:${cropBox.x * scale}px;
                    top:${cropBox.y * scale}px;
                    width:${cropBox.w * scale}px;
                    height:${cropBox.h * scale}px;
                `"
                @mousedown="startDrag($event)">

                <template x-for="dir in ['nw','ne','sw','se']">
                    <div :class="{
                            'nw': 'absolute w-4 h-4 bg-blue-500 cursor-nw-resize -top-2 -left-2 rounded-full',
                            'ne': 'absolute w-4 h-4 bg-blue-500 cursor-ne-resize -top-2 -right-2 rounded-full',
                            'sw': 'absolute w-4 h-4 bg-blue-500 cursor-sw-resize -bottom-2 -left-2 rounded-full',
                            'se': 'absolute w-4 h-4 bg-blue-500 cursor-se-resize -bottom-2 -right-2 rounded-full'
                        }[dir]"
                        @mousedown.stop="startResize($event, dir)"></div>
                </template>
            </div>
        </div>


    </div>

    <!-- Botón guardar -->
    <div class="flex justify-start">
        <button @click.prevent="saveCover"
            class="bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-6 rounded">
            Guardar portada
        </button>
    </div>

    <input type="hidden" wire:model="croppedPhoto" x-model="croppedPhoto" x-ref="croppedPhoto">

</div>

<script>
function coverEditor() {
    return {
        canvas: null, ctx: null, preview: null,
        image: null, croppedPhoto: @entangle('croppedPhoto'),
        mode: 'auto',
        scale: 1,
        showActions: false,

        dragging: false, resizing: false, resizeDir: null,
        startX: 0, startY: 0,
        cropBox: { x: 50, y: 50, w: 628, h: 160 },

        loadImage(event) {
            this.showActions = false; // Ocultar acciones y pantalla principal

            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                this.image = new Image();
                this.showActions = true; // Mostrar acciones y pantalla principal

                this.image.onload = () => {
                    this.canvas = document.getElementById('canvas');
                    this.ctx = this.canvas.getContext('2d');
                    this.preview = document.getElementById('preview').getContext('2d');

                    // Escalar al wrapper
                    const wrapper = this.$refs.canvasWrapper;
                    this.canvas.width = wrapper.clientWidth;
                    this.canvas.height = wrapper.clientHeight;

                    // Escala respecto a tamaño base
                    this.scale = this.canvas.width / 1256;

                    this.draw();
                };
                this.image.src = e.target.result;
            };
            reader.readAsDataURL(file);
            window.addEventListener('mousemove', e => this.onMove(e));
            window.addEventListener('mouseup', () => this.stopAction());
        },

        draw() {
            if (!this.ctx || !this.image) return;
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            this.ctx.drawImage(this.image, 0, 0, this.canvas.width, this.canvas.height);
            this.updatePreview();
        },

        // CropBox controls
        startDrag(e) { this.dragging = true; this.startX = e.clientX; this.startY = e.clientY; },
        startResize(e, dir) { this.resizing = true; this.resizeDir = dir; this.startX = e.clientX; this.startY = e.clientY; },
        onMove(e) {
            if (this.mode !== 'crop') return;
            if (this.dragging) {
                const dx = e.clientX - this.startX, dy = e.clientY - this.startY;
                this.cropBox.x += dx; this.cropBox.y += dy;
                this.startX = e.clientX; this.startY = e.clientY;
                this.cropBox.x = Math.max(0, Math.min(this.cropBox.x, 1256 - this.cropBox.w));
                this.cropBox.y = Math.max(0, Math.min(this.cropBox.y, 640 - this.cropBox.h));
                this.draw();
            }
            if (this.resizing) {
                const dx = e.clientX - this.startX, dy = e.clientY - this.startY;
                switch (this.resizeDir) {
                    case 'nw': this.cropBox.x += dx; this.cropBox.y += dy; this.cropBox.w -= dx; this.cropBox.h -= dy; break;
                    case 'ne': this.cropBox.y += dy; this.cropBox.w += dx; this.cropBox.h -= dy; break;
                    case 'sw': this.cropBox.x += dx; this.cropBox.w -= dx; this.cropBox.h += dy; break;
                    case 'se': this.cropBox.w += dx; this.cropBox.h += dy; break;
                }
                this.cropBox.w = Math.max(50, this.cropBox.w); this.cropBox.h = Math.max(50, this.cropBox.h);
                this.startX = e.clientX; this.startY = e.clientY;
                this.draw();
            }
        },
        stopAction() { this.dragging = false; this.resizing = false; },

        updatePreview() {
            if (!this.preview) return;
            const { x, y, w, h } = this.mode === 'crop'
                ? this.cropBox
                : { x:0, y:0, w:1256, h:640 };

            this.preview.clearRect(0, 0, this.preview.canvas.width, this.preview.canvas.height);
            this.preview.drawImage(
                this.canvas,
                x * this.scale, y * this.scale, w * this.scale, h * this.scale,
                0, 0, this.preview.canvas.width, this.preview.canvas.height
            );
        },

        resizeCanvas() {
            const wrapper = this.$refs.canvasWrapper;
            if(this.canvas && this.mode == 'crop') {
                this.canvas.width = wrapper.clientWidth;
                this.canvas.height = wrapper.clientHeight;
                this.scale = this.canvas.width / 1256;
                this.draw();
            }
        },

        saveCover() {
            this.croppedPhoto = document.getElementById('preview').toDataURL('image/png');
            @this.save();
        }
    }
}
</script>
