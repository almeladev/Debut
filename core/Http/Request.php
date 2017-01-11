<?php

namespace core\Http;

class Request
{
    /**
     * Las peticiones
     * 
     * @var array 
     */
    protected $requests = [];
    
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
    protected function mergeData(array $post, array $files)
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
    public function input($name)
    {
        return array_key_exists($name, $this->requests) ? $this->requests[$name] : null;
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

