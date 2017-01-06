<?php

namespace core;

use PDO;

abstract class DB
{
    /**
     * Conexión a la base de datos usando DBAL de Doctrine
     *
     * @return mixed
     */
    public static function connection()
    {
        static $conn = null;
        
        if ($conn === null) {
            
            $config = new \Doctrine\DBAL\Configuration();
            $db_config = Config::get('database');
            $connectionParams = $db_config['connections'][$db_config['default']];
        
            $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        }
        
        return $conn;
    }

    /**
     * Ejecuta una consulta a la base de datos y devuelve un array
     *
     * @param  string  $sql    Consulta SQL
     * @param  array   $params Parámetros de la consulta
     * @param  boolean $fetch  Si la consulta recupera datos
     *
     * @return mixed Datos de la consulta o boolean
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
