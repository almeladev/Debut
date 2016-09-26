<?php

/**
 * Debut - Framework PHP para los que quieren MVC fácil.
 *
 * @package  Debut
 * @author   Daniel Martínez <danielmartinezalmela@gmail.com>
 * @link     https://github.com/DanMnez/Debut
 * @license  http://opensource.org/licenses/MIT MIT License
 */

// Autocarga de clases con Composer
require_once '../vendor/autoload.php';

// Controlador de errores
error_reporting(E_ALL);
set_error_handler('core\ErrorHandler::errorHandler');
set_exception_handler('core\ErrorHandler::exceptionHandler');

// Inicio de la app
$router = new core\Router\Router();
$router->run();
