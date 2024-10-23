<?php

namespace App\Http\Controllers;

use App\Models\AsientoContableModel;
use App\Models\AsientoCuentaModel;
use App\Models\CuentasModel;
use GuzzleHttp\Psr7\Response;
use Illuminate\Container\Attributes\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

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
            $date = date('d/m/Y');
            $data = array("nro_asiento" => $siguiente_asiento, 'isModal' => true, 'fecha' => $date);

            return view('asientos.agregarAsientos', $data);
        }

        return view('asientos.agregarAsientos', ['isModal' => false]);
    }

    public function detalleAsiento(Request $request)
    {
        if ($request->query('modal') === 'true') {
            $id_asiento = $request->input("id_asiento");
            // Recuperar el asiento contable con sus cuentas asociadas
            $asiento = AsientoContableModel::with('cuentas')->find($id_asiento);
            var_dump($asiento);
            die();
            if (!$asiento) {
                return response()->json(['error' => 'Asiento no encontrado'], 404);
            }
            $data = array('isModal' => true, 'id_asiento' => $id_asiento, 'asiento' => $asiento);

            return view('asientos.detalleAsiento', $data);
        }

        return view('asientos.detalleAsiento', ['isModal' => false]);
    }

    public function getAsiento(Request $request)
    {
        $id_asiento = $request->input("id_asiento");
        // Recuperar el asiento contable con sus cuentas asociadas
        $asiento = AsientoContableModel::with('cuentas')->find($id_asiento);

        if (!$asiento) {
            return response()->json(['error' => 'Asiento no encontrado'], 404);
        }

        return response()->json($asiento);
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

    public function registrarAsiento(Request $request)
    {
        // Validación de los datos recibidos
        $validatedData = $request->validate([
            'fecha' => 'required|date',
            'descripcion' => 'required|string|max:255',
            'nro_asiento' => 'required|integer|unique:asientos_contables,nro_asiento',
            'cuentas' => 'required|array',
            'cuentas.*.cuenta_id' => 'required|integer|exists:cuentas,idcuenta',
            'cuentas.*.debe' => 'nullable|numeric|min:0',
            'cuentas.*.haber' => 'nullable|numeric|min:0',
        ]);
        $user = $request->user();
        $validatedData['usuario_id'] = $user->idusuario ?? null;

        // Iniciar una transacción para asegurar la consistencia
        DB::beginTransaction();

        try {
            // Crear el asiento contable en la tabla 'asientos_contables'
            $asientoContable = AsientoContableModel::create([
                'fecha' => $validatedData['fecha'],
                'descripcion' => $validatedData['descripcion'],
                'usuario_id' => $validatedData['usuario_id'],
                'nro_asiento' => $validatedData['nro_asiento'],
            ]);

            // Recorrer cada cuenta en el request y crear las entradas correspondientes en 'asiento_cuenta'
            foreach ($validatedData['cuentas'] as $cuentaData) {
                $cuenta = CuentasModel::find($cuentaData['cuenta_id']);

                // Validar que no haya conflictos con "Debe" y "Haber"
                $debe = $cuentaData['debe'] ?? 0;
                $haber = $cuentaData['haber'] ?? 0;

                // Registrar el movimiento en la tabla 'asiento_cuenta'
                AsientoCuentaModel::create([
                    'asiento_id' => $asientoContable->idasiento,  // Relacionar con el asiento contable
                    'cuenta_id' => $cuenta->idcuenta,
                    'debe' => $debe,
                    'haber' => $haber,
                    'saldo' => $cuenta->saldo_actual + ($debe - $haber), // Cálculo del saldo
                ]);

                // Actualizar el saldo actual de la cuenta en la tabla 'cuentas'
                $cuenta->saldo_actual += ($debe - $haber);
                $cuenta->utilizada = 'T';
                $cuenta->save();
            }

            // Confirmar la transacción
            DB::commit();

            return response()->json([
                'message' => 'Asiento contable registrado exitosamente.',
                'asiento' => $asientoContable,
            ], 201);
        } catch (\Exception $e) {
            // Si algo falla, deshacer la transacción
            DB::rollBack();

            return response()->json([
                'error' => 'Ocurrió un error al registrar el asiento contable.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
