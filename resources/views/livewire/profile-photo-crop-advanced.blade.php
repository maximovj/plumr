<div x-data="photoCropper()"
    x-init="$watch('croppedPhoto', value => @this.set('croppedPhoto', value))"
    class="space-y-4">

    <div>
        <span class="block font-bold mb-2">Foto de perfil actual</span>
        <img src="{{ $user->profile->photo_url }}" alt="Foto de usuario"
                        class="w-28 h-28 rounded-full border-4 border-white shadow-lg cursor-pointer hover:scale-105 transition transform">
    </div>

    <input type="file" accept="image/*" @change="loadImage" class="border p-2 rounded w-full">

    <div class="flex gap-4">
        <!-- Canvas principal -->
        <div class="relative border border-gray-300 w-[400px] h-[400px]">
            <canvas id="canvas" width="400" height="400" class="bg-gray-100"></canvas>

            <!-- Caja azul -->
            <div class="absolute border-2 border-blue-500 bg-blue-200 bg-opacity-20"
                x-show="image"
                :style="'left:' + cropBox.x + 'px; top:' + cropBox.y + 'px; width:' + cropBox.w + 'px; height:' + cropBox.h + 'px;'"
                @mousedown="startDrag($event)">

                <!-- Esquinas de redimensionamiento -->
                <div class="absolute w-3 h-3 bg-blue-500 cursor-nw-resize -top-1 -left-1"
                    @mousedown.stop="startResize($event, 'nw')"></div>
                <div class="absolute w-3 h-3 bg-blue-500 cursor-ne-resize -top-1 -right-1"
                    @mousedown.stop="startResize($event, 'ne')"></div>
                <div class="absolute w-3 h-3 bg-blue-500 cursor-sw-resize -bottom-1 -left-1"
                    @mousedown.stop="startResize($event, 'sw')"></div>
                <div class="absolute w-3 h-3 bg-blue-500 cursor-se-resize -bottom-1 -right-1"
                    @mousedown.stop="startResize($event, 'se')"></div>
            </div>
        </div>

        <!-- Vista previa del recorte -->
        <div>
            <canvas id="preview" width="150" height="150" class="border"></canvas>
        </div>
    </div>

    <input type="hidden" wire:model="croppedPhoto" x-model="croppedPhoto" x-ref="croppedPhoto">

    <button @click.prevent="cropImage" class="bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded">
        Actualizar foto de perfil
    </button>
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
            reader.onload = (e) => {
                this.image = new Image();
                this.image.onload = () => {
                    this.canvas = document.getElementById('canvas');
                    this.ctx = this.canvas.getContext('2d');
                    this.preview = document.getElementById('preview').getContext('2d');
                    this.draw();
                }
                this.image.src = e.target.result;
            };
            reader.readAsDataURL(file);

            window.addEventListener('mousemove', (e) => this.onMove(e));
            window.addEventListener('mouseup', () => this.stopAction());
        },

        draw() {
            if (!this.ctx) return;
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            this.ctx.drawImage(this.image, 0, 0, this.canvas.width, this.canvas.height);
            this.updatePreview();
        },

        startDrag(e) {
            this.dragging = true;
            this.startX = e.clientX;
            this.startY = e.clientY;
        },

        startResize(e, dir) {
            this.resizing = true;
            this.resizeDir = dir;
            this.startX = e.clientX;
            this.startY = e.clientY;
        },

        onMove(e) {
            if (this.dragging) {
                const dx = e.clientX - this.startX;
                const dy = e.clientY - this.startY;

                this.cropBox.x += dx;
                this.cropBox.y += dy;

                this.startX = e.clientX;
                this.startY = e.clientY;

                // límites
                this.cropBox.x = Math.max(0, Math.min(this.cropBox.x, this.canvas.width - this.cropBox.w));
                this.cropBox.y = Math.max(0, Math.min(this.cropBox.y, this.canvas.height - this.cropBox.h));

                this.draw();
            }

            if (this.resizing) {
                const dx = e.clientX - this.startX;
                const dy = e.clientY - this.startY;

                switch (this.resizeDir) {
                    case 'nw':
                        this.cropBox.x += dx;
                        this.cropBox.y += dy;
                        this.cropBox.w -= dx;
                        this.cropBox.h -= dy;
                        break;
                    case 'ne':
                        this.cropBox.y += dy;
                        this.cropBox.w += dx;
                        this.cropBox.h -= dy;
                        break;
                    case 'sw':
                        this.cropBox.x += dx;
                        this.cropBox.w -= dx;
                        this.cropBox.h += dy;
                        break;
                    case 'se':
                        this.cropBox.w += dx;
                        this.cropBox.h += dy;
                        break;
                }

                // tamaño mínimo
                this.cropBox.w = Math.max(50, this.cropBox.w);
                this.cropBox.h = Math.max(50, this.cropBox.h);

                // límites dentro del canvas
                this.cropBox.x = Math.max(0, this.cropBox.x);
                this.cropBox.y = Math.max(0, this.cropBox.y);
                if (this.cropBox.x + this.cropBox.w > this.canvas.width) {
                    this.cropBox.w = this.canvas.width - this.cropBox.x;
                }
                if (this.cropBox.y + this.cropBox.h > this.canvas.height) {
                    this.cropBox.h = this.canvas.height - this.cropBox.y;
                }

                this.startX = e.clientX;
                this.startY = e.clientY;

                this.draw();
            }
        },

        stopAction() {
            this.dragging = false;
            this.resizing = false;
        },

        updatePreview() {
            if (!this.preview) return;
            const { x, y, w, h } = this.cropBox;
            this.preview.clearRect(0, 0, 150, 150);
            this.preview.drawImage(this.canvas, x, y, w, h, 0, 0, 150, 150);
        },

        cropImage() {
            const { x, y, w, h } = this.cropBox;
            const tmpCanvas = document.createElement('canvas');
            tmpCanvas.width = w;
            tmpCanvas.height = h;
            const tmpCtx = tmpCanvas.getContext('2d');
            tmpCtx.drawImage(this.canvas, x, y, w, h, 0, 0, w, h);

            this.croppedPhoto = tmpCanvas.toDataURL('image/png');

            /*
            // Actualiza propiedad 'croppedPhoto' del componente Livewire
            //@this.set('croppedPhoto', this.croppedPhoto);
            //@this.croppedPhoto = this.croppedPhoto;
            //Livewire.emit('updateCroppedPhoto', this.croppedPhoto);
            */

            @this.save();
        }
    }
}
</script>
