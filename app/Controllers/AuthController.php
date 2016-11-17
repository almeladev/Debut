<?php

namespace app\Controllers;

use core\Auth;
use core\Controller;
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
            View::template('auth/login.twig.html');
        } else {
            return redirect('/menu');
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
        if (Auth::login($this->request->input('email'), $this->request->input('pass'))) {
            return redirect('/menu');
        } else {
            return redirect('/login');
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
        return redirect('/');
    }
}
