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
        'min'      => 'El :attribute no puede ser menor que :min',
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
     * Los errores ocurridos durante la validación
     * 
     * @var array
     */
    protected $errors = array();
    
    /**
     * Permite validar cualquier array de atributos sin necesidad
     * de instanciar la clase pasandole algunas reglas y mensajes
     * personalizados
     * 
     * @param array  $data
     * @param array  $rules
     * @param array  $messages
     * 
     * @return \static
     */
    public static function make(array $data, array $rules, array $messages = null)
    {
        $validator = new static();
        $validator->data  = $data;
        $validator->rules = $validator->parseRules($rules);
        
        $validator->validate();
        return $validator;
    }
    
    /**
     * Obtiene las reglas de validación para cada atributo
     * en un array
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
    
    /**
     * Valida los datos llamando a cada método que corresponde 
     * a la regla
     * 
     * @throws \Exception
     */
    protected function validate()
    {
        foreach ($this->rules as $key => $rules) {
            foreach ($rules as $rule) {
                
                $method = null;
                $param  = null; 
   
                // Primera letra en mayúscula (identificar el método)
                $ucRule = ucfirst($rule);
                
                // Comprobar si la regla tiene parámetros
                if (strstr($rule, ':') !== false) {
                    $partsRule   = explode(':', $ucRule);
                    $method = 'validate'.$partsRule[0];
                    
                    // Obtenemos los parámetros y la regla original en minúsculas
                    $param  = $partsRule[1];
                    $rule   = strtolower($partsRule[0]);
                } else {
                    $method = 'validate'. $ucRule;
                }
                
                if (is_callable(array($this, $method))) {
                    // Parámetros de los métodos de validación
                    $value = $this->data[$key];
                    $attribute = $key;
                    
                    // El método adecuado de validación
                    // Si devuelve falso genera un mensaje de error con los datos precisos
                    if (! $this->$method($attribute, $value, $param)) {
                        $this->errors[] = str_replace([':attribute', ":$rule"], [$attribute, $param], $this->messages[$rule]);
                    }
                } else {
                    throw new \Exception('El método ' . $method . ' no existe');
                }
            }
        }
    }
    
    /**
     * Comprueba que el valor existe
     * 
     * @param mixed $value
     * @return bool
     */
    protected function validateRequired($attribute, $value)
    {
        if (is_null($value)) {
            return false;
        } elseif (is_string($value) && trim($value) === '') {
            return false;
        } elseif ((is_array($value) || $value instanceof \Countable) && count($value) < 1) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Comprueba que el valor sea único en la base de datos
     * 
     * @param string $attribute
     * @param mixed  $value
     * @param mixed  $param
     * 
     * @return bool
     */
    protected function validateUnique($attribute, $value, $param)
    {
        $sql   = "SELECT $attribute FROM $param WHERE $attribute = '$value'";
        $query = DB::query($sql);
        
        // Si existe, devuelve false y la validación será incorrecta
        return ($query) ? false : true;
    }
    
    /**
     * Comprueba que la cadena no sea mayor a la permitida
     * 
     * @param string $attribute
     * @param mixed $value
     * @param mixed $param
     * 
     * @return bool
     */
    protected function validateMax($attribute, $value, $param)
    { 
        if (strlen($value) > (int) $param) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Comprueba que la cadena no sea menor a la permitida
     * 
     * @param string $attribute
     * @param mixed $value
     * @param mixed $param
     * 
     * @return bool
     */
    protected function validateMin($attribute, $value, $param)
    { 
        if (strlen($value) < (int) $param) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Muestra los errores ocurridos durante la validación de los datos
     * 
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }
    
    /**
     * Indica si ha ocurrido algún error durante la validación de los datos
     * 
     * @return bool
     */
    public function fails()
    {
        return ($this->errors) ? true : false;
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
