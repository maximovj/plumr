<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
        $profile = $user->profile;
        return view('plumr.profile.edit', compact('profile', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $min_year = (date('Y') - 100);
        $max_year = (date('Y'));

        // Crear validador
        $validator = Validator::make($request->all(), [
            'fullname' => ['required', 'string', 'min:3', 'max:160'],
            'birthday_day' => 'required|numeric|between:1,32',
            'birthday_month' => 'required|numeric|between:1,12',
            'birthday_year' => ['required', 'numeric', "between:$min_year,$max_year"],
            'sex' => ['nullable', Rule::in(['Hombre', 'Mujer', '*'])],
            'number_phone' => ['nullable', 'string', 'min:3', 'max:60'],
            'country' => ['nullable', 'string', 'min:3', 'max:60'],
            'city' => ['nullable', 'string', 'min:3', 'max:160'],
            'address' => ['nullable', 'string', 'min:3', 'max:160'],
            'bio' => ['nullable', 'string', 'min:3', 'max:160'],
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
        if($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar si existe un perfil para el usuario
        if(!isset($user->profile)) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_top', 'Lo siento no existe el perfil de usuario');
        }

        $user->profile->fill($validator->validated());
        $user->profile->save();

        return redirect()->route('main_account', ['user' => $user]);
    }

}
