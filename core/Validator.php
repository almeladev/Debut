<?php

namespace core;

class Validator {
    
    /**
     * Los mensajes para las reglas fallidas
     * 
     * @var type 
     */
    protected $messages = [
        'required' => 'El :attribute es requerido',
        'unique'   => 'El :attribute ya existe',
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
    private function parseRules(array $rules = [])
    {
        foreach ($rules as $key => $rule) {
            $rules[$key] = (is_string($rule)) ? explode('|', $rule) : $rule;
        }
        return $rules;
    }
    
    private function validate()
    {
        echo "hola";
    }
    
    private function validateRequired()
    {
        //
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
