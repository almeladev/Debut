<?php

use core\View;
use core\Hash;
use core\Routing\Redirector;

if (! function_exists('config')) {
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
}

if (! function_exists('view')) {
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
        return View::make($view, $args);
    }
}

if (! function_exists('encrypt')) {
    /**
     * Encripta una contraseña
     * 
     * @param type $password
     * 
     * @return string
     */
    function encrypt($password)
    {
        return Hash::make($password);
    }
}

if (! function_exists('redirect')) {
    /**
     * Redirreciona a la ruta elegida
     *
     * @param string $path La ruta
     *
     * @return void
     */
    function redirect($path = null)
    {
        return new Redirector($path);
    }
}

if (! function_exists('flash_messages')) {
    /**
     * Agrega a la sesión mensajes temporales
     * 
     * @param mixed $message
     */
    function flash_messages($type, $message)
    {
        if (! is_array($type)) {
            $_SESSION['flash_messages'][$type] = (array) $message;
        } else {
            foreach ($type as $key => $t) {
                $_SESSION['flash_messages'][$t] = $message[$key];
            }
        }
    }
}

if (! function_exists('clear_flash_messages')) {
    /**
     * Elimina todos los mensajes temporales de la sesión
     */
    function clear_flash_messages()
    {
        unset($_SESSION['flash_messages']);
    }
}

