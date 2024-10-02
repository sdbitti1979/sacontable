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
        'saldo',
        'id_padre',
        'utilizada',
        'eliminada',
        'modificado',
        'solo_admin',
        'fecha_creacion',
        'fecha_modificacion',
        'usuario_id'
    ];


}
