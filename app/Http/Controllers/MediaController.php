<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        //
        $medias = $this->getMedias($user);
        return view("plumr.account.medias.index", [
            'user' => $user,
            'medias' => $medias,
        ]);
    }

    protected function getMedias(User $user)
    {
         if(isfollower($user)) { // Verificar si usuario autenticado es seguidor
            return $user->medias()
            ->whereIn('visibility', ['public', 'followers_only'])
            ->latest()->take(30)->get();
        }elseif(auth()->user()->id !== $user->id) { // Verificar si usuario no es el mismo autenticado
            return $user->medias()
            ->whereIn('visibility', ['public'])
            ->latest()->take(30)->get();
        } else {
            return $user->medias()
            ->latest('updated_at')->take(30)->get();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        //
        return view("plumr.account.medias.form", [
            'user' => $user,
            'media' => new Media(),
            'action' => 'create',
            'route' => route('medias.store', [$user]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        //
        // Validar formulario
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:3'],
            'description' => ['nullable', 'string', 'min:3'],
            'visibility' => ['nullable', 'in:private,public,followers_only'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:20'],
            'media' => 'mimes:jpg,jpeg,png,gif,mp4,mov,avi,mp3,wav,pdf|max:20480',
        ]);

        $new_media = new Media();
        $new_media->fill($validated);
        $new_media->user_id = auth()->user()->id;
        $new_media->slug = Str::slug($new_media->title.'-'.now()->format('d-M-Y H:m:s'));

        if($request->hasFile('media')) {
            $file = $request->file('media');

            $path = $file->store('medias/source_'.auth()->user()->id, 'public');
            $mime_type = $file->getClientMimeType();
            $type = '';

            if (str_starts_with($mime_type, 'image/')) {
                $type = 'photo';
            } elseif (str_starts_with($mime_type, 'video/')) {
                $type = 'video';
            } elseif (str_starts_with($mime_type, 'audio/')) {
                $type = 'audio';
            } elseif ($mime_type === 'application/pdf') {
                $type = 'pdf';
            } else {
                $type = 'other'; // opcional para archivos no soportados
            }

            $new_media->file_path = $path;
            $new_media->mime_type = $mime_type;
            $new_media->type = $type;
            toastr()->addSuccess('Portada cargado correctamente');
        }

        $new_media->save();

        toastr()->addSuccess('Multimedia creado correctamente');

        sweetalert()
        ->showConfirmButton(
           true,
            "Enterado",
            "btn btn-success",
            "Enterado"
        )
        ->addSuccess('Multimedia creado correctamente');

        return redirect()->route('medias.index', [$user]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Media $media)
    {
        //
        return view("plumr.account.medias.form", [
            'user' => $user,
            'media' => $media,
            'action' => 'edit',
            'route' => route('medias.update', [$user, $media]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Media $media)
    {
        //
        // Validar formulario
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:3'],
            'description' => ['nullable', 'string', 'min:3'],
            'visibility' => ['nullable', 'in:private,public,followers_only'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:20'],
            'media' => 'mimes:jpg,jpeg,png,gif,mp4,mov,avi,mp3,wav,pdf|max:20480',
        ]);

        $old_media = (object) $media->toArray();
        $new_media = $media;
        $new_media->fill($validated);

        if($request->hasFile('media')) {
            $file = $request->file('media');

            // Eliminar portada del artículo antiguo
            if($old_media->file_path && Storage::disk('public')->exists($old_media->file_path)) {
                Storage::disk('public')->delete($old_media->file_path);
                toastr()->addInfo('Portada anterior eliminado correctamente');
            }


            $path = $file->store('medias/source_'.auth()->user()->id, 'public');
            $mime_type = $file->getClientMimeType();
            $type = '';

            if (str_starts_with($mime_type, 'image/')) {
                $type = 'photo';
            } elseif (str_starts_with($mime_type, 'video/')) {
                $type = 'video';
            } elseif (str_starts_with($mime_type, 'audio/')) {
                $type = 'audio';
            } elseif ($mime_type === 'application/pdf') {
                $type = 'pdf';
            } else {
                $type = 'other'; // opcional para archivos no soportados
            }

            $new_media->file_path = $path;
            $new_media->mime_type = $mime_type;
            $new_media->type = $type;
            toastr()->addSuccess('Portada cargado correctamente');
        }

        $new_media->save();

        toastr()->addSuccess('Multimedia actualizado correctamente');

        sweetalert()
        ->showConfirmButton(
           true,
            "Enterado",
            "btn btn-success",
            "Enterado"
        )
        ->addSuccess('Multimedia actualizado correctamente');

        return redirect()->route('medias.index', [$user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
