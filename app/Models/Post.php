<?php

namespace app\Models;

use core\DB;
use core\Model;
use core\Paginator;
use core\Collection;

class Post extends Model
{
    /**
     * La tabla de la base de datos
     *
     * @var string
     */
    protected $table = 'posts';
    
    /**
     * Reglas para la validación
     * 
     * @return array
     */
    protected function rules()
    {
        return [
            'title'   => 'required|unique:'.$this->table.','.$this->id.'|max:255',
            'content' => 'required',
        ];
    }
    
    /**
     * Relacion con la tabla users. Obtiene
     * Todos los posts con su autor paginados
     * 
     * @return \core\Collection | bool
     */
    public static function withUsers()
    {
        $sql = 'SELECT posts.*, users.name as author ' 
             . 'FROM users ' 
             . 'RIGHT JOIN posts on users.id = posts.user_id ' 
             . 'ORDER BY id';
        
        $query = DB::query($sql);
        
        if ($query) {
            
            // Obtenemos el nombre del modelo, instanciamos la clase de colecciones y la clase de paginación
            $classname = get_called_class();
            $collection = new Collection();
            $pagination = new Paginator($query);

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
}
