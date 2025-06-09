<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('plumr.auth.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $min_year = (date('Y') - 100);
        $max_year = (date('Y'));

        // Modificar request
        $request->request->add(['username' => Str::slug($request->username)]);

        // Crear validador
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'birthday_day' => 'required|numeric|between:1,32',
            'birthday_month' => 'required|numeric|between:1,12',
            'birthday_year' => ['required', 'numeric', "between:$min_year,$max_year"],
            'sex' => ['nullable', Rule::in(['Hombre', 'Mujer', '*'])],
            'username' => ['required', 'string', 'min:4', 'max:15', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Verificar la fecha de nacimiento
        $validator->after(function ($validator) use ($request) {
            $day   = $request->input('birthday_day');
            $month = $request->input('birthday_month');
            $year  = $request->input('birthday_year');

            if (!checkdate($month, $day, $year)) {
                $validator->errors()->add('birthday_year', 'La fecha de nacimiento no es válida.');
                return;
            }

            $birthday = Carbon::createFromDate($year, $month, $day);
            if ($birthday->age < 18) {
                $validator->errors()->add('birthday_year', 'Debes tener al menos 18 años.');
            }
        });

        // Verificar si existe fallas en la validación
        if ($validator->fails()) {
            $inputUsername = $request->input('username');

            // Generar sugerencias
            $suggestions = collect();
            for ($i = 0; $i < 5; $i++) {
                $suggested = $inputUsername . Str::random(2);
                if (!User::where('username', $suggested)->exists()) {
                    $suggestions->push($suggested);
                }
            }

            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('username_suggestions', $suggestions);
        } else {
            // Crear un nuevo usuario
            $new_user = new User();
            $new_user->fill($validator->validated());
            $new_user->password = Hash::make($request->password);
            $new_user->save();

            return redirect()->route('home');
        }

    }
}
