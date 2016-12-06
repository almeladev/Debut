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
    'default' => config('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Conexión a la base de datos
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar distintas bases de datos y en un futuro, añadir
    | cualquier otra
    |
    */
    'connections' => [
        
        'mysql' => [
            'driver'   => 'mysql',
            'host'     => config('DB_HOST', 'localhost'),
            'port'     => config('DB_PORT', '3306'),
            'database' => config('DB_DATABASE', ''),
            'username' => config('DB_USERNAME', ''),
            'password' => config('DB_PASSWORD', ''),
            'charset'  => config('DB_CHARSET', 'utf8'),
        ],
        
        'pgsql' => [
            'driver' => 'pgsql',
            'host'     => config('DB_HOST', 'localhost'),
            'port'     => config('DB_PORT', '5432'),
            'database' => config('DB_DATABASE', ''),
            'username' => config('DB_USERNAME', ''),
            'password' => config('DB_PASSWORD', ''),
            'schema'   => 'public',
        ],
        
    ],


];

