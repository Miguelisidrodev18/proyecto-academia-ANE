<?php

namespace App\Http\Controllers;

use App\Models\Plan;

class HomeController extends Controller
{
    public function index()
    {
        $planes = Plan::where('activo', true)->orderBy('precio')->get();

        return view('home', compact('planes'));
    }
}
