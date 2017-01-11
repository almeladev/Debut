<?php

namespace core;

class Paginator 
{

    /**
     * Las propiedades de la paginación
     * 
     * @var array   
     */
    private $_properties = array();
    
    /**
     * Configuración por defecto
     * 
     * @var array  
     */
    public $_defaults = array(
      'page' => 1,
      'perPage' => 10 
    );
    
    /**
     * Constructor
     * 
     * @param array $array
     * @param int   $perPage
     * 
     * @return void
     */
    public function __construct($array, $perPage = null)
    {
      $this->array   = $array;
      $this->curPage = (!isset($_GET['page']) ? $this->_defaults['page'] : $_GET['page']);
      $this->perPage = ($perPage == null) ? $this->_defaults['perPage'] : $perPage;
    }

    /**
     * Obtiene los resultados de la paginación
     * 
     * @return array Elementos de la pagina
     */
    public function getResults()
    {
        // Asigna la página usando el método GET, sino hay página asume que es la expecificada por defecto (1)
        $this->page = (empty($this->curPage) !== false) ? $this->curPage : $this->page = $this->_defaults['page'];
      
        // El tamaño del array
        $this->length = count($this->array);

        // Número de páginas
        $this->pages = ceil($this->length / $this->perPage);

        // Calcula el punto de partida
        $this->start = ceil(($this->page - 1) * $this->perPage);
      
        // Validaciones para los resultados (manipulación URL)
        if (count($this->array) <= $this->start) {
            return redirect('?page=' . $this->pages);
        } elseif ($this->page <= 0) {
            return redirect($_GET['url']);
        } elseif (!is_numeric($this->page)) {
            return redirect($_GET['url']);
        }
        
        // retorna la porción del resultado, si la página no existe le redirige a la última existente
        return array_slice($this->array, $this->start, $this->perPage);
    }
    
    /**
     * Obtiene los enlaces html para el desplazamiento por páginas
     * 
     * @return mixed|bool
     */
    public function getLinks()
    {
      // Inicializa el conjunto de enlaces
      $links  = array();
      
      // Solo seguimos si tenemos más de una página
      if (($this->pages) > 1) {
          
        // Si no está en la primera página, asigna el enlace "Anterior"
        if ($this->page != 1) {
            $links[] = '<li><a href="?page='.($this->page - 1).'">&laquo; Anterior</a></li>';
        }
        
        // Asigna todos los números al array
        for ($i = 1; $i < ($this->pages + 1); $i++) {
            if ($this->page == $i) {
                $links[] = '<li class="active"><a>'.$i.'</a></li>'; // Añade estilos a la página activa
            } else {
                $links[] = '<li><a href="?page='.$i.'">'.$i.'</a></li>';
            }
        }
        
        // Si no está en la última página, asigna el enlace "Siguiente"
        if ($this->page < $this->pages) {
            $links[] = '<li><a href="?page='.($this->page + 1).'"> Siguiente &raquo; </a></li>';
        }
        
        // Devuelve el array de links como un string
        return implode($links);
      }
      
      return false;
    }
    
    /**
     * Obtiene la propiedad del array de propiedades
     * si existe
     * 
     * @param string $name El nombre de la propiedad
     * 
     * @return mixed El valor de la propiedad o false
     */
    public function __get($name)
    {
      if (array_key_exists($name, $this->_properties)) {
        return $this->_properties[$name];
      }
      return false;
    }
    
    /**
     * Asigna propiedades
     * 
     * @param string $name
     * @param string $value
     * 
     * @return void
     */
    public function __set($name, $value) 
    { 
      $this->_properties[$name] = $value;
    }
}