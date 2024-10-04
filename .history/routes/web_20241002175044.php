<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsientosController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\CuentasController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\ReportesController;

//Route::view('/', 'welcome');
//Route::view('login', 'login')->name('login')->middleware('guest');
//Route::view('dashboard', 'dashboard')->middleware('auth');

Route::get('/showLoginForm', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/showRegisterForm', [AuthController::class, 'showRegisterForm'])->name('showRegisterForm');

Route::post('/validatelogin', [AuthController::class, 'validateLogin'])->name('validatelogin');
Route::post('/registerUser', [AuthController::class, 'registerUser'])->name('registerUser');
//Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
Route::middleware(['auth', \App\Http\Middleware\CargarRolesYPermisos::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    // Otras rutas protegidas...
    Route::get('/usuarios', [UsuariosController::class, 'usuarios'])->name('usuarios');
    Route::get('/configuracion', [ConfiguracionController::class, 'configuracion'])->name('configuracion');
    Route::get('/cuentas', [CuentasController::class, 'cuentas'])->name('cuentas');
    Route::get('/asientos', [AsientosController::class, 'asientos'])->name('asientos');
    Route::get('/reportes', [ReportesController::class, 'reportes'])->name('reportes');
    Route::post('asientos/lista', [AsientosController::class, 'getAsientos'])->name('asientos.lista');
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


Route::post('/asientos.agregarAsiento', [AsientosController::class, 'agregarAsiento'])->name('asientos.agregarAsiento');
Route::post('/asientos.guardarAsiento', [AsientosController::class, 'guardarAsiento'])->name('asientos.guardarAsiento');

Route::post('/cuentas.agregarCuenta', [CuentasController::class, 'agregarCuenta'])->name('cuentas.agregarCuenta');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
