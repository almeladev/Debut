<?php

namespace core;

class Request
{
    /**
     * Las peticiones
     * 
     * @var array 
     */
    private $requests = [];
    
    public function __construct()
    {
        $this->requests = $this->mergeData($_POST, $_FILES);
    }
    
    /**
     * Fusiona datos de POST y FILES
     * 
     * @param array $post
     * @param array $files
     * 
     * @return array fusionado
     */
    private function mergeData(array $post, array $files)
    {
        foreach($post as $key => $value) {
            if(is_string($value)) { 
                $post[$key] = trim($value); 
            }
        }
        return array_merge($files, $post);
    }
    
    /**
     * Acceso a los datos de las peticiones
     * 
     * @param string $key
     * 
     * @return mixed
     */
    public function input($key)
    {
        return array_key_exists($key, $this->requests) ? $this->requests[$key] : null;
    }
    
    /**
     * Obtener todas las requests
     * 
     * @return array
     */
    public function all()
    {
        return $this->requests;
    }
}

