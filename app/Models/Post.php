<?php

namespace app\Models;

use core\Model;

class Post extends Model
{
    /**
     * La tabla de la base de datos
     *
     * @var string
     */
    protected $table = 'posts';
    
    /**
     * Reglas para la validación
     * 
     * @var array
     */
    public $rules = [
        'title'   => 'required|unique',
        'content' => 'required',
    ];

}
