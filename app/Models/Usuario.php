<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    public function listarUsuarios()
    {
        return DB::table('usuarios')->orderBy('idusuario');
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
}
