<?php

use core\Router\Router;

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

Router::get('/welcome', function () {
    echo "<h1>Welcome :)</h1>";
});

// Home
Router::get('/', 'HomeController@index');

// login
Router::get('/login', 'AuthController@getLogin');
Router::post('/login', 'AuthController@postLogin');
// logout
Router::get('/logout', 'AuthController@getlogout');

// CRUD de usuarios.
Router::get('/users/', 'UserController@index'); // READ

Router::get('/users/create', 'UserController@create'); // CREATE
Router::post('/users/store', 'UserController@store');

Router::get('/users/edit/{id}', 'UserController@edit'); // UPDATE
Router::post('/users/update/{id}', 'UserController@update');

Router::post('/users/delete/{id}', 'UserController@delete'); // DELETE
