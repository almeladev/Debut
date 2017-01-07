<?php

namespace core;

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
     * Comprueba si existe o no el modelo
     * 
     * @var bool 
     */
    public $exists = false;
    
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
     * Todos los registros de la tabla
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
     * Todos los registros de la tabla paginados
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
     * @return object
     */
    public static function find($id)
    {
        $model = new static();
        
        $sql = 'SELECT * FROM ' . $model->table . ' WHERE ' . $model->primaryKey . ' = :' . $model->primaryKey;
        $params  = [$model->primaryKey => $id];
        $query = DB::query($sql, $params);
        
        if ($query) {
            // Generamos el objeto con sus atributos
            foreach($query as $key => $field) {
                $model->$key = $field;
            }
            // Indicamos que existe el modelo y lo retornamos
            $model->exists = true;
            return $model;
        } else {
            throw new \Exception('No existe el registro con identificador: ' . $id);
        }
    }
    
    /**
     * Guarda los datos del modelo en la
     * base de datos
     *
     * @return boolean
     */
    public function save()
    {
        // Solo continuar si el modelo no existe
        if ($this->exists) {
            return false;
        }
        
        $model = new static();
        
        // Validamos las Requests
        $request = new Request();
        $validation = Validator::make($request->all(), $this->rules);
        
        // Si la validación ha sido exitosa inserta los datos
        if (! $validation->fails()) {
            // Usamos el método insert de DBAL y simplificamos
            $conn = DB::connection();
            $insert = $conn->insert($model->table, $this->attributes);

            if ($insert) {
                // Obtenemos el identificador del último registro insertado e indicamos que existe el modelo
                $this->{$model->primaryKey} = DB::connection()->lastInsertId();
                $this->exists = true;
                return true;
            }
        }
        //json_encode($validation->errors());
        
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
        
        // Usamos el método update de DBAL y simplificamos
        $update = $conn->update($model->table, $data, array($model->primaryKey => $this->{$model->primaryKey}));
        return ($update) ? true : false;
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
        return ($delete) ? true : false;
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
    
    // --------------------------------------------------------------
    // Métodos mágicos para el array de atributos
    // --------------------------------------------------------------
    
    /**
     * Get a data by key
     *
     * @param string The key data to retrieve
     * @access public
     */
    public function __get($key) {
        return $this->attributes[$key];
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
