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
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig_Loader_Filesystem('../app/Views');
            $twig   = new \Twig_Environment($loader);
        }

        echo $twig->render($template, $args);
    }
}
