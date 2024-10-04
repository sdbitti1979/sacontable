<?php

namespace App\Http\Controllers;

use App\Models\CatNombresModel;
use App\Models\ClasificacionesModel;
use App\Models\CuentasModel;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

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

         $data = array("clasificaciones"=>$catClasificacionM->getClasificaciones());

         return view('cuentas.agregarCuenta', $data);
     }

     public function getCatNombres(Request $request){
           $nombre = $request->only("nombre");

           $catNombresM = new CatNombresModel();
           $data = $catNombresM->getCatalogoNombres($nombre["nombre"]);

           return response()->json($data);

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
     public function destroy($id)
     {
         $cuenta = CuentasModel::findOrFail($id);
         $cuenta->delete();

         return redirect()->route('cuentas.index')->with('success', 'Cuenta eliminada con éxito.');
     }


     public function lista(Request $request)
    {

        $start = $request->input('start', 0);  // Desplazamiento (inicio de los datos)
        $length = $request->input('length', 10);  // Número de registros a mostrar por página
        $searchValue = $request->input('search')['value'];
        // Llamar al método getDataTable del modelo para obtener los datos
        $cuentasM = new CuentasModel();
        $cuentas = $cuentasM->getDataTable($start, $length, $searchValue);
        $totalRecords = $cuentasM->getTotalRecords();
        $totalFilteredRecords = $cuentasM->getFilteredRecords($searchValue, $totalRecords);

        // Devolver la respuesta en formato JSON para DataTables
        return response()->json([
            'draw' => $request->input('draw'),  // Lo envía DataTables para la sincronización
            'recordsTotal' => $totalRecords,    // Total de registros en la tabla
            'recordsFiltered' => $totalFilteredRecords,  // Total de registros después del filtro
            'data' => $cuentas->items() // Los datos paginados
        ]);
    }
}
