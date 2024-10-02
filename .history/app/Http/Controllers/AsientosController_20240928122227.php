<?php

namespace App\Http\Controllers;

use App\Models\AsientoContableModel;
use GuzzleHttp\Psr7\Response;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class AsientosController extends Controller
{
    /**
     *
     */
    public function asientos(Request $request)
    {
        $vista = view('asientos.asientos');

        return $vista;
    }

    /**
     *
     */
    public function agregarAsiento(Request $request, Response $response)
    {
        if ($request->query('modal') === 'true') {
            //var_dump("true");
            return view('asientos.agregarAsientos', ['isModal' => true]);
        }

        return view('asientos.agregarAsientos', ['isModal' => false]);
    }

    /**
     *
     */
    public function getAsientos(Request $request, Response $response)
    {
        if ($request->ajax()) {
            // Llama al método del modelo que maneja la lógica de DataTable
            return (new AsientoContableModel())->getDataTable();
        }
    }

    /**
     *
     */
    public function guardarAsiento(Request $request, Response $response){
        $user = $request->user();


        $validated = $request->validate([
            'fechaasiento' => 'required|string|max:10',
            'descripcionAsiento' => 'required|string|max:100'
        ]);

        $AsientoM = new AsientoContableModel();


    }
}
