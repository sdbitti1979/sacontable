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


    public function registrarAsiento(Request $request)
    {
        // Validación de datos del formulario
        $validatedData = $request->validate([
            'fecha' => 'required|date_format:d/m/Y H:i:s',
            'descripcion' => 'required|string|max:255',
            'nro_asiento' => 'required|integer',
            'cuentas' => 'required|array',
            'cuentas.*.cuenta_id' => 'required|integer',
            'cuentas.*.monto' => 'required|numeric',
            'cuentas.*.tipo' => 'required|in:debe,haber', // debe o haber
        ]);

        // Inicializamos los totales de debe y haber
        $totalDebe = 0;
        $totalHaber = 0;

        // Iniciamos una transacción para garantizar consistencia en la base de datos
        DB::beginTransaction();

        try {
            // Creamos el asiento contable
            $asiento = new AsientoContable();
            $asiento->fecha = $request->fecha;
            $asiento->descripcion = $request->descripcion;
            $asiento->nro_asiento = $request->nro_asiento;
            $asiento->usuario_id = auth()->id(); // Asignamos el ID del usuario actual
            $asiento->save();

            // Recorremos las cuentas que se van a agregar en el asiento contable
            foreach ($request->cuentas as $cuentaData) {
                $detalle = new AsientoContableDetalle();
                $detalle->asiento_id = $asiento->id;
                $detalle->cuenta_id = $cuentaData['cuenta_id'];
                $detalle->monto = $cuentaData['monto'];

                // Asignamos el monto a "Debe" o "Haber" y sumamos a los totales
                if ($cuentaData['tipo'] === 'debe') {
                    $detalle->debe = $cuentaData['monto'];
                    $detalle->haber = 0;
                    $totalDebe += $cuentaData['monto'];
                } else {
                    $detalle->haber = $cuentaData['monto'];
                    $detalle->debe = 0;
                    $totalHaber += $cuentaData['monto'];
                }

                // Guardamos el detalle del asiento contable
                $detalle->save();
            }

            // Verificamos que el total del debe sea igual al total del haber
            if ($totalDebe !== $totalHaber) {
                // Si no están equilibrados, hacemos rollback y retornamos error
                DB::rollBack();
                return response()->json(['error' => 'El asiento no está balanceado. El total del Debe no es igual al total del Haber.'], 400);
            }

            // Si todo está bien, confirmamos la transacción
            DB::commit();

            return response()->json(['message' => 'Asiento registrado correctamente']);
        } catch (\Exception $e) {
            // Si algo falla, hacemos rollback de la transacción
            DB::rollBack();
            return response()->json(['error' => 'Hubo un error al registrar el asiento: ' . $e->getMessage()], 500);
        }
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
