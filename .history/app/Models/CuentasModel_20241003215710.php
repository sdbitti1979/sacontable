<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PDO;
use Yajra\DataTables\Facades\DataTables;

class CuentasModel extends Model
{
    use HasFactory;

    protected $table = 'cuentas'; // Nombre de la tabla
    protected $primaryKey = 'idcuenta'; // Nombre de la columna primaria
    public $incrementing = true; // Si es autoincremental
    public $timestamps = false; // Si usas timestamps

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'nombre_id',
        'codigo',
        'clasificacion_id',
        'saldo_actual',
        'id_padre',
        'recibe_saldo',
        'catnombre',
        'utilizada',
        'eliminada',
        'modificado',
        'solo_admin',
        'usuario_id'  // Relación con catálogo de nombres
        // Otros campos que necesitas
    ];

    // Relación con otras tablas, por ejemplo, si tiene una relación con clasificación o padre
    public function clasificacion()
    {
        return $this->belongsTo(ClasificacionesModel::class);
    }

    public function padre()
    {
        return $this->belongsTo(CuentasModel::class, 'id_padre');
    }

    public function crearCuenta($validated)
    {
        CuentasModel::create([
            'nombre' => \mb_strtoupper($validated['nombre']),
            'codigo' => $validated['codigo'],
            'clasificacion_id' => $validated['clasificacion'] ?? null,  // Asignar clasificación si existe
            'saldo_actual' => $validated['saldoActual'] ?? 0,  // Si no hay saldoActual, asignar 0
            'id_padre' => $validated['cuentaPadre'] ?? null,   // Asignar cuenta padre si existe
            'recibe_saldo' => $validated['recibeSaldo'],
            'nombre_id' => $validated['catnombre'],
            'utilizada' => 'F',
            'eliminada' => 'F',
            'modificado' => 'F',
            'solo_admin' => 'F',
            'usuario_id' => $validated["usuario_id"]  // Relacionar con el catálogo de nombres
        ]);
    }

    public function getDataTable($start, $length, $searchValue)
    {
        $pdo = DB::connection()->getPdo();

        $query = "SELECT c.idcuenta, c.nombre, c.codigo, c.clasificacion_id,
                            c.saldo_actual, c.id_padre, c.utilizada, c.eliminada,
                            c.modificado, c.solo_admin, c.usuario_id, c.recibe_saldo, c.nombre_id,
                            u.usuario
                    FROM cuentas c
                    left join usuarios u on (c.usuario_id = u.idusuario)";
        if (!empty($searchValue)) {
            $query .= " WHERE c.nombre LIKE :search OR c.codigo LIKE :search";
        }

        // Agregar la paginación a la consulta
        $query .= " LIMIT :length OFFSET :start";


        $stmt = $pdo->prepare($query);

        if (!empty($searchValue)) {
            $stmt->bindValue(':search', '%' . $searchValue . '%', PDO::PARAM_STR);
        }
        $stmt->bindValue(':length', (int) $length, PDO::PARAM_INT);
        $stmt->bindValue(':start', (int) $start, PDO::PARAM_INT);

        $stmt->execute();

        // Obtener los resultados en un array asociativo
        $cuentas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Devolver los datos en formato JSON para DataTables
        return response()->json([
            'data' => $cuentas
        ]);
    }

    public function getFilteredRecords($searchValue, $totalRecords)
    {
        $totalFilteredRecords = $totalRecords;
        if (!empty($searchValue)) {
            $stmtFiltered = $pdo->prepare("SELECT COUNT(*) AS total FROM cuentas c WHERE c.nombre LIKE :search OR c.codigo LIKE :search");
            $stmtFiltered->bindValue(':search', '%' . $searchValue . '%', PDO::PARAM_STR);
            $stmtFiltered->execute();
            $totalFilteredRecords = $stmtFiltered->fetch(PDO::FETCH_ASSOC)['total'];
        }
    }

    public function getTotalRecords()
    {
        $pdo = DB::connection()->getPdo();

        // Consulta para contar el total de registros
        $totalRecordsQuery = "SELECT COUNT(*) AS total FROM cuentas";
        $stmtTotal = $pdo->prepare($totalRecordsQuery);
        $stmtTotal->execute();
        $totalRecords = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

        return $totalRecords;
    }
}
