<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $priceRange = [
            'min' => House::min('price') ?: 0,
            'max' => House::max('price') ?: 1000000
        ];

        return view('welcome', compact('priceRange'));
    }
} 