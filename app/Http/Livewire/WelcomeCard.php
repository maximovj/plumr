<?php

namespace App\Http\Livewire;

use Livewire\Component;

class WelcomeCard extends Component
{

    public $titulo = "Bienvenido";
    public $subtitulo;

    public function mount($subtitulo)
    {
        $this->subtitulo = $subtitulo;
    }

    public function render()
    {
        return view('livewire.welcome-card');
    }
}
