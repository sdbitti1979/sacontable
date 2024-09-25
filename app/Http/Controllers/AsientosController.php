<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class AsientosController extends Controller
{
    public function asientos(Request $request)
    {
        $vista = view('asientos.asientos');

        return $vista;
    }


    public function agregarAsiento(Request $request, Response $response)
    {
        $data = array();
        return view('asientos.agregarAsientos', $data);
    }
}
