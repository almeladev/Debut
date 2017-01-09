<?php

namespace core;

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
     */
    public function __construct(array $attributes = array()) 
    {
        $this->fill($attributes);
    }

    /**
     * Devuelve todos los registros de la tabla
     * como una colección de objetos
     * 
     * @return \Collection
     */
    public static function all()
    {
        $model = new static();

        $sql   = 'SELECT * FROM ' . $model->table . ' ORDER BY ' . $model->primaryKey;
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
     * @return \Collection 
     */
    public static function paginate($perPage = null)
    {
        $model = new static();
        
        $sql    = 'SELECT * FROM ' . $model->table . ' ORDER BY ' . $model->primaryKey;
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
     * un objeto para luego hacer uso de las acciones save, update y delete
     * Si no existe el registro, lanza una excepción
     * 
     * @param  int $id El identificador
     * @return object|bool
     */
    public static function find($id)
    {
        $model = new static();
        
        $sql = 'SELECT * FROM ' . $model->table . ' WHERE ' . $model->primaryKey . ' = :' . $model->primaryKey;
        $params  = [$model->primaryKey => $id];
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
        $insert = $conn->insert($model->table, $this->attributes);
        
        // Si no se ha llegado a insertar registramos el error
        if (! $insert) {
            $this->errors[] = 'El registro no se ha creado';
            return false;
        }
        
        // Obtenemos el identificador del último registro insertado e indicamos que existe el modelo
        $this->{$model->primaryKey} = DB::connection()->lastInsertId();
        $this->exists = true;
        
        return true;
    }
    
    /**
     * Modifica los datos del modelo en la
     * base de datos
     * 
     * @param array $attributes Los atributos del modelo
     * @return bool
     */
    public function update(array $attributes = [])
    {
        // Solo continuar si el modelo existe
        if (! $this->exists) {
            return false;
        }
        
        $model = new static();
        $conn = DB::connection();
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
        $update = $conn->update($model->table, $data, array($model->primaryKey => $this->{$model->primaryKey}));
        
        // Si no se ha llegado a actualizar registramos el error
        if (! $update) {
            $this->errors[] = 'El registro no se ha modificado';
            return false;
        }
        
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
        $conn = DB::connection();
        
        // Usamos el método delete de DBAL y simplificamos
        $delete = $conn->delete($model->table, array($model->primaryKey => $this->{$model->primaryKey}));
        
        // Si no se ha llegado a borrar registramos el error
        if (! $delete) {
            $this->errors[] = 'El registro no se ha borrado';
            return false;
        }
        
        return true;
    }
    
    /**
     * Guarda un nuevo modelo y devuelve la instancia
     * 
     * @param array $attributes
     * @return static
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
     * Get a data by key
     *
     * @param string The key data to retrieve
     * @access public
     */
    public function __get($key) {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
        return false;
    }

    /**
     * Assigns a value to the specified data
     *
     * @param string The data key to assign the value to
     * @param mixed  The value to set
     * @access public
     */
    public function __set($key,$value) {
        $this->attributes[$key] = $value;
    }

    /**
     * Whether or not an data exists by key
     *
     * @param string An data key to check for
     * @access public
     * @return boolean
     * @abstracting ArrayAccess
     */
    public function __isset($key) {
        return isset($this->attributes[$key]);
    }
}
