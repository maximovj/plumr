<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MediasGallery extends Component
{
    public $medias;
    public $album;
    public $user;
    public $redirect;

    public function mount($medias, $user, $album = null, $redirect = null)
    {
        $this->medias = $medias;
        $this->user = $user;
        $this->album = $album;
        $this->redirect = $redirect;
    }

    public function render()
    {
        if($this->redirect == null){
        $this->redirect = isset($this->album->slug) ?
            route('albums.show', [$this->user, $this->album]) :
            route('medias.index', [$this->user]);
        }
        return view('livewire.medias-gallery');
    }
}
