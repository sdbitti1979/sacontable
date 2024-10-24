<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PDO;

class RolesModel extends Model
{
    use HasFactory;

    protected $table = 'roles'; // Nombre de la tabla
    protected $primaryKey = 'idrol'; // Nombre de la columna primaria
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

    public function listarRoles()
    {
        return DB::table('roles')             
                ->select('roles.*', 'roles.descripcion as rol')
                ->orderBy('idrol');
        
    }

    public function cantidadRoles(){
        return DB::table('roles')->count();
                
    }

    public function getRoles(){

        $query = "select idrol, descripcion as rol from roles order by descripcion asc";
        $pdo = DB::connection()->getPdo();
        $result = $pdo->prepare($query);

        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

}
