<?php

/*
|--------------------------------------------------------------------------
| Funciones útiles
|--------------------------------------------------------------------------
|
| En este archivo podemos añadir funciones personalizadas para nuestro
| proyecto
|
*/

/**
 * Obtiene los datos de las variables de entorno
 * 
 * @param string $var
 * @param mixed $default
 * @return $data
 */
function config($var, $default = null)
{
    $array_ini = parse_ini_file(ROOT . 'config.ini');
    $data = $array_ini[$var];
    
    $config = (! empty($data)) ? $data : $default;
    
    return $config;
}


