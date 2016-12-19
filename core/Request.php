<?php

namespace core;

class Request
{
    /**
     * Array de los datos pasados por POST
     * 
     * @var array 
     */
    public $data = [];
    
    public function __construct() 
    {
        $this->data = $this->mergeData($_POST, $_FILES);
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
     * Acceso a los datos de $data
     * 
     * @param string $key
     * 
     * @return mixed
     */
    public function input($key)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }
}

