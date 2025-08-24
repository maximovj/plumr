<div x-data="photoCropper()"
    x-init="$watch('croppedPhoto', value => @this.set('croppedPhoto', value))"
    class="max-w-3xl mx-auto p-6 bg-gradient-to-br from-gray-50 via-white to-gray-100 rounded-3xl shadow-2xl space-y-6">

    <!-- Header con perfil -->
    <div class="flex justify-between items-center space-x-4">
        <div class="flex items-center space-x-4">
            <div class="relative">
                <img src="{{ $user->profile->photo_url }}" alt="Foto de usuario"
                    class="w-24 h-24 rounded-full border-4 border-white shadow-xl cursor-pointer hover:scale-105 transition-transform">
                <div class="absolute bottom-0 right-0 w-7 h-7 bg-blue-500 rounded-full flex items-center justify-center text-white shadow-md hover:bg-blue-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4" />
                    </svg>
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $user->profile->fullname }}</h2>
                <h4 class="text-xs">{{ '@'.$user->username }}</h4>
                <p class="text-gray-500 text-xs">Actualiza tu foto de perfil</p>
            </div>
        </div>
        <div>
            <a href="{{ route('main_account', ['user' => $user]) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg shadow transition">
            ← Volver
            </a>
        </div>
    </div>

    <!-- Canvas y controles -->
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Canvas principal con overlay moderno -->
        <div class="relative w-full rounded-2xl overflow-hidden shadow-lg bg-gray-50" style="width: 400px;height: 400px;">
            <canvas id="canvas" width="400" height="400"></canvas>

            <div x-show="image"
                class="absolute border-2 border-blue-400 bg-blue-300 bg-opacity-20 rounded-xl cursor-move"
                :style="'left:' + cropBox.x + 'px; top:' + cropBox.y + 'px; width:' + cropBox.w + 'px; height:' + cropBox.h + 'px;'"
                @mousedown="startDrag($event)">
                <template x-for="dir in ['nw','ne','sw','se']">
                    <div :class="{
                        'nw': 'absolute w-5 h-5 bg-blue-500 cursor-nw-resize -top-2 -left-2 rounded-full',
                        'ne': 'absolute w-5 h-5 bg-blue-500 cursor-ne-resize -top-2 -right-2 rounded-full',
                        'sw': 'absolute w-5 h-5 bg-blue-500 cursor-sw-resize -bottom-2 -left-2 rounded-full',
                        'se': 'absolute w-5 h-5 bg-blue-500 cursor-se-resize -bottom-2 -right-2 rounded-full'
                    }[dir]" @mousedown.stop="startResize($event, dir)"></div>
                </template>
            </div>
        </div>

        <!-- Vista previa + input -->
        <div class="flex flex-col items-center gap-4">
            <span class="text-gray-600 font-medium">Vista previa</span>
            <canvas id="preview" width="150" height="150" class="border rounded-2xl shadow-md"></canvas>
            <label
                class="mt-2 cursor-pointer px-4 py-2 bg-gradient-to-r from-blue-400 to-blue-500 text-white font-semibold rounded-full shadow hover:from-blue-500 hover:to-blue-600 transition">
                Subir imagen
                <input type="file" accept="image/*" @change="loadImage" class="hidden">
            </label>
        </div>
    </div>

    <!-- Botón guardar -->
    <div class="flex justify-start">
        <button @click.prevent="cropImage"
            class="px-6 py-2 bg-gradient-to-r from-green-400 to-green-500 text-white rounded-full font-semibold shadow-lg hover:from-green-500 hover:to-green-600 transition transform hover:scale-105">
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
