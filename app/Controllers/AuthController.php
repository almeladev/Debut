<?php

namespace app\Controllers;

use core\Auth;
use app\Models\User;
use core\Controller;
use core\Http\Request;

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
    public function postLogin(Request $request)
    { 
        if (Auth::login($request->input('email'), $request->input('password'))) {
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
    
    public function postRegister(Request $request)
    {   
        $user = User::create($request->all());
        
        if(! $user->getErrors()){
            return redirect('/login')->with('success', 'Ya se ha registrado en el sistema!');
        }
        
        return redirect()->back()->with(['danger', 'post'], [$user->getErrors(), $request->all()]);
    }
}
