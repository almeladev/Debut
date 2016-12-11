<?php

namespace core;

class Config {

    /**
     * Todas las características de la configuración que se ha cargado
     * 
     * @var array
     */
    private static $items = array();

    /**
     * Devuelve la información del archivo
     *
     * @param   string $key
     * @return  array or string
     */
    public static function get($key = null)
    {
        $input = explode('.', $key);
        $filepath = $input[0];
        unset($input[0]);
        
        $key = implode('.', $input);

        static::load($filepath);
        return (! empty($key)) ? static::$items[$key] : static::$items;
    }
    
    /**
     * Carga la configuración del archivo
     *
     * @param   string $filepath
     * @return  void
     */
    private static function load($filepath)
    {
        $file = ROOT . 'config/' . $filepath . '.php';
        
        if (!file_exists($file)) {
            throw new \Exception('No existe el archivo de configuración', 404);
        }
        static::$items = require ROOT . 'config/' . $filepath . '.php';
    }

}
