<?php

namespace core;

use core\Routing\Router;

class Kernel
{   
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
        if (! session_id()) {
            @session_start();
        }
        
        // --------------------------------------------------------------
        // Inicio del enrutamiento
        // --------------------------------------------------------------
        $router = new Router();
        $router->run();
    }
}

