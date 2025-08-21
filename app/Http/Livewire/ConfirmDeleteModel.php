<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ConfirmDeleteModel extends Component
{
    public User $user;
    public Model $model;
    public $redirect = null;   // ruta a donde redirigir (opcional)
    public $message = 'Esta acción no se puede deshacer.';
    public $title = '¿Eliminar este registro?';
    public $showModal = false;

    public function mount(User $user, Model $model, $redirect = null, $title = null, $message = null)
    {
        $this->user = $user;
        $this->model = $model;
        $this->redirect = $redirect;
        if ($title) $this->title = $title;
        if ($message) $this->message = $message;
    }

    public function delete()
    {
        if(!$this->model && !$this->user ) return;

        if(Auth::check() && Auth::user()->id != $this->user->id ) {
            toastr()->addSuccess('Registro no eliminado, acción prohibida');
            return;
        }

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
