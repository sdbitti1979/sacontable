<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CuentasController extends Controller
{
    public function cuentas(Request $request)
    {
        $vista = view('cuentas.cuentas');
        
        return $vista;
    }
}
