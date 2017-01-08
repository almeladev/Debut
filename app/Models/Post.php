<?php

namespace app\Models;

use core\Model;
use core\DB;

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
            'title'   => 'required|unique:posts|max:10',
            'content' => 'required',
        ];
    }
    
    /**
     * Relacion con la tabla users. Obtiene
     * Todos los posts con su autor paginados
     * 
     * @return array
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
            $collection = new \core\Collection();
            $pagination = new \core\Paginator($query);

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
