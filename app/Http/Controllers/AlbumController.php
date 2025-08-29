<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Album;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        //
        $albums = $this->getAlbums($user);
        return view("plumr.account.albums.index", [
            'user' => $user,
            'albums' => $albums,
        ]);
    }

    protected function getAlbums(User $user)
    {
         if(isfollower($user)) { // Verificar si usuario autenticado es seguidor
            return $user->albums()
            ->whereIn('visibility', ['public', 'followers_only'])
            ->latest()->take(30)->get();
        }elseif(auth()->user()->id !== $user->id) { // Verificar si usuario no es el mismo autenticado
            return $user->albums()
            ->whereIn('visibility', ['public'])
            ->latest()->take(30)->get();
        } else {
            return $user->albums()
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
        return view('plumr.account.albums.form', [
            'user' => $user,
            'album' => new Album(),
            'route' => route('albums.store', $user),
            'action' => 'create',
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
        // Validar formulario
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:3'],
            'description' => ['nullable', 'string', 'min:3'],
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5400'],
            'visibility' => ['nullable', 'in:private,public,followers_only'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:20'],
            'media.*' => 'mimes:jpg,jpeg,png,gif,mp4,mov,avi,mp3,wav,pdf|max:20480',
        ]);

        // Crear un nuevo álbum
        $new_album = new Album();
        $new_album->fill($validated);
        $new_album->user_id = auth()->user()->id;
        $new_album->slug = Str::slug($new_album->title. '-' . now()->format('d-m-Y H:m:s'));

        if($request->hasFile('cover')) {

            $path = $request->file('cover')->store('albums/cover', 'public');
            $new_album->cover = $path;
            toastr()->addSuccess('Portada cargado correctamente');
        }

        $new_album->save(); // Guardar álbum
        $new_album->folder = 'media/sources_'.$new_album->user_id.'/'.'album_'.$new_album->id;

        if ($request->hasFile('media')) {
            $counter = 0;
            foreach ($request->file('media') as $file) {
                $counter++;
                $slug = Str::slug('media-'.$counter.'-'.now()->format('d-m-Y H:m:s'));
                $filename = Str::slug('media-'.$counter.'-'.now()->format('d-m-Y H:m:s')).'.'.$file->getClientOriginalExtension();
                $path = $file->storeAs(
                    $new_album->folder,
                    $filename,
                    'public');

                $mime = $file->getClientMimeType();
                $type = '';

                if (str_starts_with($mime, 'image/')) {
                    $type = 'photo';
                } elseif (str_starts_with($mime, 'video/')) {
                    $type = 'video';
                } elseif (str_starts_with($mime, 'audio/')) {
                    $type = 'audio';
                } elseif ($mime === 'application/pdf') {
                    $type = 'pdf';
                } else {
                    $type = 'other'; // opcional para archivos no soportados
                }

                $new_media = new Media();
                $new_media->fill([
                    'user_id' => auth()->user()->id,
                    'mime_type' => $mime,
                    'file_path' => $path,
                    'slug' => $slug,
                    'type' => $type,
                    'title' => $filename,
                    'visibility' => 'followers_only',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $new_media->save();

                DB::table('album_media')->insert([
                    'album_id' => $new_album->id,
                    'media_id' => $new_media->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            }
        }

        $new_album->save();

        toastr()->addSuccess('Album creado correctamente');

        sweetalert()
        ->showConfirmButton(
           true,
            "Enterado",
            "btn btn-success",
            "Enterado"
        )
        ->addSuccess('Album creado correctamente');

        return redirect()->route('albums.index', [$user]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Album $album)
    {
        return view('plumr.account.albums.show', [
            'user' => $user,
            'album' => $album,
            'route' => route('albums.update', [$user, $album]),
            'action' => 'edit',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Album $album)
    {
        //
        return view('plumr.account.albums.form', [
            'user' => $user,
            'album' => $album,
            'route' => route('albums.update', [$user, $album]),
            'action' => 'edit',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Album $album)
    {
        //

        // Validar formulario
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:3'],
            'description' => ['nullable', 'string', 'min:3'],
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5400'],
            'visibility' => ['nullable', 'in:private,public,followers_only'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:20'],
        ]);

        $old_album = (object) $album->toArray();

        $album->fill($validated);

        if($request->hasFile('cover')) {

            // Eliminar portada del artículo antiguo
            if($old_album->cover && Storage::disk('public')->exists($old_album->cover)) {
                Storage::disk('public')->delete($old_album->cover);
                toastr()->addInfo('Portada anterior eliminado correctamente');
            }

            $path = $request->file('cover')->store('albums/cover', 'public');
            $album->cover = $path;
            toastr()->addSuccess('Portada modificado correctamente');
        }

        if ($request->hasFile('media')) {
            $counter = $user->albums->map->medias->flatten()->count();
            foreach ($request->file('media') as $file) {
                $counter++;
                $slug = Str::slug('media-'.$counter.'-'.now()->format('d-m-Y H:m:s'));
                $filename = Str::slug('media-'.$counter.'-'.now()->format('d-m-Y H:m:s')).'.'.$file->getClientOriginalExtension();
                $path = $file->storeAs(
                    $album->folder,
                    $filename,
                    'public');

                $mime = $file->getClientMimeType();
                $type = '';

                if (str_starts_with($mime, 'image/')) {
                    $type = 'photo';
                } elseif (str_starts_with($mime, 'video/')) {
                    $type = 'video';
                } elseif (str_starts_with($mime, 'audio/')) {
                    $type = 'audio';
                } elseif ($mime === 'application/pdf') {
                    $type = 'pdf';
                } else {
                    $type = 'other'; // opcional para archivos no soportados
                }

                $new_media = new Media();
                $new_media->fill([
                    'user_id' => auth()->user()->id,
                    'mime_type' => $mime,
                    'file_path' => $path,
                    'slug' => $slug,
                    'type' => $type,
                    'title' => $filename,
                    'visibility' => 'followers_only',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $new_media->save();

                DB::table('album_media')->insert([
                    'album_id' => $album->id,
                    'media_id' => $new_media->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            }
        }

        $album->touch();
        $album->save(); // Actualizar album

        toastr()->addSuccess('Album modificado correctamente');

        sweetalert()
        ->showConfirmButton(
           true,
            "Enterado",
            "btn btn-success",
            "Enterado"
        )
        ->addSuccess('Album modificado correctamente');

        return redirect()->route('albums.index', [$user]);
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
