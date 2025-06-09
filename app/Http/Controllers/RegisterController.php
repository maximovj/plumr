<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

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

        // Crear validador
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:10|max:255',
            'birthday_day' => 'required|numeric|between:1,32',
            'birthday_month' => 'required|numeric|between:1,12',
            'birthday_year' => ['required', 'numeric', "between:$min_year,$max_year"],
            'sex' => ['nullable', Rule::in(['Hombre', 'Mujer', '*'])],
            'username' => ['required', 'string', 'min:4', 'max:15', 'alpha_num', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Verificar la fecha de nacimiento
        $validator->after(function ($validator) use ($request) {
            $day   = $request->input('birthday_day');
            $month = $request->input('birthday_month');
            $year  = $request->input('birthday_year');

            if (!checkdate($month, $day, $year)) {
                $validator->errors()->add('birthday_day', 'La fecha de nacimiento no es válida.');
                return;
            }

            $birthday = Carbon::createFromDate($year, $month, $day);
            if ($birthday->age < 18) {
                $validator->errors()->add('birthday_day', 'Debes tener al menos 18 años.');
            }
        });

        // Verificar si existe fallas en la validación
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        dd($request->all());
    }
}
