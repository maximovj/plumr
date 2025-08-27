<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MediasGallery extends Component
{
    public $medias;
    public $user;

    public function mount($medias, $user)
    {
        $this->medias = $medias;
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.medias-gallery');
    }
}
