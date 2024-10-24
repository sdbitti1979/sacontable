<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PermisosModel extends Model
{
    use HasFactory;

    protected $table = 'permisos'; // Nombre de la tabla
    protected $primaryKey = 'idpermiso'; // Nombre de la columna primaria
    public $incrementing = true; // Si es autoincremental
    public $timestamps = false; // Si usas timestamps

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'descripcion'
    ];

    public function listarPermisos()
    {
        return DB::table('permisos')             
                ->select('permisos.*', 'permisos.descripcion as permiso')
                ->orderBy('idpermiso');
        
    }

    public function cantidadPermisos(){
        return DB::table('permisos')->count();
                
    }

    public function permisosAsignadosAlRol($roleId){
        return DB::table('roles')
        ->join('roles_permisos', 'roles.idrol', '=', 'roles_permisos.rol_id')
        ->join('permisos', 'roles_permisos.permiso_id', '=', 'permisos.idpermiso')
        ->where('roles.idrol', $roleId)
        ->select('permisos.*')
        ->get();
    }
}
