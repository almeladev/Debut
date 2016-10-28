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
     * Campos de la base de datos que
     * pueden ser asignados
     * @var mixed
     */
    protected $fillable;
        
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
        $result = DB::query($sql);

        return $result;
    }

    /**
     * El modelo con identificador elegido
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
        foreach ($model->fillable as $field) {
            $model->$field = $query[$field];
        }
        
        return $model;
    }
    
    /**
     * Guarda los datos del modelo en la
     * base de datos
     *
     * @return void
     */
    public function save()
    {
        $model = new static();
        
        $fields = implode(', ', $model->fillable);
        $statements = preg_replace('#([\w]+)#', ':${1}', $fields);
        
        $sql = "INSERT INTO $model->table ($fields)
                VALUES ($statements)";
        
        foreach ($this->fillable as $field) {
            $params[$field] = $this->$field;
        }

        DB::query($sql, $params, false);
    }
    
    /**
     * Modifica los datos del modelo en la
     * base de datos
     *
     * @return void
     */
    public function update()
    {
        $model = new static();
        
        $fields = implode(', ', $model->fillable);
        $statements = preg_replace('#([\w]+)#', '${1}=:${1}', $fields);
        
        $sql = "UPDATE $model->table SET $statements WHERE id=" . $this->id;

        foreach ($this->fillable as $field) {
            $params[$field] = $this->$field;
        }

        DB::query($sql, $params, false);
    }
    
    /**
     * Elimina los datos del modelo en la
     * base de datos
     *
     * @return void
     */
    public function delete()
    {
        $model = new static();
        
        $sql = "DELETE FROM $model->table WHERE id=" . $this->id;

        DB::query($sql, null, false);
    }
}
