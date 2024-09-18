<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AsientosController extends Controller
{
    public function asientos(Request $request)
    {
        $vista = view('asientos.asientos');
        
        return $vista;
    }
}
