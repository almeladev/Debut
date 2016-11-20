<?php

namespace core;

class View
{

    public function __construct()
    {
        //
    }

    /**
     * Agrega el archivo de la vista
     *
     * @param string $view El archivo de la vista
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = "../app/Views/$view"; // Directorio de las vistas

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception('La vista $file no ha sido encontrada');
        }
    }

    /**
     * Agrega el archivo de la vista usando el motor de plantillas Twig
     *
     * @param string $template La plantilla
     * @param array $args Array asociativo con los datos que se le pasen a la vista
     *
     * @return void
     */
    public static function template($template, $args = [])
    {
        $loader = new \Twig_Loader_Filesystem(APP . 'Views');
        
        // Añadir cache para optimizar el renderizado de las vistas ya procesadas (necesita permisos)
        $twig = new \Twig_Environment($loader, array(
            //'cache' => ROOT . 'storage/cache',
        ));
        
        // Añade extensiones útiles para el motor de plantillas => http://twig.sensiolabs.org/doc/extensions/index.html#extensions-install
        $twig->addExtension(new \Twig_Extensions_Extension_Text());
        
        echo $twig->render($template, $args);
    }
}
