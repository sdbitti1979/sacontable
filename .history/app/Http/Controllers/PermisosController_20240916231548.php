<?php

namespace App\Http\Controllers;

use App\Models\PermisosModel;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PermisosController extends Controller
{
    public function permisos(Request $request)
    {
        $vista = view('permisos.permisos');
        
        return $vista;
    }

    public function permisospaginado(Request $request){
        
        if (request()->ajax()) {
            $permisosM = new PermisosModel();

            $data = $permisosM->listarPermisos();

            return DataTables::of($data)
                ->addIndexColumn()                              
                ->orderColumn('idpermiso', 'idpermiso $1') // Establecer el orden por 'idusuario'               
                ->make(true);           
        }
    }

    public function cantidadPermisos(Request $request, Response $response){
        $permisosM = new PermisosModel();
        $cantidad = $permisosM->cantidadPermisos();
        $data = array("cantidad" => $cantidad);
        return response()->json($data);
    }

    public function permisosAsignadosAlRol(Request $request, Response $response){
        $rolid = $request->getParam("rolid", null);
        $permisosM = new PermisosModel();
        $permisos = $permisosM->permisosAsignadosAlRol($rolid);

        $data = array("permisos", $permisos);
        return response()->json($data);
    }
}
