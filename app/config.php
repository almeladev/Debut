<?php

/*
|--------------------------------------------------------------------------
| Configuración de la aplicación
|--------------------------------------------------------------------------
|
| Aquí es donde puedes establecer la configuración para tu aplicación.
|
*/

return array(
    
    /**
     * Configuración general para la aplicación
     * 
     * APP_DEBUG: modo depurador 
     */
    "APP_DEBUG" => true,
    
    /**
     * Configuración para la base de datos
     * 
     * más info: http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connection-details
     */
    "DB_CONNECTION" => "pdo_mysql",
    "DB_HOST"       => "localhost",
    "DB_PORT"       => 3306,
    "DB_DATABASE"   => "debut",
    "DB_USERNAME"   => "root",
    "DB_PASSWORD"   => "secret",
    "DB_CHARSET"    => "utf8",
    
);

