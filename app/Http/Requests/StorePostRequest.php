<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePostRequest extends FormRequest
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
            'title' => ['required', 'string', 'min:3', 'max:200'],
            'content' => ['required', 'string', 'min:3'],
            'status' => ['nullable', 'array'],
            'status.*' => ['string', 'max:20'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:20'],
            'links' => ['nullable', 'array'],
            'links.*' => ['nullable', 'url'],
        ];
    }

    public function attributes() {
        return [
            'links' => 'enlace',
            'links.*' => 'enlace',
        ];
    }
}
