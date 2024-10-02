<?php

namespace App\Http\Controllers;

use App\Models\CuentasModel;
use Illuminate\Http\Request;

class CuentasController extends Controller
{
    public function cuentas(Request $request)
    {
        $vista = view('cuentas.cuentas');

        return $vista;
    }

     // Mostrar el formulario para crear una nueva cuenta
     public function create()
     {
         return view('cuentas.agregarCuenta');
     }

     // Almacenar una nueva cuenta en la base de datos
     public function store(Request $request)
     {
         $validated = $request->validate([
             'nombre' => 'required|string|max:255',
             'codigo' => 'required|string|max:255|unique:cuentas,codigo',
             'clasificacion_id' => 'nullable|exists:clasificaciones,id',
             'saldo_actual' => 'nullable|numeric',
             'id_padre' => 'nullable|exists:cuentas,idcuenta',
         ]);

         CuentasModel::create($validated);

         return redirect()->route('cuentas.index')->with('success', 'Cuenta creada con éxito.');
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

         return redirect()->route('cuentas.cuentas')->with('success', 'Cuenta actualizada con éxito.');
     }

     // Eliminar una cuenta
     public function destroy($id)
     {
         $cuenta = CuentasModel::findOrFail($id);
         $cuenta->delete();

         return redirect()->route('cuentas.index')->with('success', 'Cuenta eliminada con éxito.');
     }
}
