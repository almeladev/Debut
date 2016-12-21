<?php

/**
 * Consola de comandos para Debut
 *
 * @package  Debut
 * @author   Daniel Martínez <danielmartinezalmela@gmail.com>
 */

// Define las rutas del framework
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP', ROOT . 'app' . DIRECTORY_SEPARATOR);
define('CORE', ROOT . 'core' . DIRECTORY_SEPARATOR);

// Autoload y archivos requeridos
require_once ROOT . 'vendor/autoload.php';
require_once ROOT . 'libs/helpers.php';

use Symfony\Component\Console\Application;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand;

// Conexión a la base de datos
$db = \core\DB::connection();

// Comandos disponibles
$commands = [
    // Migraciones
    $execute  = new ExecuteCommand(),
    $generate = new GenerateCommand(),
    $migrate  = new MigrateCommand(),
    $status   = new StatusCommand(),
    $version  = new VersionCommand()
    
    // ..
];

// Helpers
$helperSet = new HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($db),
    'dialog' => new \Symfony\Component\Console\Helper\QuestionHelper(),
));

// --------------------------------------------------------------
// Migraciones
// --------------------------------------------------------------
$config = new Configuration($db);
$config_file = \core\Config::get('migrations');

// Configuración personalizada
$config->setName($config_file['name']);
$config->setMigrationsNamespace($config_file['namespace']);
$config->setMigrationsTableName($config_file['table_name']);
$config->setMigrationsDirectory($config_file['directory']);

// Agregamos la configuración para las migraciones
foreach ($commands as $command) {
    $command->setMigrationConfiguration($config);
}
// --------------------------------------------------------------

// Agregamos los helpers y los comandos generados y ejecutamos
$console = new Application;
$console->setHelperSet($helperSet);
$console->addCommands($commands);

$console->run();