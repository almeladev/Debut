<?php

namespace app\Models;

use core\Model;
use core\DB;

class User extends Model
{
    /**
     * La tabla de la base de datos
     *
     * @var string
     */
    protected $table = 'users';
    
    /**
     * Relacion con la tabla posts. Obtiene
     * Todos los posts con su autor
     * 
     * @return array
     */
    public static function posts()
    {
        $sql = 'SELECT posts.*, users.name as author ' 
             . 'FROM users ' 
             . 'RIGHT JOIN posts on users.id = posts.user_id ' 
             . 'ORDER BY id';
        
        $result = DB::query($sql);
        return $result;
    }

}
