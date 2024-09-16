<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as FacadesLog;
use Yajra\DataTables\DataTables;

class UsuariosController extends Controller
{
    public function usuarios()
    {
        return view('usuarios');
    }

    public function usuariospaginado(Request $request)
    {
        // Paginación sin Eloquent
        if (request()->ajax()) {
            $usuariosM = new Usuario();

            $data = $usuariosM->listarUsuarios();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', function ($row) {
                    return '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-id="'.$row->idusuario.'">Editar</a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-id="'.$row->idusuario.'">Eliminar</a>';
                })                
                ->orderColumn('idusuario', 'idusuario $1') // Establecer el orden por 'idusuario'
                ->rawColumns(['edit', 'delete'])
                ->make(true);

            //return view('usuariospaginado');
        }
    }

    public function agregarUsuario(Request $request, Response $response)
    {

        return view('usuarios.agregarUsuarios');
    }

    public function editarUsuario(Request $request, Response $response)
    {
        $usuariosM = new Usuario();
        $usuario = $usuariosM->dameUsuarioPorId($request->id);

        return view('usuarios.editarUsuarios', [
            'usuario' => $usuario
        ]);
    }

    public function guardarUsuario(Request $request, Response $response)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'usuario' => 'required|string|max:15',
            'email' => 'required|string|email|max:100',
            'clave' => 'required|string|max:255',
            'apellido' => 'required|string|max:30',
            'nombre' => 'required|string|max:30',
            'cuil' => 'nullable|string|max:11', // Si es opcional
        ]);

       
        $UsuarioM = new Usuario();
        $UsuarioM->insertarUsuario($validated);
       
        return response()->json(['message' => 'Datos guardados correctamente']);
      
    }

    public function actualizarUsuario(Request $request, Response $response)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'usuario' => 'required|string|max:15',
            'email' => 'required|string|email|max:100',          
            'apellido' => 'required|string|max:30',
            'nombre' => 'required|string|max:30',
            'cuil' => 'nullable|string|max:11', // Si es opcional
        ]);

      
        $UsuarioM = new Usuario();
        $usuario = $UsuarioM->dameUsuarioPorId($request->id);      
        $UsuarioM->actualizarUsuario($validated, $usuario);
       
        return response()->json(['message' => 'Datos guardados correctamente']);
      
    }

    public function destroy(Request $request)
    {
        try {

            
            $UsuarioM = new Usuario();
            // Encuentra el usuario por su ID
            $usuario = $UsuarioM->dameUsuarioPorId($request->id);
         
            // Verifica si el usuario existe
            if ($usuario) {
                // Elimina el usuario
                $usuario->delete();

                // Devuelve una respuesta de éxito
                return response()->json(['success' => true]);
            } else {
                // Devuelve una respuesta de error si el usuario no se encuentra
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado.']);
            }
        } catch (\Exception $e) {
            // Registra el error y devuelve una respuesta de error
            FacadesLog::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al eliminar el usuario.']);
        }
    }
}
