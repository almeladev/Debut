<?php

namespace core\Routing;

class Redirector 
{
    
    /**
     * Permite redireccionar a la ruta indicada usando
     * el propio constructor de la clase
     * 
     * @param string $path
     * 
     * @return \core\Routing\Redirector
     */
    public function __construct($path = null, $statusCode = 303)
    {
        if ($path) {
            header('Location: ' . $path, true, $statusCode);
        }
        return $this;
    }
    
    /**
     * Redirecciona a la ruta indicada
     * 
     * @param string $path
     * @param int    $statusCode
     * 
     * @return \core\Routing\Redirector
     */
    public function route($path, $statusCode = 303)
    {
        header('Location: ' . $path, true, $statusCode);
        return $this;
    }
    
    /**
     * Redirecciona a la ruta anterior
     * 
     * @param int $statusCode
     * 
     * @return \core\Routing\Redirector
     */
    public function back($statusCode = 302)
    {
        $path = $_SERVER['HTTP_REFERER'];
        return $this->route($path, $statusCode);
    }
    
    /**
     * Pasa parámetros a la vista como mensajes flash
     * 
     * @param mixed $type
     * @param mixed $message
     * 
     * @return void
     */
    public function with($type, $message)
    {
        flash_messages($type, $message);
    }
}
