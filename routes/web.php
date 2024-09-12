<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuariosController;

//Route::view('/', 'welcome');
Route::view('login', 'login')->name('login')->middleware('guest');
Route::view('dashboard', 'dashboard')->middleware('auth');

Route::get('/showLoginForm', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/validatelogin', [AuthController::class, 'validateLogin'])->name('validatelogin');
Route::post('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

Route::get('/usuarios', [UsuariosController::class, 'usuarios'])->name('usuarios');
Route::post('/usuarios.data', [UsuariosController::class, 'usuariospaginado'])->name('usuarios.data');
Route::post('/usuarios.agregarUsuario', [UsuariosController::class, 'agregarUsuario'])->name('usuarios.agregarUsuario');
Route::post('/usuarios.editarUsuario', [UsuariosController::class, 'editarUsuario'])->name('usuarios.editarUsuario');
Route::post('/usuarios.guardarUsuario', [UsuariosController::class, 'guardarUsuario'])->name('usuarios.guardarUsuario');
Route::post('/usuarios.actualizarUsuario', [UsuariosController::class, 'actualizarUsuario'])->name('usuarios.actualizarUsuario');
Route::post('/usuarios/eliminar', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

