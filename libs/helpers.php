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
 * 
 * @return $data
 */
function config($var, $default = null)
{
    $array_ini = parse_ini_file(ROOT . 'config.ini');
    $data = (isset($array_ini[$var])) ? $array_ini[$var] : null;
    
    $config = (! empty($data)) ? $data : $default;
    
    return $config;
}

/**
 * Redirreciona a la ruta elegida
 *
 * @param string $path La ruta
 *
 * @return void
 */
function redirect($path = null)
{
    if ($path) {
        header('Location: ' . $path);
        exit;
    }
}


