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
            'name' => 'required|max:255',
            'email' => 'required|unique:'.$this->table.','.$this->id.'|max:255',
            'password' => 'required|min:6',
            'newpassword' => 'min:6',
        ];
    }
}
