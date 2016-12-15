<?php

namespace core;

class Auth
{
    /**
     * Comprueba si se ha iniciado sesión.
     *
     * @return boolean true si ha iniciado sesión. false si no.
     */
    public static function check()
    {
        if (isset($_SESSION["user"])) {
            return true;
        }
        return false;
    }

    /**
     * Inicia sesión con los datos pasados por parámetro
     *
     * @param $identity El identificador
     * @param $password Contraseña
     *
     * @return boolean true si ha iniciado sesión. false si no
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
        if (self::check()) {
            $sql = "SELECT * FROM users WHERE email= :email";
            $params = ['email' => $_SESSION["user"]];
            
            $result = DB::query($sql, $params);
            
            // Obtiene el array y lo convierte a objecto
            return (object) $result;
        }
        return false;
    }
    
}
