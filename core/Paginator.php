<?php

namespace core;

class Paginator {

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
     * @param array $array   Array que será paginado
     * @param int   $perPage La cantidad de elementos que se deben mostrar por página
     * @return void
     */
    public function __construct($array, $perPage = null)
    {
      $this->array   = $array;
      $this->curPage = (!isset($_GET['page']) ? $this->_defaults['page'] : $_GET['page']);
      $this->perPage = ($perPage == null) ? $this->_defaults['perPage'] : $perPage;
    }
    
    /**
     * Global setter
     * 
     * Utiliza el array de propiedades
     * 
     * @param string $name  El nombre de la propiedad
     * @param string $value El valor de la propiedad
     * @return void
     */
    public function __set($name, $value) 
    { 
      $this->_properties[$name] = $value;
    }
    
    /**
     * Global getter
     * 
     * Obtiene la propiedad del array de propiedades
     * si existe
     * 
     * @param string $name El nombre de la propiedad
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
      
      // retorna la porción del resultado
      return array_slice($this->array, $this->start, $this->perPage);
    }
    
    /**
     * Get the html links for the generated page offset
     * 
     * @param array $params A list of parameters (probably get/post) to
     * pass around with each request
     * @return mixed  Return description (if any) ...
     * @access public 
     */
    public function getLinks()
    {
      // Initiate the links array
      $plinks = array();
      $links  = array();
      $slinks = array();
      
      // If we have more then one pages
      if (($this->pages) > 1) {
        // Assign the 'previous page' link into the array if we are not on the first page
        if ($this->page != 1) {
            $plinks[] = '<li><a href="?page='.($this->page - 1).'">&laquo; Prev</a></li>';
        }
        
        // Assign all the page numbers & links to the array
        for ($j = 1; $j < ($this->pages + 1); $j++) {
          if ($this->page == $j) {
            $links[] = '<li class="active"><a>'.$j.'</a></li>'; // If we are on the same page as the current item
          } else {
            $links[] = ' <li><a href="?page='.$j.'">'.$j.'</a></li>'; // add the link to the array
          }
        }
        
        // Assign the 'next page' if we are not on the last page
        if ($this->page < $this->pages) {
            $slinks[] = '<li><a href="?page='.($this->page + 1).'"> Next &raquo; </a></li>';
        }
        
        // Push the array into a string using any some glue
        return implode(' ', $plinks).implode($this->mainSeperator, $links).implode(' ', $slinks);
      }
      return false;
    }
}