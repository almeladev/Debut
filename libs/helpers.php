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
 * @param mixed  $default
 * 
 * @return $config
 */
function config($var, $default = null)
{
    $file = APP . 'config.php';
    
    if (!file_exists($file)) {
        throw new \Exception('No existe el archivo de configuración', 404);
    }
    
    $array = require $file;
    $data = (isset($array[$var])) ? $array[$var] : null;
    
    $config = (! empty($data)) ? $data : $default;
    return $config;
}

/**
 * Renderiza la plantilla con los datos
 * 
 * @param string $view
 * @param array  $args
 * 
 * @return void
 */
function view($view, array $args = [])
{
    return \core\View::template($view, $args);
}

/**
 * Encripta una contraseña
 * 
 * @param type $password
 * 
 * @return string
 */
function encrypt($password)
{
    return \core\Hash::make($password);
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


