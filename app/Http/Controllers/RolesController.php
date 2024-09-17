<?php

namespace App\Http\Controllers;

use App\Models\PermisosModel;
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

    public function rolespaginado(Request $request)
    {

        if (request()->ajax()) {
            $rolesM = new RolesModel();

            $data = $rolesM->listarRoles();

            return DataTables::of($data)->addIndexColumn()
                ->addColumn('edit', function ($row) {
                    return '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-id="' . $row->idrol . '">Editar</a>';
                })                
                ->orderColumn('idrol', 'idrol $1') // Establecer el orden por 'idusuario'     
                ->rawColumns(['edit'])          
                ->make(true);
        }
    }

    public function cantidadRoles(Request $request, Response $response)
    {
        $rolesM = new RolesModel();
        $cantidad = $rolesM->cantidadRoles();
        $data = array("cantidad" => $cantidad);
        return response()->json($data);
    }

    public function editarRol(Request $request, Response $response){
        $rolid = $request->get('rolid');
        
        $permisosM = new PermisosModel();
        $permisos = $permisosM->permisosAsignadosAlRol($rolid);

        $data = array("permisos", $permisos);

        $vista = view('roles.editarRol', $data);

        return $vista;
    }
}
