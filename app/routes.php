<?php

use core\Routing\Router;

/*
|--------------------------------------------------------------------------
| Rutas de la aplicación
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar todas las rutas de tu aplicación.
| Básicamente indicas las rutas que son permitidas, las cuales pueden
| acceder a un controlador y sus diferentes acciones. También es posible
| asociar una ruta con una función anónima
|
*/

// Welcome :)
$app = 'is awesome';
Router::get('/welcome', function () use ($app) {
    echo "<h1>Welcome :) Debut $app</h1>";
});

// Home
Router::get('/', 'HomeController@index');
Router::get('/menu', 'HomeController@menu');

// login
Router::get('/login', 'AuthController@getLogin');
Router::post('/login', 'AuthController@postLogin');
// logout
Router::get('/logout', 'AuthController@getlogout');

/**
 * CRUD de usuarios.
 */
Router::get('/users', 'UserController@index');                  // READ
Router::post('/users/store', 'UserController@store');           // CREATE
Router::post('/users/update/{id}', 'UserController@update');    // UPDATE
Router::post('/users/delete/{id}', 'UserController@destroy');   // DELETE

/**
 * CRUD de posts
 */
Router::get('/posts', 'PostController@index');                  // READ
Router::post('/posts/store', 'PostController@store');           // CREATE
Router::post('/posts/update/{id}', 'PostController@update');    // UPDATE
Router::post('/posts/delete/{id}', 'PostController@destroy');   // DELETE