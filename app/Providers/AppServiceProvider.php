<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                // Obtener los permisos del usuario
                $permisos = DB::table('permisos')
                    ->join('roles_permisos', 'permisos.idpermiso', '=', 'roles_permisos.permiso_id')
                    ->join('roles', 'roles.idrol', '=', 'roles_permisos.rol_id')
                    ->join('usuarios', 'usuarios.idrol', '=', 'roles.idrol')
                    ->where('usuarios.idusuario', $user->idusuario) // Filtra por el ID del usuario
                    ->pluck('permisos.descripcion') // O la columna que contiene la descripción del permiso
                    ->toArray();

                // Compartir los permisos con todas las vistas
                $view->with('permissions', $permisos)
                ->withHeaders([
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0',
                    'Pragma' => 'no-cache',
                    'Expires' => 'Thu, 01 Jan 1970 00:00:00 GMT',
                ]);
            }
        });
    }
}
