<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateMediaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Obteniendo el parámetro 'user' de la ruta
        $user = $this->route('user');

        // Si tienes un model User
        $user = \App\Models\User::where('username', $user->username)->first();

        // Retornar false si no existe
        if (!$user) {
            return false;
        }

        return Auth::check() && Auth::user()->id == $user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'min:3'],
            'description' => ['nullable', 'string', 'min:3'],
            'visibility' => ['nullable', 'in:private,public,followers_only'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:20'],
            'media' => ['nullable', 'mimes:jpg,jpeg,png,gif,mp4,mov,avi,mp3,wav,pdf', 'max:20480'],
        ];
    }
}
