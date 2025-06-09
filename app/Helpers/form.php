<?php

// Helpers para formularios
// - Laravel Blade / vistas

// Crear un nuevo helper
if(!function_exists('e_class'))
{
    function e_class($field)
    {
        if(session('errors') && session('errors')->has($field) )
            return 'border-2 border-red-600 hover:border-red-600 focus:border-red-600 active:border-red-600';
        else
            return 'border-1 border-gray-400';
    }
}
