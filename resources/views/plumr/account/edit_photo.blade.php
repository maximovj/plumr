@extends("plumr.layout.app")

@section('main')
<x-main>
    <form method="POST" action="{{ route('account.edit_photo', [$user]) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <input type="file" class="filepond" name="file" multiple
           data-max-file-size="20MB"
           data-max-files="5" />
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Subir</button>
</form>
</x-main>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const inputElement = document.querySelector('.filepond');
        if (window.FilePond && inputElement) {
            FilePond.create(inputElement, {
                server: {
                    url: "{{ config('filepond.server.url') }}",
                    headers: {
                        'X-CSRF-TOKEN': "{{ @csrf_token() }}",
                    }
                },
                acceptedFileTypes: [
                    'image/*',
                    'video/*',
                    'audio/*',
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ]
            });
        } else {
            console.error("⚠️ FilePond no está disponible o no encontró el input.");
        }
    });
</script>
@endpush
