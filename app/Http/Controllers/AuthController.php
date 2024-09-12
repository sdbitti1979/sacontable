<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showLoginForm(){
        return view('login');
    }

    public function validateLogin(Request $request)
    {
        $credentials = request()->only('email', 'password');
        
        $user = DB::table('usuarios')->where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->clave)) {
            Auth::loginUsingId($user->idusuario);
    
            return redirect()->route('dashboard');
        }
       
        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}

