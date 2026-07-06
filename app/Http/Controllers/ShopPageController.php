<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopPageController extends Controller
{
    /**
     * Show the shop page with Livewire component
     */
    public function index()
    {
        return view('pages.shop-page');
    }
}
