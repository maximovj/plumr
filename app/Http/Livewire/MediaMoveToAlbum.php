<?php

namespace App\Http\Livewire;

use App\Models\Album;
use App\Models\Media;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class MediaMoveToAlbum extends Component
{
    // Variables
    public Media $media;
    public $showModal = false;
    public array $albums_selected = [];
    public array $albums = [];

    // Props
    public $redirect = null;
    public $albumId;
    public $mediaId;
    public $userId;

    protected $listeners = [
        'mediaMoveToAlbum' => 'openModal',
        'updateAlbumsSelected',
    ];

    public function mount()
    {
        $this->showModal = false;
        $this->albums_selected = []; // 👈 asegura array vacío
        $this->albums = []; // 👈 asegura array vacío
    }

    public function openModal($albumId, $mediaId, $userId, $redirect = null)
    {
        $this->albumId = $albumId;
        $this->mediaId = $mediaId;
        $this->userId = $userId;
        $this->redirect = $redirect;

        // Obtener la lista de álbumes del usuario
        $user = User::find($this->userId);
        $this->albums = $user->albums()->orderBy('title')->get()->toArray() ?? [];
        
        // Obtener la lista de álbumes de la media
        $media = Media::find($this->mediaId);
        $AlbumsIds = $media->albums()->get()->pluck('id')->toArray();
        $this->albums_selected = array_merge($this->albums_selected, $AlbumsIds);

        $this->showModal = true;
    }

    public function updateAlbumsSelected($value)
    {
        $this->albums_selected = $value;
    }

    public function moveToAlbum()
    {

        $this->showModal = false;

        $media = Media::find($this->mediaId);
        $album = Album::find($this->albumId);

        if(empty($this->albums_selected)) {
            if($media && ($album = $media->albums()->first()) && $user = $media->user) {
                if(isowner($user)) {

                    $oldPath = $media->file_path;
                    $newPath = str_replace("album_$album->id/", '', $oldPath);

                    // Mover archivo en el disco "public"
                    try {
                        if (Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->move($oldPath, $newPath);

                            // Actualizar en la BD
                            $media->update([
                                'file_path' => $newPath,
                            ]);
                        }
                    } catch (\Throwable $th) {
                        //throw $th;
                    }

                    $media->albums()->detach();

                    toastr()->addSuccess('Multimedia eliminado correctamente');

                    sweetalert()
                    ->showConfirmButton(
                        true,
                        "Enterado",
                        "btn btn-success",
                        "Enterado"
                    )->addSuccess('Multimedia eliminado correctamente');
                }
            }
        }else
        if(!$album && $media && $user = $media->user) {

            if(isowner($user)) {

                $media->albums()->sync($this->albums_selected);

                $media->albums()->update(['updated_at' => now()]);

                toastr()->addSuccess('Multimedia modificado correctamente');

                sweetalert()
                ->showConfirmButton(
                true,
                "Enterado",
                "btn btn-success",
                "Enterado"
                )->addSuccess('Multimedia modificado correctamente');
            }

        }else
        if($media && $album && $user = $album->user) {
            if(isowner($user)) {

                $media->albums()->sync($this->albums_selected);

                $album->touch();

                $media->albums()->update(['updated_at' => now()]);

                toastr()->addSuccess('Multimedia modificado correctamente');

                sweetalert()
                ->showConfirmButton(
                true,
                "Enterado",
                "btn btn-success",
                "Enterado"
                )->addSuccess('Multimedia modificado correctamente');
            }
        }

        if ($this->redirect) {
            return redirect()->to($this->redirect);
        }

        // Emitimos un evento global
        $this->emit('updatedAlbum', $this->albumId);
    }

    public function render()
    {
        return view('livewire.media-move-to-album');
    }
}
