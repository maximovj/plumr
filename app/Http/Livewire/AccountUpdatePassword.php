<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AccountUpdatePassword extends Component
{
    public $current_password;
    public $password;
    public $password_confirmation;

    protected $listeners = [
       'executeResetForm' => 'resetForm'
    ];

    protected $rules = [
        'current_password' => 'required',
        'password' => 'required|min:8|confirmed',
    ];

    public function mount() {
        $this->resetForm();
    }

    public function updatePassword()
    {
        $this->validate();

        if (!Hash::check($this->current_password, auth()->user()->password)) {
            $this->addError('current_password', 'La contraseña actual es incorrecta.');
            return;
        }

        auth()->user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->resetForm();
        session()->flash('message', 'Contraseña actualizada correctamente ✅');
    }

    public function resetForm()
    {
        $this->reset(['current_password', 'password', 'password_confirmation']);
    }

    public function render()
    {
        $this->resetForm();
        return view('livewire.account-update-password');
    }
}
