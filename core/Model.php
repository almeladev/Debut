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
     * Campos de la base de datos
     * @var mixed
     */
    protected $fields;
    
    
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

        $sql    = 'SELECT * FROM ' . $model->table;
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
        
        // Obtenemos los datos del objeto en un array para tratarlos en la vista
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
        
        $fields = implode(', ', $model->fields);
        $statements = preg_replace('#([\w]+)#', ':${1}', $fields);
        
        $sql = "INSERT INTO $model->table ($fields)
                VALUES ($statements)";
        
        foreach ($this->fields as $field) {
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
        
        $fields = implode(', ', $model->fields);
        $statements = preg_replace('#([\w]+)#', '${1}=:${1}', $fields);
        
        $sql = "UPDATE $model->table SET $statements WHERE id=" . $this->id;

        foreach ($this->fields as $field) {
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
}
