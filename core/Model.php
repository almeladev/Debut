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
     * Todos los registros de la base de
     * datos
     *
     * @return array
     */
    public static function all()
    {
        $model = new static();

        $sql    = 'SELECT * FROM ' . $model->table;
        $result = Database::query($sql);

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
        $result = Database::query($sql, $params);

        return $result;
    }
}
