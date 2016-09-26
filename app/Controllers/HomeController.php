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
}
