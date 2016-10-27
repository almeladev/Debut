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
    protected $id = 'id';

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
     * El registro con identificador elegido
     *
     * @param  int $id El identificador
     *
     * @return array
     */
    public static function find($id)
    {
        $model = new static();

        $sql    = 'SELECT * FROM ' . $model->table . ' WHERE ' . $model->id . ' = :id';
        $params = ['id' => $id];
        $result = DB::query($sql, $params);

        return $result;
    }
    
    /**
     * Guarda los datos del nuevo usuario en la
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
            $params[$field] = $this->fillable = $this->$field;
        }

        DB::query($sql, $params, false);
    }
}
