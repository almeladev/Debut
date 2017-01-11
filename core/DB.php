<?php

namespace core;

use PDO;
use core\Config;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

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
            
            $config = new Configuration();
            $db_config = Config::get('database');
            $connectionParams = $db_config['connections'][$db_config['default']];
        
            $conn = DriverManager::getConnection($connectionParams, $config);
        }
        
        return $conn;
    }

    /**
     * Ejecuta una consulta a la base de datos y devuelve un array
     *
     * @param  string  $sql
     * @param  array   $params
     * @param  boolean $fetch
     *
     * @return mixed|bool
     */
    public static function query($sql, $params = null, $fetch = true)
    {
        $stmt = static::connection()->prepare($sql);
        
        if ($stmt->execute($params)) {
            if ($fetch) {
                $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return (count($array) === 1) ? $array[0] : $array;
            }
        }
        return false;
    }
}
