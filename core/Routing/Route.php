<?php

namespace core\Routing;

use Exception;
use core\DI;

class Route
{
    /**
     * Ruta
     * @var string
     */
    private $path;

    /**
     * Tarea
     * @var mixed
     */
    private $callable;

    /**
     * Partes de la ruta
     * @var array
     */
    private $matches = array();

    /**
     * Constructor
     * 
     * @param string $path
     * @param mixed  $callable
     */
    public function __construct($path, $callable)
    {
        $this->path     = trim($path, '/');
        $this->callable = $callable;
    }

    /**
     * Prepara la ruta con expresiones regulares
     * y capta las variables
     *
     * @param string $url La URL
     *
     * @return boolean
     */
    public function match($url)
    {
        $url  = trim($url, '/');
        $path = preg_replace('#{([\w]+)}#', '([^/]+)', $this->path);

        $regex = "#^$path$#i";

        if (!preg_match($regex, $url, $matches)) {
            return false;
        }

        array_shift($matches);
        $this->matches = $matches;

        return true;
    }

    /**
     * Llama a la tarea de la ruta pasando
     * también sus posibles parámetros
     *
     * @return callable
     */
    public function call()
    {
        if (is_string($this->callable)) {

            $params = explode('#', $this->callable);

            $controller = "app\Controllers\\$params[0]";
            $action     = $params[1];

            if (class_exists($controller)) {

                $controller_object = new $controller();

                if (is_callable([$controller_object, $action])) {
                    
                    // Instanciamos los parámetros de tipo objeto si existieran
                    // y los almacenamos en el orden adecuado para enviarlos
                    // al controlador correspondiente
                    $objects = DI::make($controller_object, $action);
                    $data_params = $this->matches;
                    foreach ($objects as $key => $param) {
                        array_splice($data_params, $key, 0, [$param]);
                    }
                    
                    return call_user_func_array([$controller_object, $action], $data_params);
                }
                throw new Exception("El método $action (en el controlador $controller) no ha sido encontrado.");
            }
            throw new Exception("El controlador $controller no ha sido encontrado.");

        } else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }
}
