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
     * Reglas para la validación
     * 
     * @var array
     */
    protected $rules = [
        'name' => 'max:30|required',
        'email' => 'required|unique:users',
        'password' => 'required|min:6',
    ];

}
