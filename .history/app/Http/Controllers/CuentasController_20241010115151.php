<?php

namespace App\Http\Controllers;

use App\Models\CatNombresModel;
use App\Models\ClasificacionesModel;
use App\Models\CuentasModel;
use GuzzleHttp\Psr7\Response;
use Hamcrest\Arrays\IsArray;
use Illuminate\Http\Request;

/**
 *
 */
class CuentasController extends Controller
{
    public function cuentas(Request $request)
    {
        $vista = view('cuentas.cuentas');

        return $vista;
    }

    // Mostrar el formulario para crear una nueva cuenta
    public function agregarCuenta()
    {
        //$catNombresM = new CatNombresModel();
        $catClasificacionM = new ClasificacionesModel();


        $data = array("clasificaciones" => $catClasificacionM->getClasificaciones());

        return view('cuentas.agregarCuenta', $data);
    }

    public function editarCuenta(Request $request){
        $idcuenta = $request->input("idcuenta");
        $cuentasM = new CuentasModel();

        $datos = $cuentasM->getCuentaById($idcuenta);

        $catClasificacionM = new ClasificacionesModel();
        $data = array("clasificaciones" => $catClasificacionM->getClasificaciones(), "datos"=> $datos);

        return view('cuentas.editarCuenta', $data);
    }

    public function getCatNombres(Request $request)
    {
        $nombre = $request->only("nombre");

        $catNombresM = new CatNombresModel();
        $data = $catNombresM->getCatalogoNombres($nombre["nombre"]);

        return response()->json($data);
    }

    public function getCuentas(Request $request){
        $filtro = $request->input('descripcion');

        if (empty($filtro)) {
            // Si no hay código, devolver una respuesta vacía
            return response()->json([]);
        }
        $cuentasM = new CuentasModel();
        $cuentas = $cuentasM->getCuentas($filtro);

        $result = array_map(function ($cuenta) {
            return [
                'label' => $cuenta['nombre'],  // Texto que se muestra en el autocomplete
                'value' => $cuenta['nombre'],  // Valor que se completa en el campo
                'id' => $cuenta['idcuenta']    // ID de la cuenta para usar en el campo oculto
            ];
        }, $cuentas);

        return response()->json($result);

    }

    public function verificarNombre(Request $request){

        $filtro = $request->input('descripcion');

        if (empty($filtro)) {
            // Si no hay código, devolver una respuesta vacía
            return response()->json([]);
        }
        $cuentasM = new CuentasModel();
        $cuentas = $cuentasM->verificarNombre($filtro);

        $result = (isset($cuentas) && !empty($cuentas)) ? array("vacio"=> false) : array("vacio"=>true);

        return response()->json($result);
    }


    public function verificarCodigo(Request $request){

        $filtro = $request->input('descripcion');

        if (empty($filtro)) {
            // Si no hay código, devolver una respuesta vacía
            return response()->json([]);
        }
        $cuentasM = new CuentasModel();
        $cuentas = $cuentasM->verificarCodigo($filtro);

        $result = (isset($cuentas) && !empty($cuentas)) ? array("vacio"=> false) : array("vacio"=>true);

        return response()->json($result);
    }


    // Almacenar una nueva cuenta en la base de datos
    public function guardarCuenta(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'catnombre' => 'required|int',
            'codigo' => 'required|string|max:255|unique:cuentas,codigo',
            'clasificacion' => 'nullable|exists:clasificaciones,idclasificacion',
            'saldoActual' => 'nullable|numeric',
            'cuentaPadre' => 'nullable|exists:cuentas,idcuenta',
            'recibeSaldo' => 'required|string|max:1',

        ]);

        $user = $request->user();
        $validated['usuario_id'] = $user->idusuario ?? null;
        $cuentasM = new cuentasModel();
        $cuentasM->crearCuenta($validated);

        // return redirect()->route('cuentas.index')->with('success', 'Cuenta creada con éxito.');
    }

    public function actualizarCuenta(Request $request){

    }

    // Mostrar el formulario para editar una cuenta existente
    public function edit($id)
    {
        $cuenta = CuentasModel::findOrFail($id);
        return view('cuentas.editarCuenta', compact('cuenta'));
    }

    // Actualizar una cuenta en la base de datos
    public function update(Request $request, $id)
    {
        $cuenta = CuentasModel::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:255|unique:cuentas,codigo,' . $id,
            'clasificacion_id' => 'nullable|exists:clasificaciones,id',
            'saldo_actual' => 'nullable|numeric',
            'id_padre' => 'nullable|exists:cuentas,idcuenta',
        ]);

        $cuenta->update($validated);

        return redirect()->route('cuentas.index')->with('success', 'Cuenta actualizada con éxito.');
    }

    // Eliminar una cuenta
    public function destroy(Request $request)
    {
        $id = $request->input("idcuenta");

        $cuentasM = new cuentasModel();
        if($cuentasM->eliminarCuenta($id)){
            return response()->json(array("type"=>"success", "msg"=>"'Cuenta eliminada con éxito.'"));
        }else{
            return response()->json(array("type"=>"success", "msg"=>"'No se pudo eliminar la cuenta.'"));
        }


    }


    public function lista(Request $request)
    {

        $start = $request->input('start', 0);  // Desplazamiento (inicio de los datos)
        $length = $request->input('length', 10);  // Número de registros a mostrar por página
        $searchValue = $request->input('search')['value'];
        $solapa = $request->input('solapa');

        // Llamar al método getDataTable del modelo para obtener los datos
        $cuentasM = new CuentasModel();
        $cuentas = $cuentasM->getDataTable($start, $length, $searchValue, $solapa);
        $totalRecords = $cuentasM->getTotalRecords();
        $totalFilteredRecords = $cuentasM->getFilteredRecords($searchValue, $totalRecords);

        // Devolver la respuesta en formato JSON para DataTables
        return response()->json([
            'draw' => $request->input('draw'),  // Lo envía DataTables para la sincronización
            'recordsTotal' => $totalRecords,    // Total de registros en la tabla
            'recordsFiltered' => $totalFilteredRecords,  // Total de registros después del filtro
            'data' => $cuentas // Los datos paginados
        ]);
    }
}
