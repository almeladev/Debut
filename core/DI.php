<?php

namespace core;

use ReflectionClass;

/**
 * Simple inyector de dependencias
 * para obtener los objetos de los parámetros
 */
class DI {
    
    
    /**
     * Los parámetros del método del objeto
     * 
     * @var array 
     */
    protected $params = array();

    /**
     * Las instancias de los objetos producidas
     * 
     * @var array 
     */
    protected $instances = array();
    
    /**
     * Instancia todos los objetos de los parámetros del método
     * 
     * @param object $object
     * @param string $method
     * 
     * @return array Array de objetos instanciados con su posición en el método
     */
    public static function make($object, $method)
    {
        $instanciator = new static();
        
        $params = $instanciator->getParameters($object, $method);
        if ($params->hasObjects()) {
            $params->instanceAll();
        }
        
        return $instanciator->instances;
    }
    
    
    /**
     * Permite instanciar automáticamente los parámetros de 
     * tipo objeto en los controladores
     * 
     * @return void
     */
    public function instanceAll()
    {
        foreach ($this->params as $key => $param) {
            $class = $param->getClass();
            if ($class) {
                $this->instances[$key] = new $class->name();
            }
        }
    }
    
    /**
     * Obtiene los parámetros del método de un objeto
     * 
     * @param object $object
     * @param string $method
     * 
     * @return object|bool
     */
    public function getParameters($object, $method)
    {
        $reflection = new ReflectionClass($object);
        $method = $reflection->getMethod($method);
        $this->params = $method->getParameters();
        
        return $this;
    }
    
    /**
     * Comprueba si existen parámetros de tipo objeto
     * 
     * @param array $params
     * 
     * @return bool
     */
    public function hasObjects()
    {
        foreach ($this->params as $param) {
            if ($param->getClass()) {
                return true;
            }
        }
        return false;
    }
}
