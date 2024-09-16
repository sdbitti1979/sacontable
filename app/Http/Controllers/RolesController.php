<?php

namespace App\Http\Controllers;

use App\Models\RolesModel;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RolesController extends Controller
{
    public function roles(Request $request)
    {
        $vista = view('roles.roles');
        
        return $vista;
    }

    public function rolespaginado(Request $request){
        
        if (request()->ajax()) {
            $rolesM = new RolesModel();

            $data = $rolesM->listarRoles();

            return DataTables::of($data)
                ->addIndexColumn()                              
                ->orderColumn('idrol', 'idrol $1') // Establecer el orden por 'idusuario'               
                ->make(true);           
        }
    }

    public function cantidadRoles(Request $request, Response $response){
        $rolesM = new RolesModel();
        $cantidad = $rolesM->cantidadRoles();
        $data = array("cantidad" => $cantidad);
        return response()->json($data);
    }
}
