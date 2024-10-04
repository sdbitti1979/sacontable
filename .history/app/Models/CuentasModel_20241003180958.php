<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'codigo',
        'clasificacion_id',
        'saldo_actual',
        'id_padre',
        'recibe_saldo',
        'catnombre',  // Relación con catálogo de nombres
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

    public function crearCuenta($validated){
        CuentasModel::create([
            'nombre' => $validated['nombre'],
            'codigo' => $validated['codigo'],
            'clasificacion_id' => $validated['clasificacion'] ?? null,  // Asignar clasificación si existe
            'saldo_actual' => $validated['saldoActual'] ?? 0,  // Si no hay saldoActual, asignar 0
            'id_padre' => $validated['cuentaPadre'] ?? null,   // Asignar cuenta padre si existe
            'recibe_saldo' => $validated['recibeSaldo'],
            'catnombre' => $validated['catnombre'],  // Relacionar con el catálogo de nombres
        ]);
    }
}
