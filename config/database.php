<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Conexión por defecto
    |--------------------------------------------------------------------------
    |
    | Establece la base de datos por defecto
    | 
    */
    'default' => config('DB_CONNECTION', 'pdo_mysql'),

    /*
    |--------------------------------------------------------------------------
    | Conexión a la base de datos
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar distintas bases de datos y en un futuro, añadir
    | cualquier otra gracias a la capa de abstracción de base de datos de 
    | Doctrine
    |
    | http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connection-details
    |
    */
    'connections' => [
        
        'pdo_mysql' => [
            'driver'   => 'pdo_mysql',
            'host'     => config('DB_HOST', 'localhost'),
            'port'     => config('DB_PORT', '3306'),
            'dbname'   => config('DB_DATABASE', ''),
            'user'     => config('DB_USERNAME', ''),
            'password' => config('DB_PASSWORD', ''),
            'charset'  => config('DB_CHARSET', 'utf8'),
        ],
        
        'pdo_pgsql' => [
            'driver'   => 'pdo_pgsql',
            'host'     => config('DB_HOST', 'localhost'),
            'port'     => config('DB_PORT', '5432'),
            'dbname'   => config('DB_DATABASE', ''),
            'user'     => config('DB_USERNAME', ''),
            'password' => config('DB_PASSWORD', ''),
            'charset'  => config('DB_CHARSET', 'utf8'),
        ],
        
    ],


];

