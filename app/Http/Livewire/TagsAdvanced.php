<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;

class TagsAdvanced extends Component
{
    public $tags;

    public function mount($tags = [])
    {
        $this->tags = collect($tags)->filter(fn($item) => !empty($item));
    }

    public function render()
    {
        return view('livewire.tags-advanced');
    }
}
