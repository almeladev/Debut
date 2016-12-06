<?php

namespace core;

abstract class Model
{
    /**
     * La tabla de la base de datos
     * @var string
     */
    protected $table;

    /**
     * Campo identificador
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * Constructor para los modelos, permite
     * instanciar el objeto con propiedades
     * 
     * @param array $attributes
     */
    public function __construct(array $attributes = []) {  
        if ($attributes) {
            foreach ($attributes as $key => $attribute) {
                $this->$key = $attribute;
            }
        }
    }

    /**
     * Todos los registros de la base de
     * datos
     *
     * @return array
     */
    public static function all()
    {
        $model = new static();

        $sql    = 'SELECT * FROM ' . $model->table . ' ORDER BY ' . $model->primaryKey;
        $query = DB::query($sql);

        return $query;
    }

    /**
     * Obtiene el registro con el identificador elegido como
     * un objeto para luego hacer uso de las acciones save, update y delete
     *
     * @param  int $id El identificador
     *
     * @return Object
     */
    public static function find($id)
    {
        $model = new static();
        
        $sql = 'SELECT * FROM ' . $model->table . ' WHERE ' . $model->primaryKey . ' = :id';
        $params  = ['id' => $id];
        $query = DB::query($sql, $params);
        
        // Obtenemos la información de los campos
        $schema = $model->getSchema();
        $fields_name = [];
        foreach ($schema as $info) {
            $fields_name[] = $info['column_name'];
        }
        
        // Obtenemos los datos del objeto
        $model->id = $id;
        foreach ($fields_name as $field) {
            $model->$field = $query[$field];
        }
        
        return $model;
    }
    
    /**
     * Guarda los datos del modelo en la
     * base de datos
     *
     * @return boolean
     */
    public function save()
    {
        $model = new static();
        
        // Obtenemos la información de los campos
        $schema = $this->getSchema();
        
        // Obtenemos el nombre de los campos comprobando que existen 
        // como atributos del modelo
        $fields_name = [];
        foreach ($schema as $info) {
            if (isset($this->$info['column_name'])) {
                $fields_name[] = $info['column_name'];
            }
        }
        
        // Creamos la consulta
        $fields = implode(', ', $fields_name);
        $statements = preg_replace('#([\w]+)#', ':${1}', $fields);
        $sql = "INSERT INTO $model->table ($fields)
                VALUES ($statements)";
        
        // COMPROBAR SI EL CAMPO ES OBLIGATORIO, 
        // SI ES OBLIGATORIO INDICARSELO AL USUARIO, SINO POR DEFECTO NULL (PROXIMA VERSION)
        foreach ($fields_name as $field) {
            $params[$field] = $this->$field;
        }

        $query = DB::query($sql, $params, false);
        return ($query) ? true : false;
    }
    
    /**
     * Modifica los datos del modelo en la
     * base de datos
     * 
     * @param array $attributes
     * 
     * @return boolean
     */
    public function update(array $attributes = [])
    {
        $model = new static();
        
        // Obtenemos la información de los campos, en concreto el nombre
        $schema = $this->getSchema();
        $fields_name = [];
        foreach ($schema as $info) {
            $fields_name[] = $info['column_name'];
        }
        
        // Creamos la consulta
        $fields = implode(', ', $fields_name);
        $statements = preg_replace('#([\w]+)#', '${1}=:${1}', $fields);
        $sql = "UPDATE $model->table SET $statements WHERE id=" . $this->id;
        
        // Formamos los parámetros de la consulta
        if (!$attributes) {
            foreach ($fields_name as $field) {
                $params[$field] = $this->$field;
            }
        } else {
            foreach ($attributes as $key => $attribute) {
                $params[$key] = $attribute;
            }
        }
        
        // Hacemos la consulta a la BBDD y comprobamos resultado
        $query = DB::query($sql, $params, false);
        return ($query) ? true : false;
    }
    
    /**
     * Elimina los datos del modelo en la
     * base de datos
     *
     * @return boolean
     */
    public function delete()
    {
        $model = new static();
        
        $sql = "DELETE FROM $model->table WHERE id=" . $this->id;

        $query = DB::query($sql, null, false);
        return ($query) ? true : false;
    }
    
    /**
     * Obtiene información sobre los campos del modelo, la cual
     * contiene el nombre del campo, el tipo, su longitud máxima
     * y si es nulo
     * 
     * @return array
     */
    private function getSchema()
    {
        $model = new static();
        
        // Archivo de configuración para la base de datos
        $db_config = Config::get('database');
        $db_config = $db_config['connections'][$db_config['default']];
        
        // Dependiendo del motor de base de datos, la información del esquema puede ser distinta
        // en bases de datos PostgreSQL puedes crear más de un esquema (por defecto public) para 
        // la base de datos mientras que en MySQL el esquema por defecto es la misma base de datos
        // 
        // REVISAR LA INFORMACIÓN REFERENTE A OTRAS VERSIONES
        // http://dev.mysql.com/doc/refman/5.6/en/columns-table.html
        // https://www.postgresql.org/docs/9.1/static/infoschema-columns.html 
        $table_schema = ($db_config['driver'] == 'pgsql') ? $db_config['schema'] : $db_config['database'];
        
        $sql = "SELECT  column_name,
                        data_type,
                        character_maximum_length,
                        is_nullable
                FROM information_schema.columns 
                WHERE   table_name='$model->table'
                        AND table_schema='$table_schema'
                        AND column_name <> '$model->primaryKey'";
        
        $query = DB::query($sql);
        return $query;
    }
}
