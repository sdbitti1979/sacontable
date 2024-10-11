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

    public function getClasificaciones()
    {

        $query = "select c.idclasificacion, c.nro_cuenta, c.nombre, c.recibe_saldo, c.tipo
                        from clasificaciones c
                        order by c.nombre asc";
        $pdo = DB::connection()->getPdo();
        $result = $pdo->prepare($query);

        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClasificacionPorCodigo($codigo){

        $query = "select c.idclasificacion, c.nro_cuenta, c.nombre, c.recibe_saldo, c.tipo
                        from clasificaciones c
                        where c.nro_cuenta = :pcodigo
                        order by c.nombre asc";

        $pdo = DB::connection()->getPdo();
        $result = $pdo->prepare($query);
        $result->bindValue(":pcodigo", $codigo);
        $result->execute();

        return $result->fetch(PDO::FETCH_ASSOC);
    }
}
