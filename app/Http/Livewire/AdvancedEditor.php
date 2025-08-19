<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class AdvancedEditor extends Component
{
    use WithFileUploads;

    public $height = 300;
    public $content = '';

    protected $listeners = ['quillContentUpdated' => 'updateContent', 'uploadImage' => 'handleImageUpload'];

    public function updateContent($value)
    {
        $this->content = $value;
    }

    public function handleImageUpload($fileBase64)
    {
        // Convertir base64 a archivo
        $data = explode(',', $fileBase64);
        $decoded = base64_decode($data[1]);

        $fileName = 'quill/toolbar/image/' . uniqid() . '.png';
        Storage::disk('public')->put($fileName, $decoded);

        // Retornar URL pública para insertar en Quill
        $url = asset('storage/' . $fileName);
        $this->emit('imageUploaded', $url);
    }

    public function render()
    {
        return view('livewire.advanced-editor');
    }
}
