<?php

namespace core\Routing;

class Router
{
    /**
     * Tabla de rutas
     *
     * @var array
     */
    private static $routes = array();

    /**
     * Añade a la ruta el método de envío GET
     *
     * @param string $path     La ruta de la tabla
     * @param mixed  $callable La tarea (controlador, acción, etc.)
     *
     * @return self
     */
    public static function get($path, $callable)
    {
        return self::add($path, $callable, 'GET');
    }

    /**
     * Añade a la ruta el método de envío POST
     *
     * @param string $path     La ruta de la tabla
     * @param mixed  $callable La tarea (controlador, acción, etc.)
     *
     * @return self
     */
    public static function post($path, $callable)
    {
        return self::add($path, $callable, 'POST');
    }

    /**
     * Añade la ruta a la tabla de rutas
     *
     * @param string $path     La ruta de la tabla
     * @param mixed  $callable La tarea (controlador, acción, etc.)
     * @param string $method   Forma de envío
     *
     * @return object $route La ruta
     */
    private static function add($path, $callable, $method)
    {
        $route = new Route($path, $callable);
        static::$routes[$method][] = $route;

        return $route;
    }

    /**
     * Obtiene la URL
     *
     * @return string $url La url seleccionada
     */
    private function getUrl()
    {
        $url = (!isset($_GET['url'])) ? '/' : $_GET['url'];
        return $url;
    }

    /**
     * Inicia el enrutamiento de la aplicación. Comprueba si existe la ruta
     * en la tabla de rutas y lleva a cabo el proceso de redireccionamiento al
     * controlador con su acción o hace la llamada a la función anónima establecida
     *
     * @return object
     */
    public function run()
    {
        if (!isset(static::$routes[$_SERVER['REQUEST_METHOD']])) {
            throw new \Exception('No existe el método de solicitud', 404);
        }

        foreach (static::$routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->match($this->getUrl())) {
                return $route->call();
            }
        }
        throw new \Exception('No se ha encontrado la ruta', 404);
    }
}
