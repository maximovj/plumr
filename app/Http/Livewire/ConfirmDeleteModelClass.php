<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ConfirmDeleteModelClass extends Component
{
    public $modelClass;
    public $modelId;
    public $redirect = null;
    public $title = '¿Eliminar este registro?';
    public $message = 'Esta acción no se puede deshacer.';
    public $showModal = false;

    protected $listeners = [
        'confirmDeleteModelClass' => 'confirmDelete',
    ];

    public function confirmDelete($modelClass, $modelId, $redirect = null, $title = null, $message = null)
    {
        $this->modelClass = $modelClass;
        $this->modelId = $modelId;
        $this->redirect = $redirect;

        if ($title) $this->title = $title;
        if ($message) $this->message = $message;

        $this->showModal = true;
    }

    public function delete()
    {
        if (!$this->modelClass || !$this->modelId) return;

        $model = $this->modelClass::find($this->modelId);

        if ($model instanceof \App\Models\Album) {
            if ($model && $user = $model->user) {
                $this->deletingAlbum($model, $user);
            }
        } else
        if ($model instanceof \App\Models\Media) {
            if ($model && $user = $model->albums->first()->user) {
                $this->deletingMedia($model, $user);
            }
        } else
        if ($model && $user = $model->owner->first()) {
            if(Auth::check() && Auth::user()->id ==  $user->id) {
                $model->delete();
                toastr()->addSuccess('Registro eliminado correctamente');

                sweetalert()
                ->showConfirmButton(
                true,
                    "Enterado",
                    "btn btn-success",
                    "Enterado"
                )
                ->addSuccess('Registro eliminado correctamente');
            }
        } else {
            toastr()->addError('Registro no eliminado, acción prohibida');
        }

        $this->showModal = false;

        if ($this->redirect) {
            return redirect()->to($this->redirect);
        }

        $this->emit('deleted', $this->modelId, $this->modelClass);
    }

    protected function deletingAlbum($model, $user)
    {
        if(Auth::check() && Auth::user()->id ==  $user->id) {

            // Eliminar todos los registros de media asociados
            $album = $model;
            foreach ($album->media as $media) {
                $media->delete(); // el observer se encarga de borrar el archivo
            }

            // Limpiar la relación pivote
            $album->media()->detach();

            // Eliminar también la carpeta entera
            if ($album->folder && Storage::disk('public')->exists($album->folder)) {
                Storage::disk('public')->deleteDirectory($album->folder);
            }

            // Eliminar el álbum
            $album->delete();

            toastr()->addSuccess('Registro eliminado correctamente');

            sweetalert()
            ->showConfirmButton(
                true,
                "Enterado",
                "btn btn-success",
                "Enterado"
            )
            ->addSuccess('Registro eliminado correctamente');
        }
    }

    protected function deletingMedia($model, $user)
    {
        if(Auth::check() && Auth::user()->id ==  $user->id) {
            // Eliminar media
            $model->delete();

            toastr()->addSuccess('Registro eliminado correctamente');

            sweetalert()
            ->showConfirmButton(
                true,
                "Enterado",
                "btn btn-success",
                "Enterado"
            )
            ->addSuccess('Registro eliminado correctamente');
        }
    }

    public function render()
    {
        return view('livewire.confirm-delete-model-class');
    }
}
