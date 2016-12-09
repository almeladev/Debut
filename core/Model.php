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
     * El nombre del campo identificador
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * El id del modelo
     * @var mixed 
     */
    protected $id = false;

    /**
     * Comprueba si existe o no el modelo
     * @var boolean 
     */
    protected $exists = false;
    
    /**
     * Constructor para los modelos
     * 
     * @param array $attributes
     */
    public function __construct(array $attributes = []) 
    {  
        // Permite instanciar el modelo con propiedades
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
     * @return array | boolean
     */
    public static function all()
    {
        $model = new static();

        $sql    = 'SELECT * FROM ' . $model->table . ' ORDER BY ' . $model->primaryKey;
        $query = DB::query($sql);

        return ($query) ? $query : false;
    }

    /**
     * Obtiene el registro con el identificador elegido como
     * un objeto para luego hacer uso de las acciones save, update y delete
     * Si no existe el registro, lanza una excepción
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
        
        if ($query) {
            // Obtenemos el nombre de las columnas
            $columns = $model->getColumnsWithoutId();
            // Generamos el objeto con sus atributos
            foreach($columns as $column) {
                $model->attributes[$column] = $query[$column];
            }
            // Indicamos que existe el modelo, añadimos su identificador y lo retornamos
            $model->id = $query[$model->primaryKey];
            $model->exists = true;
            return $model;
        } else {
            throw new \Exception('No existe el registro con identificador: ' . $id);
        }
    }
    
    /**
     * Guarda los datos del modelo en la
     * base de datos
     *
     * @return boolean
     */
    public function save()
    {
        // Solo continuar si el modelo no existe
        if ($this->exists) {
            return false;
        }
        
        $model = new static();
        
        // Obtenemos las columnas de la tabla
        $columns = $this->getColumnsWithoutId();
                
        // Creamos la consulta
        $fields = implode(', ', $columns);
        $stmt = preg_replace('#([\w]+)#', ':${1}', $fields);
        $sql = "INSERT INTO $model->table ($fields) VALUES ($stmt)";
        
        // Asignamos los valores de los campos
        foreach ($columns as $field) {
            $this->attributes[$field] = (isset($this->$field)) ? $this->$field : null;
            unset($this->$field);
        }
        
        // Hacemos la consulta a la BBDD y comprobamos resultado
        $query = DB::query($sql, $this->attributes, false);
        
        if ($query) {
            // Obtenemos el identificador del último registro insertado e indicamos que existe el modelo
            $this->id = DB::connection()->lastInsertId();
            $this->exists = true;
            return true;
        }
        return false;
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
        // Solo continuar si el modelo existe
        if (! $this->exists) {
            return false;
        }
        
        $model = new static();
        
        // Obtenemos las columnas de la tabla
        $columns = $this->getColumnsWithoutId();
        
        // Creamos la consulta
        $fields = implode(', ', $columns);
        $stmt = preg_replace('#([\w]+)#', '${1}=:${1}', $fields);
        $sql = "UPDATE $model->table SET $stmt WHERE id=" . $this->id;
        
        // Asignamos los nuevos valores a los campos
        // Si no existen, los valores serán los que ya tiene el modelo
        if (!$attributes) {
            foreach ($columns as $field) {
                $this->attributes[$field] = (isset($this->$field)) ? $this->$field : $this->attributes[$field];
            }
        } else {
            foreach ($columns as $field) {
                $this->attributes[$field] = (isset($attributes[$field])) ? $attributes[$field] : $this->attributes[$field];
            }
        }
        
        // Hacemos la consulta a la BBDD y comprobamos resultado
        $query = DB::query($sql, $this->attributes, false);
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
        // Solo continuar si el modelo existe
        if (! $this->exists) {
            return false;
        }
        
        $model = new static();
        
        // Creamos la consulta
        $sql = "DELETE FROM $model->table WHERE id=" . $this->id;
        
        // Hacemos la consulta a la BBDD y comprobamos resultado
        $query = DB::query($sql, null, false);
        return ($query) ? true : false;
    }
    
    /**
     * Obtiene las columnas que tendrá el modelo sin el identificador de los campos. 
     * Esto permitirá hacer inserciones de nuevos registros sin
     * contar con el campo autoincremental. Que lo gestionará
     * automáticamente la BBDD
     * 
     * @return array Las columnas de la tabla
     */
    private function getColumnsWithoutId()
    {
        // Removemos el identificador de las columnas de la tabla
        // Para no añadir datos sobre el identificador
        // Así aseguramos no poder manipular accidentalmente la clave primaria
        $columns = DB::getNameColumns($this->table);
        foreach($columns as $key => $column) {
            if ($column === $this->primaryKey) {
                unset($columns[$key]);
                break; // Si remueve el identificador, termina el ciclo
            }
        }
        return $columns;
    }
}
