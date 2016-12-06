<?php

namespace core;

abstract class Model
{
    /**
     * La tabla de la base de datos
     * @var string
     */
    protected $table = false;

    /**
     * Campo identificador
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * Campos de la tabla, menos identificador
     * @var array 
     */
    protected $fields = array();


    /**
     * Constructor para los modelos
     * 
     * @param array $attributes
     */
    public function __construct(array $attributes = []) 
    {  
        // Permite instanciar el objeto con propiedades
        if ($attributes) {
            foreach ($attributes as $key => $attribute) {
                $this->$key = $attribute;
            }
        }
        
        /* Obtenemos el nombre de los campos de la tabla a partir del esquema 
         * sin el campo identificador, que será declarado en $primaryKey
         * Haciendo esto, simplificamos código para extraer la clave primaria
         * para cada motor de base de datos
         */
        if (empty($this->fields)) {
            $fields = DB::schema($this->table);
            foreach ($fields as $field) {
                if ($this->primaryKey !== $field['column_name']) {
                    $this->fields[] = $field['column_name'];
                }
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
        
        // Obtenemos los datos del objeto
        $model->id = $id;
        foreach ($model->fields as $field) {
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
        
        // Creamos la consulta
        $fields = implode(', ', $model->fields);
        $statements = preg_replace('#([\w]+)#', ':${1}', $fields);
        $sql = "INSERT INTO $model->table ($fields)
                VALUES ($statements)";
        
        // Asignamos los valores de los campos
        foreach ($model->fields as $field) {
            $params[$field] = (isset($this->$field)) ? $this->$field : null;
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
        $schema = $model->fields;
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
}
