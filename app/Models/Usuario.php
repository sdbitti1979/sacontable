<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $table = 'usuarios'; // Nombre de la tabla
    protected $primaryKey = 'idusuario'; // Nombre de la columna primaria
    public $incrementing = true; // Si es autoincremental
    public $timestamps = false; // Si usas timestamps
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'usuario',
        'email',
        'clave',
        'apellido',
        'nombre',
        'fecha_creacion',
        'ultimo_inicio_sesion',
        'cuil',
    ];

    /*public function listarUsuarios()
    {
        return DB::table('usuarios')
                ->leftJoin('roles', 'usuarios.idrol', '=', 'roles.idrol') // LEFT JOIN con la tabla 'roles'
                ->select('usuarios.*', 'roles.descripcion as rol_nombre')
                ->orderBy('idusuario');
        
    }*/

    /**
     * Scope para listar usuarios con filtros y ordenamientos.
     *
     * @param Builder $query
     * @param array $filtros
     * @param array $ordenes
     * @return Builder
     */
    public function scopeListarUsuarios(Builder $query, $filtros = [], $ordenes = [])
    {
        $query->leftJoin('roles', 'usuarios.idrol', '=', 'roles.idrol') // LEFT JOIN con la tabla 'roles'
            ->select('usuarios.*', 'roles.descripcion as rol_nombre') // Selecciona columnas y alias
            ->where('activo', 'T'); // Asegúrate de que la columna 'estado' existe

        // Aplica filtros
        foreach ($filtros as $key => $value) {
            if ($key === 'usuario' && !empty($value)) {
                $query->where('usuarios.usuario', 'LIKE', "%{$value}%");
            } elseif ($key === 'email' && !empty($value)) {
                $query->where('usuarios.email', 'LIKE', "%{$value}%");
            } elseif ($key === 'apellido' && !empty($value)) {
                $query->where('usuarios.apellido', 'LIKE', "%{$value}%");
            } elseif ($key === 'nombre' && !empty($value)) {
                $query->where('usuarios.nombre', 'LIKE', "%{$value}%");
            } elseif ($key === 'cuil' && !empty($value)) {
                $query->where('usuarios.cuil', 'LIKE', "%{$value}%");
            }
        }

        // Aplica ordenamientos
        foreach ($ordenes as $columna => $orden) {
            $query->orderBy($columna, $orden);
        }

        return $query;
    }

    public function insertarUsuario($validated)
    {
        Usuario::create([
            'usuario' => $validated['usuario'],
            'email' => $validated['email'],
            'clave' => Hash::make($validated['clave']),
            'apellido' => mb_strtoupper($validated['apellido']),
            'nombre' => mb_strtoupper($validated['nombre']),
            'fecha_creacion' => date('Y-m-d H:i:s'), //$validated['fecha_creacion'],
            'ultimo_inicio_sesion' => date('Y-m-d H:i:s') ?? null,
            'cuil' => $validated['cuil'] ?? null,
        ]);
    }

    public function actualizarUsuario($validated,  $usuario)
    {

        $usuario = Usuario::findOrFail($usuario->idusuario);

        // Actualizar los campos del usuario
        $usuario->update([
            'usuario' => $validated['usuario'],
            'email' => $validated['email'],
            'clave' => $usuario->clave, // Solo actualizar si se proporciona una nueva clave
            'apellido' => mb_strtoupper($validated['apellido']),
            'nombre' => mb_strtoupper($validated['nombre']),
            'cuil' => $validated['cuil'] ?? null,
        ]);
    }

    public function dameUsuarioPorId($idusuario)
    {
        return  Usuario::find($idusuario);
    }

    public function roles($userId)
    {
        return DB::table('roles')
        ->join('usuarios', 'roles.idrol', '=', 'usuarios.idrol')
        ->where('usuarios.idrol', $userId)
        ->pluck('roles.descripcion') // Cambia 'nombre_rol' por el nombre real
        ->toArray();
    }

    // Relación para permisos
    public function permisos($userId)
    {
        return DB::table('permisos')
        ->join('roles_permisos', 'permisos.idpermiso', '=', 'roles_permisos.permiso_id')
        ->join('roles', 'roles.idrol', '=', 'roles_permisos.rol_id')
        ->join('usuarios', 'usuarios.idrol', '=', 'roles.idrol')
        ->where('usuarios.idusuario', $userId) // Filtra por el ID del usuario
        ->pluck('permisos.descripcion') // O la columna que contiene la descripción del permiso
        ->toArray();
    }

}
