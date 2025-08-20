<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Obteniendo el parámetro 'user' de la ruta
        $username = $this->route('user');

        // Si tienes un model User
        $user = \App\Models\User::where('username', $username)->first();

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
            'title' => ['required', 'string', 'min:15', 'max:160'],
            'subtitle' => ['nullable', 'string', 'min:15'],
            'summary' => ['required', 'string', 'min:15', 'max:255'],
            'header' => ['nullable', 'string', 'min:15'],
            'content' => ['nullable', 'string', 'min:15'],
            'footer' => ['nullable', 'string', 'min:15'],
            'author' => ['nullable', 'string', 'min:15'],
            'profession' => ['nullable', 'string', 'min:15'],
            'network_social' => ['nullable', 'array'],
            'tags' => ['nullable', 'string', 'max:255'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'is_publish' => ['required', 'in:false,true'],
        ];
    }

    public function messages()
    {
        return [
            'cover.image' => 'El archivo debe ser una imagen válida.',
            'cover.mimes' => 'Solo se permiten imágenes JPG, JPEG o PNG.',
            'cover.max' => 'El tamaño máximo permitido es de 5 MB.',
        ];
    }

}
