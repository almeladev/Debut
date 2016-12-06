<?php

namespace core;

use PDO;

abstract class DB
{
    /**
     * Conexión a la base de datos usando la clase PDO
     *
     * @return mixed
     */
    public static function connection()
    {
        // Archivo de configuración para la base de datos
        $db_config = Config::get('database');
        $db_config = $db_config['connections'][$db_config['default']];
        
        static $db = null;

        if ($db === null) {
            
            switch ($db_config['driver']) {
                case 'mysql':
                    // dsn de mysql -> http://php.net/manual/en/ref.pdo-mysql.connection.php
                    $dsn =  $db_config['driver'] . ':host=' . $db_config['host'] . ';port=' . $db_config['port'] . ';dbname=' .
                            $db_config['database'] . ';charset=' . $db_config['charset'];
                    break;
                case 'pgsql':
                    // dsn de pgsql -> http://www.php.net/manual/en/ref.pdo-pgsql.connection.php
                    $dsn =  $db_config['driver'] . ':host=' . $db_config['host'] . ';port=' . $db_config['port'] . ';dbname=' .
                            $db_config['database'];
                    break;
                default:
                   throw new \Exception('No existe el driver para la conexión', 404);
            }
            
            $db = new PDO($dsn, $db_config['username'], $db_config['password']);
            // Arroja los errores si los hubiese
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }

    /**
     * Ejecuta una consulta a la base de datos y devuelve un array
     *
     * @param  string  $sql    Consulta SQL
     * @param  array   $params Parámetros de la consulta
     * @param  boolean $fetch  Si la consulta recupera datos
     *
     * @return mixed Datos de la consulta y boolean
     */
    public static function query($sql, $params = null, $fetch = true)
    {
        $stmt = static::connection()->prepare($sql);
        
        if ($stmt->execute($params)) {
            return ($fetch) ? $result = ($params) ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC) : true;
        }
        return false;
    }
}
