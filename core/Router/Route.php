<?php

namespace core\Router;

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
    private $matches = [];

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

            $params = explode('@', $this->callable);

            $controller = $params[0];
            $controller = "app\Controllers\\$controller";
            $action     = $params[1];

            if (class_exists($controller)) {

                $controller_object = new $controller();

                if (is_callable([$controller_object, $action])) {
                    return call_user_func_array([$controller_object, $action], $this->matches);
                }
                throw new \Exception("El método $action (en el controlador $controller) no ha sido encontrado.");
            }
            throw new \Exception("El controlador $controller no ha sido encontrado.");

        } else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }
}
