<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

abstract class AbstractController extends Controller
{
    // define shared methods
    
    /**
     * view render
     * @param  array $data
     * @return view
     */
    final protected function render($data = [])
    {
        $called     = debug_backtrace()[1];
        $controller = explode('\\', $called['class']);
        return view('admin.' . str_replace('controller', '', strtolower($controller[count($controller) - 1])) . '.' . $called['function'], $data);
    }
}
