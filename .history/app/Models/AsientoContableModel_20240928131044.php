<?php

namespace App\Models;

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
        return DataTables::of($this->scopeDataTable($this->newQuery()))
            ->addColumn('usuario_nombre', function ($row) {
                return $row->usuario ? $row->usuario->nombre : 'Sin usuario';
            })
            ->make(true);
    }

    public function insertarAsiento($validated)
    {

        // Asegúrate de que $validated['usuario_id'] esté presente y no sea nulo
        if (!isset($validated['usuario_id']) || is_null($validated['usuario_id'])) {
            throw new \Exception("El campo usuario_id no puede ser nulo.");
        }

        // Crear el registro en la base de datos
        $id_asiento = AsientoContableModel::create([
            'fecha' => date('Y-m-d', strtotime($validated["fecha"])), // Convertir a formato de fecha si es necesario
            'descripcion' => $validated['descripcion'],
            'usuario_id' => $validated["usuario_id"]
        ]);

        var_dump($id_asiento);
        die();
    }
}
