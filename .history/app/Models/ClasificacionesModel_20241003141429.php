<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PDO;

class ClasificacionesModel extends Model
{
    use HasFactory;

    protected $table = 'clasificaciones';

    protected $primaryKey = 'idclasificacion'; // Nombre de la columna primaria
    public $incrementing = true; // Si es autoincremental
    public $timestamps = false;

    public function getClasificaciones(){

        $query = "select idrol, descripcion as rol from roles order by descripcion asc";
        $pdo = DB::connection()->getPdo();
        $result = $pdo->prepare($query);

        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

}
