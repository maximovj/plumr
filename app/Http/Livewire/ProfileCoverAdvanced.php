<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class ProfileCoverAdvanced extends Component
{
    public $croppedPhoto;

    public User $user;

    protected $listeners = [
        'updateCroppedPhoto' => 'setCroppedPhoto'
    ];

    public function mount($user)
    {
        $this->user = $user;
    }


    public function setCroppedPhoto($dataUrl)
    {
        $this->croppedPhoto = $dataUrl;
    }

    public function save()
    {

        if ($this->croppedPhoto && $this->user) {
            list($type, $data) = explode(';', $this->croppedPhoto);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);

            $fileName = 'profile_' . auth()->id() . '.png';
            $path = Storage::disk('public')->put('users/profiles/cover/'.$fileName, $data);

            $this->user->profile()->update(['cover' => "users/profiles/cover/$fileName"]);

            toastr()->addSuccess('Portada de perfil actualizada correctamente');

            sweetalert()
            ->showConfirmButton(
            true,
                "Enterado",
                "btn btn-success",
                "Enterado"
            )
            ->addSuccess('Portada de perfil actualizada correctamente');

        } else {
            toastr()->addError('Lo siento, fallo al actualizar portada de perfil');
        }
    }

    public function render()
    {
        return view('livewire.profile-cover-advanced');
    }
}
