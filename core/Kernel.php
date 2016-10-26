<?php

namespace core;

use core\Routing\Router;

class Kernel
{
    /**
     * Constructor del Kernel. 
     */
    public function __construct() 
    {
        require_once ROOT . '/libs/helpers.php';
        require_once APP . 'Http/routes.php';
    }
    
    /**
     * Arranca la aplicación con la configuración establecida
     * 
     * @return void
     */
    public function run()
    {
        // Reporte de errores
        error_reporting(E_ALL);
        set_error_handler('core\ErrorHandler::errorHandler');
        set_exception_handler('core\ErrorHandler::exceptionHandler');
        
        // Arranca el enrutamiento
        $router = new Router();
        $router->run();
    }
    
}

