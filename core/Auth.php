<?php

namespace core;

use core\DB;
use core\Hash;

class Auth
{
    /**
     * Comprueba si se ha iniciado sesión.
     *
     * @return bool
     */
    public static function check()
    {
        return (isset($_SESSION["user"])) ? true : false;
    }

    /**
     * Inicia sesión con los datos pasados por parámetro
     *
     * @param $identity
     * @param $password
     *
     * @return bool
     */
    public static function login($identity, $password)
    {
        $sql = 'SELECT * FROM users WHERE email= :email';
        $params = ['email' => $identity];
        
        $user = DB::query($sql, $params);

        if ($user) {
            // Verifica que la contraseña sea correcta
            if (Hash::check($password, $user['password'])) {
                
                // La password no será guardada en $_SESSION, por seguridad
                unset($user['password']);
                
                $_SESSION["user"] = $user;
                return true;
            }
        }
        return false;
    }
            
    /**
     * Elimina la sesión del usuario
     *
     * @return void
     */
    public static function logout()
    {
        session_destroy();
    }
    
    /**
     * Datos del usuario que tiene sesión activa
     * 
     * @return object
     */
    public static function user()
    {
        return (self::check()) ? (object) $_SESSION['user'] : false;
    }
    
}
