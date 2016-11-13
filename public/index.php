<?php

/**
 * Debut - Framework PHP extremadamente simple para los que quieren MVC fácil.
 *
 * @package  Debut
 * @author   Daniel Martínez <danielmartinezalmela@gmail.com>
 * @link     https://github.com/DanMnez/Debut
 * @license  http://opensource.org/licenses/MIT MIT License
 */

// --------------------------------------------------------------
// Define las rutas del framework
// --------------------------------------------------------------
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP', ROOT . 'app' . DIRECTORY_SEPARATOR);
define('CORE', ROOT . 'core' . DIRECTORY_SEPARATOR);

// --------------------------------------------------------------
// Autoload
// --------------------------------------------------------------
require_once ROOT . 'vendor/autoload.php';

// --------------------------------------------------------------
// Arranca la aplicación
// --------------------------------------------------------------
$app = new core\Kernel();
$app->run();
