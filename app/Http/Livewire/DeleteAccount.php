<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class DeleteAccount extends Component
{
    public $password;

    public function deleteAccount()
    {
        $this->validate([
            'password' => 'required'
        ]);

        if (!Hash::check($this->password, auth()->user()->password)) {
            $this->addError('password', 'La contraseña es incorrecta.');
            return;
        }

        toastr()->addSuccess('Cuenta eliminada correctamente');

        sweetalert()
        ->showConfirmButton(
            true,
            "Enterado",
            "btn btn-success",
            "Enterado"
        )
        ->addSuccess('Cuenta eliminada correctamente');

        $user = auth()->user();

        // Borrar relaciones
        $user->profile()->delete();
        $user->posts()->detach();
        $user->articles()->detach();
        $user->followings()->detach();
        $user->followers()->detach();

        // Cerrar sesión y borrar
        auth()->logout();
        $user->delete();

        return redirect()->route('home');

    }

    public function render()
    {
        return view('livewire.delete-account');
    }
}
