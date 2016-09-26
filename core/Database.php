<?php

namespace core;

use PDO;

abstract class Database
{
    /**
     * Conexión a la base de datos usando la clase PDO
     *
     * @return mixed
     */
    public static function connection()
    {
        // Archivo de configuración
        require_once '../app/config.php';

        static $db = null;

        if ($db === null) {
            $dsn = $database_cfg["driver"] . ':host=' . $database_cfg["host"] . ';dbname=' .
                $database_cfg["database"] . ';charset=' . $database_cfg["charset"];

            $db = new PDO($dsn, $database_cfg["user"], $database_cfg["pass"]);

            // Arroja los errores si los hubiese
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }

    /**
     * Ejecuta una consulta a la base de datos
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
