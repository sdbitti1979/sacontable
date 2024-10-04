<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClasificacionesModel extends Model
{
    use HasFactory;

    protected $table = 'clasificaciones';

    protected $primaryKey = 'idcuenta'; // Nombre de la columna primaria
    public $incrementing = true; // Si es autoincremental
    public $timestamps = false;

}
