<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

class ConfirmDeleteModel extends Component
{
    public Model $model;
    public $redirect = null;   // ruta a donde redirigir (opcional)
    public $message = 'Esta acción no se puede deshacer.';
    public $title = '¿Eliminar este registro?';
    public $showModal = false;

    public function mount(Model $model, $redirect = null, $title = null, $message = null)
    {
        $this->model = $model;
        $this->redirect = $redirect;
        if ($title) $this->title = $title;
        if ($message) $this->message = $message;
    }

    public function delete()
    {
        // Eliminar modelo
        $this->model->delete();

        toastr()->addSuccess('Registro eliminado correctamente');

        sweetalert()
        ->showConfirmButton(
           true,
            "Enterado",
            "btn btn-success",
            "Enterado"
        )
        ->addSuccess('Registro eliminado correctamente');

        if ($this->redirect) {
            return redirect()->to($this->redirect);
        }

        $this->emit('deleted'); // evento por si quieres capturarlo
    }

    public function render()
    {
        return view('livewire.confirm-delete-model');
    }
}
