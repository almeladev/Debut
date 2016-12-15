<?php

namespace app\Models;

use core\Model;
use core\DB;

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
    
    /**
     * Relacion con la tabla users. Obtiene
     * Todos los posts con su autor
     * 
     * @return array
     */
    public static function withUsers()
    {
        $sql = 'SELECT posts.*, users.name as author ' 
             . 'FROM users ' 
             . 'RIGHT JOIN posts on users.id = posts.user_id ' 
             . 'ORDER BY id';
        
        $result = DB::query($sql);
        return $result;
    }

}
