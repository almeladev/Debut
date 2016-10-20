<?php

/**
 * Debut - Framework PHP para los que quieren MVC fácil.
 *
 * @package  Debut
 * @author   Daniel Martínez <danielmartinezalmela@gmail.com>
 * @link     https://github.com/DanMnez/Debut
 * @license  http://opensource.org/licenses/MIT MIT License
 */

// Rutas de la aplicación
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP', ROOT . 'app' . DIRECTORY_SEPARATOR);
define('CORE', ROOT . 'core' . DIRECTORY_SEPARATOR);

// Autoload
require_once ROOT . 'vendor/autoload.php';

// Reporte de errores
error_reporting(E_ALL);
set_error_handler('core\ErrorHandler::errorHandler');
set_exception_handler('core\ErrorHandler::exceptionHandler');

// Inicio de la app
$router = new core\Routing\Router();
$router->run();
