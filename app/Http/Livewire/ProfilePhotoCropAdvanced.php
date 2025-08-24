<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoCropAdvanced extends Component
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

        if ($this->croppedPhoto) {
            list($type, $data) = explode(';', $this->croppedPhoto);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);

            if($this->croppedPhoto && $this->user)
            {
                $fileName = 'profile_' . auth()->id() . '.png';
                $path = Storage::disk('public')->put('users/profiles/photo/'.$fileName, $data);

                $this->user->profile()->update(['photo' => "users/profiles/photo/$fileName"]);

                toastr()->addSuccess('Foto de perfil actualizada correctamente');

                sweetalert()
                ->showConfirmButton(
                true,
                    "Enterado",
                    "btn btn-success",
                    "Enterado"
                )
                ->addSuccess('Foto de perfil actualizada correctamente');
            }

        } else {
            toastr()->addError('Lo siento, fallo al actualizar foto de perfil');
        }
    }

    public function render()
    {
        return view('livewire.profile-photo-crop-advanced');
    }
}
