<?php

namespace core;

use core\Http\Request;

class Controller
{
    /**
     * Request
     * 
     * @var Request
     */
    public $request = null;
    
    public function __construct(Request $request = null)
    {
        $this->request = $request !== null ? $request : new Request();
    }
}
