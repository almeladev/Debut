<?php

/*
|--------------------------------------------------------------------------
| Simple demo para Debut
|--------------------------------------------------------------------------
|
| Ejecuta este script desde consola para crear el esquema de la base de
| datos con algunos registros de ejemplo. ¡Es necesario crear antes la 
| base de datos y configurarla! en el archivo app/config.php
|
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
require_once './vendor/autoload.php';

use core\DB;
use Faker\Factory;
use Doctrine\DBAL\Schema\Schema;

// Generamos un nuevo esquema para la base de datos
// Obtenemos información sobre la plataforma
$schema = new Schema();
$conn = DB::connection();
$platform = $conn->getSchemaManager()->getDatabasePlatform();

// --------------------------------------------------------------
// TABLAS
// --------------------------------------------------------------
// Tabla de usuarios
$users = $schema->createTable('users');
$users->addColumn("id", "integer", array("autoincrement" => true));
$users->addColumn("name", "string");
$users->addColumn("email", "string");
$users->addColumn("password", "string");
$users->addColumn("avatar", "string", array("notnull" => false));
$users->setPrimaryKey(array("id"));
$users->addUniqueIndex(array("email"));

// Tabla de posts
$posts = $schema->createTable('posts');
$posts->addColumn("id", "integer", array("autoincrement" => true));
$posts->addColumn("title", "string");
$posts->addColumn("content", "text");
$posts->addColumn("user_id", "integer", array("notnull" => false));
$posts->setPrimaryKey(array("id"));
$posts->addForeignKeyConstraint($users, array("user_id"), array("id"), array("onDelete" => "SET NULL"));

// Ejecutamos el SQL que crea las tablas
$queries = $schema->toSql($platform);
foreach ($queries as $sql) {
    $conn->query($sql);
}

// --------------------------------------------------------------
// DATOS DE PRUEBA
// --------------------------------------------------------------
$faker = Factory::create();

// Admin
$conn->insert('users', [
    'name' => 'admin',
    'email' => 'admin@debut.app',
    'password' => encrypt('secret'),
    'avatar' => 'avatar_admin' . '.png'
]);

// Usuarios y posts aleatorios
$users_num = 50;
for($i = 0;$i < $users_num;$i++) {
    $conn->insert('users', [
        'name'     => $faker->name,
        'email'    => $faker->unique()->email,
        'password' => encrypt('secret'),
        'avatar' => 'avatar_example' . $faker->numberBetween($min = 1, $max = 20) . '.png'
    ]);
}

$posts_num = 80;
for($i = 0;$i < $posts_num;$i++) {
    $conn->insert('posts', [
        'title'   => $faker->unique()->sentence($nbWords = 8, $variableNbWords = true),
        'content' => $faker->text($maxNbChars = 800),
        'user_id' => $faker->numberBetween('1', '51')
    ]);
}

// --------------------------------------------------------------
// Permisos a los directorios
// --------------------------------------------------------------
exec('find  ' . ROOT . 'public' . '-type d -exec chmod 0775 {} +');
exec('find  ' . ROOT . 'storage' . '-type d -exec chmod 0775 {} +');