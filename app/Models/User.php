<?php

namespace app\Models;

use core\Model;

class User extends Model
{
    /**
     * La tabla de la base de datos
     *
     * @var string
     */
    protected $table = 'users';
    
    /**
     * Reglas para los atributos del modelo
     * 
     * @return array
     */
    protected function rules()
    {
        return [
            'name' => 'max:30|required',
            'email' => 'required|unique:'.$this->table.','.$this->id,
            'password' => 'required|min:6',
        ];
    }
}
