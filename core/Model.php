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
     * Constructor para los modelos
     * 
     * @param array $attributes
     */
    public function __construct($attributes = []) {  
        // Permite instanciar el objeto con atributos desde un array asociativo
        if (! empty($attributes)) {
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
        
        // Obtenemos la información de los campos, en concreto el nombre
        $schema = $this->getSchema();
        $fields_name = [];
        foreach ($schema as $info) {
            $fields_name[] = $info['column_name'];
        }
        
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
     * @return boolean
     */
    public function update()
    {
        $model = new static();
        
        // Obtenemos la información de los campos, en concreto el nombre
        $schema = $this->getSchema();
        $fields_name = [];
        foreach ($schema as $info) {
            $fields_name[] = $info['column_name'];
        }
        
        $fields = implode(', ', $fields_name);
        $statements = preg_replace('#([\w]+)#', '${1}=:${1}', $fields);
        
        $sql = "UPDATE $model->table SET $statements WHERE id=" . $this->id;

        foreach ($fields_name as $field) {
            $params[$field] = $this->$field;
        }
        
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
        
        $database = config('DB_DATABASE');
        
        // REVISAR LA INFORMACIÓN REFERENTE A OTRAS VERSIONES
        // http://dev.mysql.com/doc/refman/5.6/en/columns-table.html
        // https://www.postgresql.org/docs/9.1/static/infoschema-columns.html 
        $sql = "SELECT  column_name,
                        data_type,
                        character_maximum_length,
                        is_nullable
                FROM information_schema.columns 
                WHERE   table_name='$model->table'
                        AND table_schema='$database'
                        AND column_name <> '$model->primaryKey'";
        
        $query = DB::query($sql);
        return $query;
    }
}
