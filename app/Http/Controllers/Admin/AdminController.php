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
        (new \App\Services\EbayClient)->addFixedPriceItem([]);
        return view('admin.index');
    }
}
