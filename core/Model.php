<?php

namespace core;

use core\DB;
use core\ORM;
use core\Config;
use core\Paginator;
use core\Collection;

abstract class Model extends ORM
{
    /**
     * La tabla de la base de datos
     * 
     * @var string
     */
    protected $table = false;

    /**
     * El nombre del campo identificador
     * 
     * @var string
     */
    protected $primaryKey;
    
   /**
    * Los atributos del modelo
    * 
    * @var array
    */
    protected $attributes = array();
    
    /**
     * Las reglas del modelo
     * 
     * @var array
     */
    protected $rules = array();
    
    /**
     * Los errores del modelo
     * 
     * @var array
     */
    protected $errors = array();
    
    /**
     * Comprueba si existe o no el modelo
     * 
     * @var bool 
     */
    public $exists = false;
    
    /**
     * Reglas para los atributos del modelo, 
     * método obligatorio
     */
    abstract protected function rules();
    
    /**
     * Constructor para los modelos
     * 
     * @param array $attributes
     * 
     * @return void
     */
    public function __construct(array $attributes = array()) 
    {
        $this->fill($attributes);
    }

    /**
     * Devuelve todos los registros de la tabla
     * como una colección de objetos
     * 
     * @return \Collection|bool
     */
    public static function all()
    {
        $model = new static();
        $model->primaryKey = Config::get('database.primaryKey');
        
        $sql = 'SELECT * FROM ' . $model->table . ' ORDER BY ' . $model->primaryKey;
        $query = DB::query($sql);

        if ($query) {
            // Obtenemos el nombre del modelo e instanciamos la clase de colecciones
            $classname = get_called_class();
            $collection = new Collection();
            
            foreach ($query as $attributes) {                
                // Instanciamos el item e indicamos que existe
                $item = new $classname($attributes);
                $item->exists = true;
                
                // Agregamos a la colección
                $collection->addItem($item);
            }
            return $collection;
        }
        
        return false;
    }
    
    /**
     * Devuelve todos los registros de la tabla
     * como una colección de objetos paginados
     * 
     * @param int $perPage
     * 
     * @return \Collection|bool
     */
    public static function paginate($perPage = null)
    {
        $model = new static();
        $model->primaryKey = Config::get('database.primaryKey');
        
        $sql = 'SELECT * FROM ' . $model->table . ' ORDER BY ' . $model->primaryKey;
        $query = DB::query($sql);
        
        if ($query) {
            // Obtenemos el nombre del modelo, instanciamos la clase de colecciones y la clase de paginación
            $classname = get_called_class();
            $collection = new Collection();
            $pagination = new Paginator($query, $perPage);

            foreach ($pagination->getResults() as $attributes) {
                // Instanciamos el item e indicamos que existe
                $item = new $classname($attributes);
                $item->exists = true;
                
                // Agregamos a la colección
                $collection->addItem($item);
            }
            
            // Los links para la paginación
            $collection->links = $pagination->getLinks();
            return $collection;
        }
        
        return false;
    }

    /**
     * Obtiene el registro con el identificador elegido como
     * un objeto
     * 
     * @param  int $id
     * 
     * @return \core\Model|bool
     */
    public static function find($id)
    {
        $model = new static();
        $model->primaryKey = Config::get('database.primaryKey');
        
        $sql = 'SELECT * FROM ' . $model->table . ' WHERE ' . $model->primaryKey . ' = :' . $model->primaryKey;
        $params = [$model->primaryKey => $id];
        $query = DB::query($sql, $params);
        
        if ($query) {
            // Añadimos los atributos al modelo
            $model->attributes = $query;
            // Indicamos que existe el modelo y lo retornamos
            $model->exists = true;
            return $model;
        }
        
        return false;
    }
    
    /**
     * Guarda un nuevo modelo y devuelve la instancia
     * 
     * @param array $attributes
     * 
     * @return \core\Model
     */
    public static function create(array $attributes)
    {
        $model = new static($attributes);
        $model->save();
        return $model;
    }

    /**
     * Almacena los atributos del modelo en un array
     * 
     * @param array $attributes
     * @return void
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $attribute) {
            $this->$key = $attribute;
        }
    }
    
    /**
     * Obtiene los errores de validación del modelo
     * 
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * Obtiene los datos de los atributos
     *
     * @param string
     * 
     * @return mixed|bool
     */
    public function __get($key) {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
        return false;
    }

    /**
     * Asigna datos a los atributos
     *
     * @param string
     * @param mixed
     * 
     * @return void
     */
    public function __set($key, $value) {
        $this->attributes[$key] = $value;
    }

    /**
     * Comprueba si existe el atributo
     *
     * @param string
     * 
     * @return bool
     */
    public function __isset($key) {
        return isset($this->attributes[$key]);
    }
}
