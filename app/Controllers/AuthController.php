<?php

namespace app\Controllers;

use core\Auth;
use core\Controller;
use core\Routing\Router;
use core\View;

class AuthController extends Controller
{
    /**
     * Accede a la vista de login
     *
     * @return void
     */
    public function getLogin()
    {
        if (!Auth::check()) {
            View::template('auth/login.html');
        } else {
            Router::redirect('/users');
        }
    }

    /**
     * Comprueba si el usuario existe en la base de datos
     * e inicia sesión con los datos del usuario
     *
     * @return void
     */
    public function postLogin()
    {
        if (Auth::login($_POST["email"], $_POST["pass"])) {
            Router::redirect('/users');
        } else {
            Router::redirect('/login');
        }
    }

    /**
     * Cierra la sesión del usuario
     *
     * @return void
     */
    public function getLogout()
    {
        Auth::logout();
        Router::redirect('/');
    }
}
