<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermisosController;


//Route::view('/', 'welcome');
Route::view('login', 'login')->name('login')->middleware('guest');
//Route::view('dashboard', 'dashboard')->middleware('auth');

Route::get('/showLoginForm', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/validatelogin', [AuthController::class, 'validateLogin'])->name('validatelogin');
//Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
Route::middleware(['auth', \App\Http\Middleware\CargarRolesYPermisos::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    // Otras rutas protegidas...
    Route::get('/usuarios', [UsuariosController::class, 'usuarios'])->name('usuarios');
    Route::get('/configuracion', [ConfiguracionController::class, 'configuracion'])->name('configuracion');
});


Route::post('/usuarios.data', [UsuariosController::class, 'usuariospaginado'])->name('usuarios.data');
Route::post('/usuarios.agregarUsuario', [UsuariosController::class, 'agregarUsuario'])->name('usuarios.agregarUsuario');
Route::post('/usuarios.editarUsuario', [UsuariosController::class, 'editarUsuario'])->name('usuarios.editarUsuario');
Route::post('/usuarios.guardarUsuario', [UsuariosController::class, 'guardarUsuario'])->name('usuarios.guardarUsuario');
Route::post('/usuarios.actualizarUsuario', [UsuariosController::class, 'actualizarUsuario'])->name('usuarios.actualizarUsuario');
Route::post('/usuarios/eliminar', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');



Route::get('/roles', [RolesController::class, 'roles'])->name('roles');
Route::post('/roles.data', [RolesController::class, 'rolespaginado'])->name('roles.data');
Route::post('/cantidadRoles', [RolesController::class, 'cantidadRoles'])->name('cantidadRoles');
Route::post('/roles.editarRol', [RolesController::class, 'editarRol'])->name('roles.editarRol');

Route::get('/permisos', [PermisosController::class, 'permisos'])->name('permisos');
Route::post('/permisos.data', [PermisosController::class, 'permisospaginado'])->name('permisos.data');
Route::post('/cantidadPermisos', [PermisosController::class, 'cantidadPermisos'])->name('cantidadPermisos');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');