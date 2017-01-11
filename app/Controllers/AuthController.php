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
     * @return \core\Routing\Redirector
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
     * @param \core\Http\Request $request
     * 
     * @return \core\Routing\Redirector
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
     * @return \core\Routing\Redirector
     */
    public function getLogout()
    {
        Auth::logout();
        return redirect('/');
    }
    
    /**
     * Accede a la vista de registro
     * 
     * @return void
     */
    public function getRegister()
    {
        return view('auth/register.twig');
    }
    
    /**
     * Crea el usuario nuevo que se ha registrado
     * 
     * @param \core\Http\Request $request
     * 
     * @return \core\Routing\Redirector
     */
    public function postRegister(Request $request)
    {   
        $user = User::create($request->all());
        
        if(! $user->getErrors()){
            return redirect('/login')->with('success', 'Ya se ha registrado en el sistema!');
        }
        
        return redirect()->back()->with(['danger', 'post'], [$user->getErrors(), $request->all()]);
    }
}
