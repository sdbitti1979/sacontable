<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PDO;
use Yajra\DataTables\Facades\DataTables;

class AsientoContableModel extends Model
{
    use HasFactory;

    protected $table = 'asientos_contables'; // Nombre de la tabla
    protected $primaryKey = 'idasiento'; // Nombre de la columna primaria
    public $incrementing = true; // Si es autoincremental
    public $timestamps = false; // Si usas timestamps

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fecha',
        'descripcion',
        'usuario_id'
    ];


    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function listarAsientosContables()
    {
        return DB::table('asientos_contables')
            ->select('asientos_contables.*', 'usuarios.usuario')
            ->leftJoin('usuarios', 'asientos_contables.usuario_id', '=', 'usuarios.idusuario');
    }

    // Scope para DataTable
    public function scopeDataTable($query)
    {
        return $query->with('usuario')->select('asientos_contables.*');
    }

    // Método para manejar la respuesta de DataTable
    public function getDataTable($start, $length, $searchValue)
    {

        $pdo = DB::connection()->getPdo();
        $query = "SELECT ac.idasiento, ac.fecha, ac.descripcion, ac.usuario_id, ac.nro_asiento, u.usuario
                    FROM asientos_contables ac
                    left join usuarios u on (u.idusuario = ac.usuario_id)";

         // Agregar la paginación a la consulta
         $query .= " order by ac.idasiento desc ";
         $query .= " LIMIT :length OFFSET :start";

        $result = $pdo->prepare($query);

        $result->bindValue(':length', (int) $length, PDO::PARAM_INT);
        $result->bindValue(':start', (int) $start, PDO::PARAM_INT);

        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertarAsiento($validated)
    {

        // Asegúrate de que $validated['usuario_id'] esté presente y no sea nulo
        if (!isset($validated['usuario_id']) || is_null($validated['usuario_id'])) {
            throw new \Exception("El campo usuario_id no puede ser nulo.");
        }
        try {
            // Asegúrate de que el formato de entrada coincida con el formato esperado
            $fecha_formateada = Carbon::createFromFormat('d/m/Y', $validated['fecha'])->format('Y-m-d');
        } catch (\Exception $e) {
            throw new \Exception("La fecha proporcionada no es válida.");
        }

        // Crear el registro en la base de datos
        $id_asiento = AsientoContableModel::create([
            'fecha' => $fecha_formateada, // Convertir a formato de fecha si es necesario
            'descripcion' => ($validated['descripcion'] ? mb_strtoupper($validated['descripcion']) : null),
            'usuario_id' => $validated["usuario_id"]
        ]);

        return $id_asiento;
    }
}
