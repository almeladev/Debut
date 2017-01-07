<?php

namespace core;

class Validator {
    
    /**
     * Los mensajes por defecto para las reglas fallidas
     * 
     * @var array 
     */
    protected $messages = [
        'required' => 'El :attribute es requerido',
        'unique'   => 'El :attribute ya existe',
        'max'      => 'El :attribute no puede ser mayor que :max',
    ];
    
    /**
     * Las reglas para aplicar a los datos
     * 
     * @var array
     */
    protected $rules = array();
    
    /**
     * Los datos que serán validados
     * 
     * @var array
     */
    protected $data = array();
    
    /**
     * Permite validar cualquier array de atributos sin necesidad
     * de instanciar la clase pasandole algunas reglas y mensajes
     * personalizados
     * 
     * @param array $data
     * @param array $rules
     * @param array $messages
     * 
     * @return \static
     */
    public static function make(array $data, array $rules, array $messages = null)
    {
        $model = new static();
        $model->data  = $data;
        $model->rules = $model->parseRules($rules);
        
        $model->validate();
        return $model;
    }
    
    /**
     * Obtiene las reglas de validación para cada atributo
     * 
     * @param array $rules
     * @return array
     */
    protected function parseRules(array $rules = [])
    {
        foreach ($rules as $key => $rule) {
            $rules[$key] = (is_string($rule)) ? explode('|', $rule) : $rule;
        }
        return $rules;
    }
    
    protected function validate()
    {
        foreach ($this->rules as $rules) {
            foreach ($rules as $rule) {
                
                // Estilo CamelCase para los métodos
                $rule = ucwords($rule);
                
                $method = null;
                $param  = null;
                
                // Comprobar si la regla tiene parámetros
                if (strstr($rule, ':') !== false) {
                    $partsRule   = explode(':', $rule);
                    $method = 'validate'.$partsRule[0];
                    $param  = $partsRule[1];
                    $rule   = $partsRule[0];
                } else {
                    $method = 'validate'. $rule;
                }
                
                if (is_callable(array($this, $method))) {
                    // El método adecuado de validación
                    $this->$method($param);
                } else {
                    throw new \Exception('El método ' . $method . ' no existe');
                }
            }
        }
    }
    
    protected function validateRequired()
    {
        echo "required";
    }
    
    protected function validateUnique()
    {
        echo "unique";
    }
    
    protected function validateMax($param)
    {
        echo "max:$param";
    }
    
    /**
     * Getter de rules
     * 
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }
    
    /**
     * Getter de data
     * 
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
    
}
