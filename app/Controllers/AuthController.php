<?php

namespace app\Controllers;

use core\Auth;
use core\Controller;

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
            return view('auth/login.twig');
        }
        return redirect('/menu');
    }

    /**
     * Comprueba si el usuario existe en la base de datos
     * e inicia sesión con los datos del usuario
     *
     * @return void
     */
    public function postLogin()
    { 
        if (Auth::login($this->request->input('email'), $this->request->input('password'))) {
            return redirect('/menu');
        }
        return redirect('/login');
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
    
    public function getRegister()
    {
        return view('auth/register.twig');
    }
    
    public function postRegister()
    {
        $errors = Auth::register($this->request->all());
        if($errors){
            return redirect()->back()->with('status', $errors);
        }
        return redirect('/login')->with('status', 'Ya se ha registrado en el sistema!');
    }
}
