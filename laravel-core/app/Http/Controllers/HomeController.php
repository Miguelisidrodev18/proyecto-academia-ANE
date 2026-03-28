<?php

namespace App\Http\Controllers;

use App\Models\Plan;

class HomeController extends Controller
{
    public function index()
    {
        $planes = Plan::where('activo', true)->where('mostrar_en_landing', true)->orderBy('precio')->get();

        // También pasar todos los planes activos para el formulario de contacto
        $planesContacto = Plan::where('activo', true)->orderBy('precio')->get();

        return view('home', compact('planes', 'planesContacto'));
    }
}
