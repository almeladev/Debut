<?php

namespace core;

use core\DB;
use core\Paginator;
use core\Validator;
use core\Collection;
use core\Http\Request;

abstract class Model
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
    protected $primaryKey = 'id';
    
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
     * Guarda los datos del modelo en la
     * base de datos
     *
     * @return bool
     */
    public function save()
    {
        // Solo continuar si el modelo no existe
        if ($this->exists) {
            return false;
        }
        
        $model = new static();
        
        // Validamos las Requests con las reglas del modelo, si las hubiera
        if ($this->rules()) {
            $request = new Request();
            $validation = Validator::make($request->all(), $this->rules());

            // Si la validación ha obtenido errores los añadimos al modelo
            if ($validation->fails()) {
                $this->errors = $validation->errors();
                return false;
            }
        }
        
        // Usamos el método insert de DBAL y simplificamos
        $conn = DB::connection();
        $conn->insert($model->table, $this->attributes);
        
        // Obtenemos el identificador del último registro insertado e indicamos que existe el modelo
        $this->{$model->primaryKey} = DB::connection()->lastInsertId();
        $this->exists = true;
        
        return true;
    }
    
    /**
     * Modifica los datos del modelo en la
     * base de datos
     * 
     * @param array $attributes
     * 
     * @return bool
     */
    public function update(array $attributes = [])
    {
        // Solo continuar si el modelo existe
        if (! $this->exists) {
            return false;
        }
        
        $model = new static();
        $data = (!$attributes) ? $this->attributes : $attributes;
        
        // Validamos las Requests con las reglas del modelo, si las hubiera
        if ($this->rules()) {
            $request = new Request();
            $validation = Validator::make($request->all(), $this->rules());

            // Si la validación ha obtenido errores los añadimos al modelo
            if ($validation->fails()) {
                $this->errors = $validation->errors();
                return false;
            }
        }
        
        // Usamos el método update de DBAL y simplificamos
        $conn = DB::connection();
        $update = $conn->update($model->table, $data, array($model->primaryKey => $this->{$model->primaryKey}));
        return true;
    }
    
    /**
     * Elimina los datos del modelo en la
     * base de datos
     *
     * @return bool
     */
    public function delete()
    { 
        // Solo continuar si el modelo existe
        if (! $this->exists) {
            return false;
        }
        
        $model = new static();
        
        // Usamos el método delete de DBAL y simplificamos
        $conn = DB::connection();
        $delete = $conn->delete($model->table, array($model->primaryKey => $this->{$model->primaryKey}));        
        return true;
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
