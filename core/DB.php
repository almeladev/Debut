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
    
    /**
     * Obtiene información sobre los campos de una tabla. Devuelve todos los campos
     * con el nombre, el tipo, su longitud máxima y si es nulo
     * 
     * @return array
     */
    public static function schema($table)
    {
        // Archivo de configuración para la base de datos
        $db_config = Config::get('database');
        $db_config = $db_config['connections'][$db_config['default']];
        
        /* Dependiendo del motor de base de datos, la información del esquema puede ser distinta
         * en bases de datos PostgreSQL puedes crear más de un esquema (por defecto public) para 
         * la base de datos mientras que en MySQL el esquema por defecto es la misma base de datos
         */
        $table_schema = ($db_config['driver'] == 'pgsql') ? $db_config['schema'] : $db_config['database'];
        
        $sql = "SELECT  column_name,
                        data_type,
                        character_maximum_length,
                        is_nullable
                FROM information_schema.columns 
                WHERE   table_name='$table'
                        AND table_schema='$table_schema'";
        
        $query = DB::query($sql);
        return ($query) ? $query : false;
    }
}
