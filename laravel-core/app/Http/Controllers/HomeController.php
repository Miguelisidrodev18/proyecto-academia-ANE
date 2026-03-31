<?php

namespace App\Http\Controllers;

use App\Models\Plan;

class HomeController extends Controller
{
    public function index()
    {
        $planes = Plan::where('activo', true)->where('mostrar_en_landing', true)->orderBy('precio')->get();

        // Pasar solo los planes visibles en landing para el formulario de contacto
        $planesContacto = Plan::where('activo', true)->where('mostrar_en_landing', true)->orderBy('precio')->get();

        return view('home', compact('planes', 'planesContacto'));
    }
}
