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
        require_once ROOT . 'libs/helpers.php';
        require_once APP . 'Http/routes.php';
    }
    
    /**
     * Arranca la aplicación con la configuración establecida
     * 
     * @return void
     */
    public function run()
    {
        // --------------------------------------------------------------
        // Registro de errores
        // --------------------------------------------------------------
        Handler::register();
        
        // --------------------------------------------------------------
        // Inicio de sesiones
        // --------------------------------------------------------------
        session_start();
        
        // --------------------------------------------------------------
        // Inicio del enrutamiento
        // --------------------------------------------------------------
        $router = new Router();
        $router->run();
    }
}

