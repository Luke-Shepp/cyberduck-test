<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

class SalesController extends Controller
{
    public function index(): Renderable
    {
        return view('coffee_sales');
    }
}
