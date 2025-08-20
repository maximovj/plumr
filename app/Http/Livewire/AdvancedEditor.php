<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class AdvancedEditor extends Component
{
    use WithFileUploads;

    public $editorId;
    public $height = 300;
    public $content = '';
    public $fieldName = '';
    public $placeholder = '';

    protected $listeners = [
        'quillContentUpdated' => 'updateContent',
        'uploadImage' => 'handleImageUpload'
    ];

    public function mount($editorId, $placeholder, $fieldName, $content = '')
    {
        $this->editorId     = $editorId;
        $this->placeholder  = $placeholder;
        $this->fieldName    = $fieldName;
        $this->content      = $content;
    }

    public function updateContent($editorId, $value)
    {
        if (strval($editorId) == strval($this->editorId)) {
            $this->content = $value;
        }
    }

    public function handleImageUpload($fileBase64, $editorId)
    {
        if (strval($editorId) == strval($this->editorId)) {
            // Convertir base64 a archivo
            $data = explode(',', $fileBase64);
            $decoded = base64_decode($data[1]);

            $fileName = 'quill/toolbar/image/' . uniqid() . '.png';
            Storage::disk('public')->put($fileName, $decoded);

            // Retornar URL pública para insertar en Quill
            $url = asset('storage/' . $fileName);
            $this->emit('imageUploaded', $url, $editorId);
        }
    }

    public function render()
    {
        return view('livewire.advanced-editor');
    }
}
