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
Route::post('/asientos.detalleAsiento', [AsientosController::class, 'detalleAsiento'])->name('asientos.detalleAsiento');
Route::post('/asientos.guardarAsiento', [AsientosController::class, 'guardarAsiento'])->name('asientos.guardarAsiento');
Route::post('/asientos.registrarAsiento', [AsientosController::class, 'registrarAsiento'])->name('asientos.registrarAsiento');

Route::post('/cuentas.agregarCuenta', [CuentasController::class, 'agregarCuenta'])->name('cuentas.agregarCuenta');
Route::post('/cuentas.getCatNombres', [CuentasController::class, 'getCatNombres'])->name('cuentas.getCatNombres');
Route::post('/cuentas.guardarCuenta', [CuentasController::class, 'guardarCuenta'])->name('cuentas.guardarCuenta');
Route::post('/cuentas.actualizarCuenta', [CuentasController::class, 'actualizarCuenta'])->name('cuentas.actualizarCuenta');
Route::post('/cuentas.lista', [CuentasController::class, 'lista'])->name('cuentas.lista');
Route::get('/cuentas.getCuentas', [CuentasController::class, 'getCuentas'])->name('cuentas.getCuentas');
Route::post('/cuentas.verificarNombre', [CuentasController::class, 'verificarNombre'])->name('cuentas.verificarNombre');
Route::post('/cuentas.verificarCodigo', [CuentasController::class, 'verificarCodigo'])->name('cuentas.verificarCodigo');
Route::post('/cuentas.eliminarCuenta', [CuentasController::class, 'destroy'])->name('cuentas.eliminarCuenta');
Route::post('/cuentas.editarCuenta', [CuentasController::class, 'editarCuenta'])->name('cuentas.editarCuenta');
Route::post('/cuentas.cuentaUtilizada', [CuentasController::class, 'cuentaUtilizada'])->name('cuentas.cuentaUtilizada');
Route::post('/cuentas.getClasificaciones', [CuentasController::class, 'getClasificaciones'])->name('cuentas.getClasificaciones');



Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
