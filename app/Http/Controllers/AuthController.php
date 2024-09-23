<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('login');
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function validateLogin(Request $request)
    {
        $credentials = request()->only('email', 'password');

        $user = DB::table('usuarios')->where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->clave)) {
            Auth::loginUsingId($user->idusuario);

            $currentTime = Carbon::now('America/Argentina/Buenos_Aires');
            DB::table('usuarios')->where('idusuario', $user->idusuario)->update(['ultimo_inicio_sesion' => $currentTime]);

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    }

    public function logout()
    {
        Auth::logout();

        // Invalidar la sesión
        request()->session()->invalidate();

        // Regenerar el token CSRF para mayor seguridad
        request()->session()->regenerateToken();

        // Redirigir al login
        return redirect()->route('login');
    }
}
