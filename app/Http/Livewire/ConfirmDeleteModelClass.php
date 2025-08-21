<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

                //session()->flash('success', 'Registro eliminado ✅');
            }
        } else {
            toastr()->addError('Registro no eliminado acción prohibida');
        }

        $this->showModal = false;

        if ($this->redirect) {
            return redirect()->to($this->redirect);
        }

        $this->emit('deleted', $this->modelId, $this->modelClass);
    }

    public function render()
    {
        return view('livewire.confirm-delete-model-class');
    }
}
