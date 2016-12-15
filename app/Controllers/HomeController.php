<?php

namespace app\Controllers;

use core\Controller;
use core\Auth;

class HomeController extends Controller
{
    /**
     * Accede a la vista del HOME
     *
     * @return void
     */
    public function index()
    {
        return view('index.twig', ['title' => 'Debut framework']);
    }
    
    /**
     * Accede a la vista del menú
     *
     * @return void
     */
    public function menu()
    {
        if (Auth::check()) {
            return view('menu.twig', ['title' => 'Menú']);
        }
        
        // Acceso denegado (falta de permisos)
        //return view('');
        
        return redirect('/');
    }
}
