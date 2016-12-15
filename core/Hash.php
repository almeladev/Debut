<?php

namespace core;

class Hash {
    
    /**
     * Encripta una contraseña dada usando el algoritmo
     * de password_hash()
     * 
     * (Warning: The salt option has been deprecated as of PHP 7.0.0. 
     * It is now preferred to simply use the salt that is generated
     * by default)
     * 
     * @param string $password
     * 
     * @return string
     */
    public static function make($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Verifica que una contraseña coincide con un hash
     * 
     * @param string $password
     * @param string $hash
     * 
     * @return bool
     */
    public static function check($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
