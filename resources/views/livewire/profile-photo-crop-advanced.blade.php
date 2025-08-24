<div x-data="photoCropper()"
    x-init="$watch('croppedPhoto', value => @this.set('croppedPhoto', value))"
    class="space-y-6 max-w-4xl mx-auto p-6 bg-white rounded-2xl shadow-lg">

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
            <a href="{{ route('posts.index', ['user' => $user]) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg shadow transition">
            ← Volver
            </a>
        </div>
    </div>

    <!-- Carga de imagen -->
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Canvas principal -->
        <div class="relative w-full border border-gray-300 rounded-xl overflow-hidden shadow-inner bg-gray-50" style="width: 400px;height:400px;">
            <canvas id="canvas" width="400" height="400"></canvas>

            <!-- Caja azul -->
            <div class="absolute border-2 border-blue-500 bg-blue-200 bg-opacity-20 rounded"
                x-show="image"
                :style="'left:' + cropBox.x + 'px; top:' + cropBox.y + 'px; width:' + cropBox.w + 'px; height:' + cropBox.h + 'px;'"
                @mousedown="startDrag($event)">

                <!-- Esquinas de redimensionamiento -->
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

        <!-- Vista previa -->
        <div class="flex flex-col items-center justify-start gap-4">
            <span class="font-semibold text-gray-700">Vista previa</span>
            <canvas id="preview" width="150" height="150" class="border rounded-xl shadow-md"></canvas>
            <input type="file" accept="image/*" @change="loadImage"
                class="mt-2 p-2 border rounded-full cursor-pointer bg-blue-50 hover:bg-blue-100 transition">
        </div>
    </div>

    <!-- Botón de acción -->
    <div class="flex justify-end">
        <button @click.prevent="cropImage"
            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-full shadow-lg transition-all transform hover:scale-105">
            Guardar foto
        </button>
    </div>

    <input type="hidden" wire:model="croppedPhoto" x-model="croppedPhoto" x-ref="croppedPhoto">

</div>

<script>
function photoCropper() {
    return {
        canvas: null,
        ctx: null,
        image: null,
        preview: null,
        croppedPhoto: @entangle('croppedPhoto'),
        dragging: false,
        resizing: false,
        resizeDir: null,
        startX: 0,
        startY: 0,
        cropBox: { x: 50, y: 50, w: 150, h: 150 },

        loadImage(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                this.image = new Image();
                this.image.onload = () => {
                    this.canvas = document.getElementById('canvas');
                    this.ctx = this.canvas.getContext('2d');
                    this.preview = document.getElementById('preview').getContext('2d');
                    this.draw();
                };
                this.image.src = e.target.result;
            };
            reader.readAsDataURL(file);
            window.addEventListener('mousemove', e => this.onMove(e));
            window.addEventListener('mouseup', () => this.stopAction());
        },

        draw() {
            if (!this.ctx) return;
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            this.ctx.drawImage(this.image, 0, 0, this.canvas.width, this.canvas.height);
            this.updatePreview();
        },

        startDrag(e) { this.dragging = true; this.startX = e.clientX; this.startY = e.clientY; },
        startResize(e, dir) { this.resizing = true; this.resizeDir = dir; this.startX = e.clientX; this.startY = e.clientY; },

        onMove(e) {
            if (this.dragging) {
                const dx = e.clientX - this.startX, dy = e.clientY - this.startY;
                this.cropBox.x += dx; this.cropBox.y += dy;
                this.startX = e.clientX; this.startY = e.clientY;
                this.cropBox.x = Math.max(0, Math.min(this.cropBox.x, this.canvas.width - this.cropBox.w));
                this.cropBox.y = Math.max(0, Math.min(this.cropBox.y, this.canvas.height - this.cropBox.h));
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
                this.cropBox.x = Math.max(0, this.cropBox.x); this.cropBox.y = Math.max(0, this.cropBox.y);
                if (this.cropBox.x + this.cropBox.w > this.canvas.width) this.cropBox.w = this.canvas.width - this.cropBox.x;
                if (this.cropBox.y + this.cropBox.h > this.canvas.height) this.cropBox.h = this.canvas.height - this.cropBox.y;
                this.startX = e.clientX; this.startY = e.clientY;
                this.draw();
            }
        },

        stopAction() { this.dragging = false; this.resizing = false; },
        updatePreview() {
            if (!this.preview) return;
            const { x, y, w, h } = this.cropBox;
            this.preview.clearRect(0, 0, 150, 150);
            this.preview.drawImage(this.canvas, x, y, w, h, 0, 0, 150, 150);
        },

        cropImage() {
            const { x, y, w, h } = this.cropBox;
            const tmpCanvas = document.createElement('canvas');
            tmpCanvas.width = w; tmpCanvas.height = h;
            tmpCanvas.getContext('2d').drawImage(this.canvas, x, y, w, h, 0, 0, w, h);
            this.croppedPhoto = tmpCanvas.toDataURL('image/png');
            @this.save();
        }
    }
}
</script>
