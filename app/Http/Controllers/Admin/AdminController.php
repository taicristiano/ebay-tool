<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Instantiate a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * view dashboard
     * @return view
     */
    public function index()
    {
        return view('admin.index');
    }
}

