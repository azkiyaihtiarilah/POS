<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function foodBeverage()
    {
        return view('food-beverage'); // Tanpa "products."
    }

    public function beautyHealth()
    {
        return view('beauty-health');
    }

    public function homeCare()
    {
        return view('home-care');
    }

    public function babyKid()
    {
        return view('baby-kid');
    }
}