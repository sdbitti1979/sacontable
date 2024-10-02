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

     // Mostrar el formulario para crear una nueva cuenta
     public function create()
     {
         return view('cuentas.create');
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

         Cuenta::create($validated);

         return redirect()->route('cuentas.index')->with('success', 'Cuenta creada con éxito.');
     }

     // Mostrar el formulario para editar una cuenta existente
     public function edit($id)
     {
         $cuenta = Cuenta::findOrFail($id);
         return view('cuentas.edit', compact('cuenta'));
     }

     // Actualizar una cuenta en la base de datos
     public function update(Request $request, $id)
     {
         $cuenta = Cuenta::findOrFail($id);

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
         $cuenta = Cuenta::findOrFail($id);
         $cuenta->delete();

         return redirect()->route('cuentas.index')->with('success', 'Cuenta eliminada con éxito.');
     }
}
