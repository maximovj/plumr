<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Obtener el usuario de la ruta
        $routeUser = $request->route('user');

        if (!$routeUser instanceof \App\Models\User) {
            $routeUser = \App\Models\User::where('username', $routeUser)->first();
        }

        if (!$routeUser) {
            abort(404);
        }

        // Verificar si hay un usuario autenticado
        if (!Auth::check()) {
            // Redirigir al login si no está autenticado
            return redirect()->route('login')->with('app-error', 'Debes iniciar sesión primero.');
        }

        // Verificar que el usuario autenticado sea el propietario
        if (Auth::user()->id !== $routeUser->id) {
            // Redirigir al dashboard del propio usuario
            return redirect()->route('main_account', ['user' => Auth::user()->username])
                             ->with('app-error', 'No puedes acceder a la cuenta de otro usuario.');
        }

        return $next($request);
    }

}
