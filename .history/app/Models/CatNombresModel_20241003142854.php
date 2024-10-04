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


}
