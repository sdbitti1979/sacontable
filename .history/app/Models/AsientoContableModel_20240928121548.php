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
        'cuenta_id',
        'descripcion',
        'usuario'
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
        AsientoContableModel::create([
            'usuario' => $validated['usuario'],
            'descripcion' => $validated['descripcionAsiento'],
            'fecha' => $validated["fechaAsiento"]
        ]);
    }
}
