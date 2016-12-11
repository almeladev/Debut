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
    
    /**
     * Obtiene el nombre de las columnas de una tabla
     * Esto es muy importante para la completa abstracción de la BBDD
     * 
     * @param string $table La tabla a consultar
     * 
     * @return mixed El nombre de las columnas o boolean
     */
    public static function getNameColumns($table)
    {
        $stmt = static::connection()->getSchemaManager();
        
        $columns = $stmt->listTableColumns($table);
        
        if (! empty($columns)) {
            // Obtenemos el nombre de cada campo
            foreach ($columns as $column) {
                $name = $column->getName();
                $columns_name[] = $name;
            }
            return $columns_name;
        }
        return false;
    }
}
