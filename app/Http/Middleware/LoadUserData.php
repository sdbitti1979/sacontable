<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Illuminate\Support\Facades\Auth;

class LoadUserData
{
    public function handle($request, Closure $next)
    {
        
        if (Auth::check()) {
            $user = new Usuario();
            //var_dump("entro al handle");
            $roles = $user->roles();
            $permissions = $user->permisos();

            session()->put('user_roles', $roles->get()->toArray());
            session()->put('user_permissions', $permissions->get()->toArray());
        }

        return $next($request);
    }
}

