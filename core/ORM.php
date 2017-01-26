<?php

namespace core;

use core\Config;
use core\Validator;
use core\Http\Request;

abstract class ORM 
{
    /**
     * El nombre del campo identificador
     * 
     * @var string
     */
    protected $primaryKey;
    
    /**
     * Guarda los datos del modelo en la
     * base de datos
     *
     * @return bool
     */
    public function save()
    {
        // Solo continuar si el modelo no existe
        if ($this->exists) {
            return false;
        }
        
        $model = new static();
        $model->primaryKey = Config::get('database.primaryKey');
        
        // Validamos las Requests con las reglas del modelo, si las hubiera
        if ($this->rules()) {
            $request = new Request();
            $validation = Validator::make($request->all(), $this->rules());

            // Si la validación ha obtenido errores los añadimos al modelo
            if ($validation->fails()) {
                $this->errors = $validation->errors();
                return false;
            }
        }
        
        // Usamos el método insert de DBAL y simplificamos
        $conn = DB::connection();
        $conn->insert($model->table, $this->attributes);
        
        // Obtenemos el identificador del último registro insertado e indicamos que existe el modelo
        $this->{$model->primaryKey} = DB::connection()->lastInsertId();
        $this->exists = true;
        
        return true;
    }
    
    /**
     * Modifica los datos del modelo en la
     * base de datos
     * 
     * @param array $attributes
     * 
     * @return bool
     */
    public function update(array $attributes = [])
    {
        // Solo continuar si el modelo existe
        if (! $this->exists) {
            return false;
        }
        
        $model = new static();
        $model->primaryKey = Config::get('database.primaryKey');
        
        $data = (!$attributes) ? $this->attributes : $attributes;
        
        // Validamos las Requests con las reglas del modelo, si las hubiera
        if ($this->rules()) {
            $request = new Request();
            $validation = Validator::make($request->all(), $this->rules());

            // Si la validación ha obtenido errores los añadimos al modelo
            if ($validation->fails()) {
                $this->errors = $validation->errors();
                return false;
            }
        }
        
        // Usamos el método update de DBAL y simplificamos
        $conn = DB::connection();
        $conn->update($model->table, $data, array($model->primaryKey => $this->{$model->primaryKey}));
        
        return true;
    }
    
    /**
     * Elimina los datos del modelo en la
     * base de datos
     *
     * @return bool
     */
    public function delete()
    { 
        // Solo continuar si el modelo existe
        if (! $this->exists) {
            return false;
        }
        
        $model = new static();
        $model->primaryKey = Config::get('database.primaryKey');
        
        // Usamos el método delete de DBAL y simplificamos
        $conn = DB::connection();
        $conn->delete($model->table, array($model->primaryKey => $this->{$model->primaryKey}));    
        
        return true;
    }
}
