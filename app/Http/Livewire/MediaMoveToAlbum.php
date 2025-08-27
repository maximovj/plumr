<?php

namespace App\Http\Livewire;

use App\Models\Album;
use App\Models\Media;
use App\Models\User;
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

        $user = User::find($this->userId);
        $this->albums = $user->albums()->orderBy('title')->get()->toArray() ?? [];
        $this->albums_selected[]  = $this->albumId;

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
            if($media && $album && $user = $album->user) {
                if(isowner($user)) {

                    $media->delete();

                    $album->touch();

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
