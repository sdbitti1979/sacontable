<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CargarRolesYPermisos
{
    public function handle($request, Closure $next)
    {
        
        if (Auth::check()) {
           
            $userId = Auth::id();

            // Consulta roles
            $roles = DB::table('roles')          
                    ->join('usuarios', 'roles.idrol', '=', 'usuarios.idrol')
                    ->where('usuarios.idrol', $userId)
                    ->pluck('roles.descripcion') // Cambia 'nombre_rol' por el nombre real
                    ->toArray();

            // Consulta permisos
            $permisos = DB::table('permisos')
                 ->join('roles_permisos', 'permisos.idpermiso', '=', 'roles_permisos.permiso_id')
                ->join('roles', 'roles.idrol', '=', 'roles_permisos.rol_id')
                ->join('usuarios', 'usuarios.idrol', '=', 'roles.idrol')
                ->where('usuarios.idusuario', $userId) // Filtra por el ID del usuario
                ->pluck('permisos.descripcion') // O la columna que contiene la descripción del permiso
                ->toArray();

            // Almacena en la sesión
            session()->put('user_roles', $roles);
            session()->put('user_permissions', $permisos);
            
        }
        
        return $next($request);
    }
}

