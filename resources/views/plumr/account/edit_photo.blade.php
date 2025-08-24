@extends("plumr.layout.app")

@section('main')
<x-main>
    <form method="POST" action="{{ route('account.edit_photo', [$user]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="flex flex-col justify-between align-middle content-center space-y-4">
        <!--- Ver imagen previa --->
        <section class="bg-gray-100 border-2 border-gray-100 shadow rounded relative"
        style="width:240px !important; height:240px !important;">
            <div class="img-crop-preview  w-full h-full" />
            @if($user->profile->photo)
            <img id="photo" src="{{ asset('storage/'.$user->profile->photo) }}" alt="" class="w-full h-full" />
            @else
            <img id="photo" src="{{ asset('img/user_default.png') }}" alt="" class="w-full h-full" />
            @endif
            {{-- Acciones --}}
            <div class="absolute left-2 bottom-2 w-full h-1/5">
                <button
                x-data
                x-on:click="cropWithCropper()"
                id="btn-img-crop"
                type="button"
                class="hidden z-20 gap-1 justify-between items-center content-center px-2 py-2 bg-gray-100 rounded border-2 border-gray-200">
                    <span style="display:block; font-size:24px; width:24px; height:24px;" >
                    <svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill="#000000" d="M256 768h672a32 32 0 1 1 0 64H224a32 32 0 0 1-32-32V96a32 32 0 0 1 64 0v672z"></path><path fill="#000000" d="M832 224v704a32 32 0 1 1-64 0V256H96a32 32 0 0 1 0-64h704a32 32 0 0 1 32 32z"></path></g></svg>
                    </span>
                    <span>Cortar</span>
                </button>
            </div>
        </section>

        <!-- Input File -->
        <section>
            <input type="file"
               class="filepond"
               name="photo"
               data-max-file-size="5MB"
               data-max-files="1" />
        </section>

        <!-- Modal de Crop -->
        <div id="cropModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-4 rounded">
                <img id="cropImage" class="max-w-full max-h-[500px]" />
                <div class="mt-2 flex justify-end gap-2">
                    <button type="button" id="cropCancel" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                    <button type="button" id="cropConfirm" class="px-4 py-2 bg-blue-500 text-white rounded">Recortar</button>
                </div>
            </div>
        </div>

        <section>
            <!-- Botón Subir -->
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded mt-4">
                Cambiar foto de perfil
            </button>
        </section>
        </div>
    </form>
</x-main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputElement = document.querySelector('.filepond');

    if (!window.FilePond || !inputElement) {
        console.error("⚠️ FilePond no está disponible o no encontró el input.");
        return;
    }

    FilePond.create(inputElement, {
        allowImagePreview: false,
        allowMultiple: false,
        instantUpload: true,
        allowFileSizeValidation: true,
        allowImageValidateSize: true,
        maxFiles: 1,
        acceptedFileTypes: ['image/jpeg', 'image/png'],
        labelIdle: `Arrastra y suelta tu imagen o <span class="filepond--label-action">Explora</span>`,
        labelFileTypeNotAllowed: 'Solo se permiten imágenes JPEG o PNG',
        imagePreviewHeight: 460,
        stylePanelLayout: 'compact',
        styleButtonRemoveItemPosition: 'right',

        onaddfile: (error, fileItem) => {
            if(error) return;

            const img_preview = document.getElementById('photo');
            img_preview.src = URL.createObjectURL(fileItem.file);

            img_preview.onload = () => {
                const btn_img_crop = document.getElementById('btn-img-crop');
                btn_img_crop.classList.remove('hidden');
                btn_img_crop.classList.add('flex');
            }

        },

        server: {
            url: "{{ config('filepond.server.url') }}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            }
        }
    });

});

function cropWithCropper()
    {
        const cropModal = document.getElementById('cropModal');
        const cropImage = document.getElementById('cropImage');
        const cropCancel = document.getElementById('cropCancel');
        const cropConfirm = document.getElementById('cropConfirm');

        const preview = document.getElementById('photo');

        cropImage.src = preview.src;
        cropModal.classList.remove('hidden');

        // Esperar a que la imagen cargue
        cropImage.onload = () => {
            let cropper = new Cropper(cropImage, { // PASAR EL ELEMENTO IMG, no selector
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 1,
                preview: '.img-crop-preview',
            });

            cropCancel.onclick = () => {
                cropper.destroy();
                cropModal.classList.add('hidden');
                //fileItem.remove();
            };

            cropConfirm.onclick = () => {
                // Aquí usamos el método correcto
                cropper.getCroppedCanvas().toBlob((blob) => {
                    //fileItem.setFile(blob); // reemplaza el archivo en FilePond
                    cropper.destroy();
                    cropModal.classList.add('hidden');
                }, 'image/png');
            };
        };

    }
</script>
@endpush
