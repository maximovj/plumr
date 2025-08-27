<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MediasGallery extends Component
{
    public $medias;
    public $album;
    public $user;

    public function mount($medias, $user, $album = null)
    {
        $this->medias = $medias;
        $this->user = $user;
        $this->album = $album;
    }

    public function render()
    {
        return view('livewire.medias-gallery');
    }
}
