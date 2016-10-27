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
        // Archivo de configuración de la aplicación
        $db_config = (require_once ROOT . 'config/database.php');

        $driver_default = $db_config['default'];
        $db_config = $db_config['connections'][$driver_default];
        
        static $db = null;

        if ($db === null) {
            $dsn = $db_config['driver'] . ':host=' . $db_config['host'] . ';dbname=' .
                $db_config['database'] . ';charset=' . $db_config['charset'];

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
     * @return array Datos de la consulta
     */
    public static function query($sql, $params = null, $fetch = true)
    {
        $stmt = static::connection()->prepare($sql);
        $stmt->execute($params);

        if ($fetch) {
            if ($params) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return $result;
        }
    }
}
