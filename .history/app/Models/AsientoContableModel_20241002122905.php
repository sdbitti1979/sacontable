<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
    public function getDataTable()
    {
        $query = $this->scopeDataTable($this->newQuery())
            ->select([
                'asientos_contables.*',
                DB::raw("TO_CHAR(fecha, 'DD/MM/YYYY') as fecha_formateada") // Formatear fecha en PostgreSQL
            ])
            ->orderBy('fecha', 'desc'); // Ordenar por fecha ascendente, puedes cambiar a 'desc' si es necesario

        // Ahora usamos DataTables para manejar las columnas adicionales y la transformación
        return DataTables::of($query)
            ->addColumn('usuario_nombre', function ($row) {
                return $row->usuario ? $row->usuario->usuario : 'Sin usuario';
            })
            ->editColumn('fecha', function ($row) {
                // Usar la fecha formateada
                return $row->fecha_formateada ?? 'Fecha no disponible';
            })
            ->make(true);
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
