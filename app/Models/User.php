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

//    /**
//     * Guarda los datos del nuevo usuario en la
//     * base de datos
//     *
//     * @return void
//     */
//    public function save()
//    {
//        $sql = "INSERT INTO $this->table (email, name, lastname, username, password, age)
//                VALUES (:email, :name, :lastname, :username, :password, :age)";
//
//        $params = [
//            'email'    => $this->email,
//            'name'     => $this->name,
//            'lastname' => $this->lastname,
//            'username' => $this->username,
//            'password' => $this->password,
//            'age'      => $this->age,
//        ];
//
//        DB::query($sql, $params, false);
//    }

    /**
     * Modifica los datos del usuario en la
     * base de datos
     *
     * @param  int $id El identificador
     *
     * @return void
     */
    public function modify($id)
    {
        $sql = "UPDATE $this->table
                SET email=:email, name=:name, lastname=:lastname, username=:username, password=:password, age=:age
                WHERE id=" . $id;

        $params = [
            'email'    => $this->email,
            'name'     => $this->name,
            'lastname' => $this->lastname,
            'username' => $this->username,
            'password' => $this->password,
            'age'      => $this->age,
        ];

        DB::query($sql, $params, false);
    }

    /**
     * Elimina los datos del usuario en la
     * base de datos
     *
     * @param  int $id El identificador
     *
     * @return void
     */
    public function destroy($id)
    {
        $sql = "DELETE FROM $this->table WHERE id=" . $id;

        DB::query($sql, null, false);
    }
}
