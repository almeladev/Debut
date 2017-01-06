<?php

namespace core;


/**
 * El modelo base está pensado para facilitar las tareas comunes
 * de los modelos. Implementa la interface ArrayAccess para
 * tratar el array de los atributos
 */
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
     * Comprueba si existe o no el modelo
     * 
     * @var boolean 
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
     * @return Object
     */
    public static function find($id)
    {
        $model = new static();
        
        $sql = 'SELECT * FROM ' . $model->table . ' WHERE ' . $model->primaryKey . ' = :' . $model->primaryKey;
        $params  = [$model->primaryKey => $id];
        $query = DB::query($sql, $params);
        
        if ($query) {
            // Obtenemos el nombre de las columnas
            $columns = $model->getColumnsWithoutId();
            // Generamos el objeto con sus atributos
            foreach($columns as $field) {
                $model->$field = $query[$field];
            }
            // Indicamos que existe el modelo, añadimos su identificador y lo retornamos
            $model->{$model->primaryKey} = $query[$model->primaryKey];
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
        
        // Obtenemos las columnas de la tabla
        $columns = $this->getColumnsWithoutId();
                
        // Creamos la consulta
        $fields = implode(', ', $columns);
        $stmt = preg_replace('#([\w]+)#', ':${1}', $fields);
        $sql = "INSERT INTO $model->table ($fields) VALUES ($stmt)";
        
        // Asignamos los valores de los campos
        foreach ($columns as $field) {
            $this->$field = (isset($this->$field)) ? $this->$field : null;
            $params[$field] = $this->$field;
        }
        
        // Hacemos la consulta a la BBDD y comprobamos resultado
        $query = DB::query($sql, $params, false);
        
        if ($query) {
            // Obtenemos el identificador del último registro insertado e indicamos que existe el modelo
            $this->{$model->primaryKey} = DB::connection()->lastInsertId();
            $this->exists = true;
            return true;
        }
        return false;
    }
    
    /**
     * Modifica los datos del modelo en la
     * base de datos
     * 
     * @param array $attributes
     * @return boolean
     */
    public function update(array $attributes = [])
    {
        // Solo continuar si el modelo existe
        if (! $this->exists) {
            return false;
        }
        
        $model = new static();
        
        // Obtenemos las columnas de la tabla
        $columns = $this->getColumnsWithoutId();
        
        // Creamos la consulta
        $fields = implode(', ', $columns);
        $stmt = preg_replace('#([\w]+)#', '${1}=:${1}', $fields);
        $sql = 'UPDATE ' . $model->table . ' SET ' . $stmt . ' WHERE ' . $model->primaryKey . '=' . $this->{$model->primaryKey};
        
        // Asignamos los nuevos valores a los campos
        // Exceptuando el identificador
        if (!$attributes) {
            foreach ($columns as $field) {
                $this->$field = (! isset($this->$field)) ?: $this->$field;
                if ($this->$field !== $model->primaryKey) {
                    $params[$field] = $this->$field;
                }
            }
        } else {
            foreach ($columns as $field) {
                $this->$field = (isset($attributes[$field])) ? $attributes[$field] : $this->$field;
                if ($this->$field !== $model->primaryKey) {
                    $params[$field] = $this->$field;
                }
            }
        }
        
        // Hacemos la consulta a la BBDD y comprobamos resultado
        $query = DB::query($sql, $params, false);
        return ($query) ? true : false;
    }
    
    /**
     * Elimina los datos del modelo en la
     * base de datos
     *
     * @return boolean
     */
    public function delete()
    { 
        // Solo continuar si el modelo existe
        if (! $this->exists) {
            return false;
        }
        
        $model = new static();
        
        // Creamos la consulta
        $sql = 'DELETE FROM ' . $model->table . ' WHERE ' . $model->primaryKey . '=' . $this->{$model->primaryKey};
        
        // Hacemos la consulta a la BBDD y comprobamos resultado
        $query = DB::query($sql, null, false);
        return ($query) ? true : false;
    }
    
    /**
     * Obtiene las columnas que tendrá el modelo sin el identificador de los campos. 
     * Esto permitirá crear consultas con declaraciones
     * 
     * @return array Las columnas de la tabla
     */
    private function getColumnsWithoutId()
    {
        $columns = DB::getNameColumns($this->table);
        foreach($columns as $key => $column) {
            if ($column === $this->primaryKey) {
                unset($columns[$key]);
                break; // Si remueve el identificador, termina el ciclo
            }
        }
        return $columns;
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
