@extends('plumr.layout.app')

@section('main')
<x-main>
    <h1>Cambiar foto de perfil</h1>
    <hr>
    <form action="{{ route('account.update_photo', [$user]) }}" method="post">
        @csrf
        @method('PUT')

        <!--  For single file upload  -->
        <input type="file" name="avatar" required/>
        <p class="help-block">{{ $errors->first('avatar') }}</p>

        <!--  For multiple file uploads  -->
        <input type="file" name="gallery[]" multiple required/>
        <p class="help-block">{{ $errors->first('gallery.*') }}</p>

        <button type="submit">Submit</button>
    </form>

    <script>
        // Set default FilePond options
        FilePond.setOptions({
            server: {
                url: "{{ config('filepond.server.url') }}",
                headers: {
                    'X-CSRF-TOKEN': "{{ @csrf_token() }}",
                }
            }
        });

        // Create the FilePond instance
        FilePond.create(document.querySelector('input[name="avatar"]'));
        FilePond.create(document.querySelector('input[name="gallery[]"]'), {chunkUploads: true});
    </script>
</x-main>
@endsection
