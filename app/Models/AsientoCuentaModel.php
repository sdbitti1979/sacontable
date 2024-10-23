<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsientoCuentaModel extends Model
{
    use HasFactory;

    protected $table = 'asiento_cuenta'; // Nombre de la tabla
    protected $primaryKey = 'idasientocuenta'; // Nombre de la columna primaria
    public $incrementing = true; // Si es autoincremental
    public $timestamps = false; // Si usas timestamps

    protected $fillable = [
        'asiento_id',   // Este es el campo que necesitas agregar
        'cuenta_id',
        'debe',
        'haber',
        'saldo',
    ];

}
