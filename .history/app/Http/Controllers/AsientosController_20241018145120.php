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

            $asientoM = new AsientoContableModel();
            $siguiente_asiento = $asientoM->getMaxNroAsiento() + 1;
            $data = array("nro_asiento"=>$siguiente_asiento, 'isModal' => true);

            return view('asientos.agregarAsientos', $data);
        }

        return view('asientos.agregarAsientos', ['isModal' => false]);
    }

    /**
     *
     */
    public function getAsientos(Request $request, Response $response)
    {
        if ($request->ajax()) {
            $start = $request->input('start', 0);  // Desplazamiento (inicio de los datos)
            $length = $request->input('length', 10);  // Número de registros a mostrar por página
            $searchValue = $request->input('search')['value'];
            $solapa = $request->input('solapa');

            // Llamar al método getDataTable del modelo para obtener los datos
            $asientoM = new AsientoContableModel();
            $cuentas = $asientoM->getDataTable($start, $length, $searchValue, $solapa);
            $totalRecords = $asientoM->getTotalRecords();
            $totalFilteredRecords = $asientoM->getFilteredRecords($searchValue, $totalRecords);

            // Devolver la respuesta en formato JSON para DataTables
            return response()->json([
                'draw' => $request->input('draw'),  // Lo envía DataTables para la sincronización
                'recordsTotal' => $totalRecords,    // Total de registros en la tabla
                'recordsFiltered' => $totalFilteredRecords,  // Total de registros después del filtro
                'data' => $cuentas // Los datos paginados
            ]);
        }
    }

    /**
     *
     */
    public function guardarAsiento(Request $request, Response $response)
    {
        $user = $request->user();


        $validated = $request->validate([
            'fecha' => 'required|string|max:10',
            'descripcion' => 'required|string|max:100'
        ]);
        $validated['usuario_id'] = $user->idusuario ?? null;
        if (is_null($validated['usuario_id'])) {
            // Manejar el caso de valor nulo aquí, lanzar excepción o redirigir
            throw new \Exception("El campo usuario_id no puede ser nulo.");
        }
        $AsientoM = new AsientoContableModel();
        $AsientoM->insertarAsiento($validated);
        return response()->json(['message' => 'Datos guardados correctamente']);
    }
}
