<?php

namespace App\Http\Controllers\Admin;

class AdminController extends AbstractController
{
    /**
     * view dashboard
     * @return view
     */
    public function index()
    {
        return view('admin.index');
    }
}
