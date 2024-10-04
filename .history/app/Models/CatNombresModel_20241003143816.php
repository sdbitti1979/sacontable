<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PDO;


class CatNombresModel extends Model
{
    use HasFactory;

    protected $table = 'catalogo_nombres';

    protected $primaryKey = 'id_catnombres'; // Nombre de la columna primaria
    public $incrementing = true; // Si es autoincremental
    public $timestamps = false;

    public function getCatalogoNombres(string $filtro)
    {
        $query = "select cn.id_catnombres, cn.nombre, c.nombre as clasificacion_nombre, cn.codigo_categoria, cn.descripcion
                    from catalogo_nombres cn
                    inner join clasificaciones c on (cn.clasificacion_id = c.idclasificacion
                    and c.tipo_clasificacion = 'nombre' and c.activo = true)";

        $pdo = DB::connection()->getPdo();
        $result = $pdo->prepare($query);

        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}
