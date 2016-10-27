<?php

namespace app\Models;

use core\DB;
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
     * Campos de la base de datos que
     * pueden ser asignados
     *
     * @var mixed
     */
    protected $fillable = [
        'email', 'name', 'lastname', 'username', 'password', 'age',
    ];

}
