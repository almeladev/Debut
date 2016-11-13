<?php

namespace app\Controllers;

use core\Controller;
use core\View;

class HomeController extends Controller
{
    /**
     * Accede a la vista del HOME
     *
     * @return void
     */
    public function index()
    {
        View::template('index.html', [
            'title' => 'Debut framework',
        ]);
    }
    
    /**
     * Accede a la vista del menú
     *
     * @return void
     */
    public function menu()
    {
        View::template('menu.html', [
            'title' => 'Menú',
        ]);
    }
}
