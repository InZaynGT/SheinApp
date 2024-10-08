<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\crearOrdenController;
use App\Http\Controllers\listadoOrdenController;
use App\Http\Controllers\listadoClienteController;
use App\Http\Controllers\DeudoresController;
use App\Http\Controllers\Consulta_PagosController;
use App\Http\Controllers\ListadoDeudoresController;
use App\Http\Controllers\ListadoMovimientosController;
use App\Http\Controllers\ListadoBancosController;
use App\Http\Controllers\ListadoCuentasController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/crearorden', [crearOrdenController::class, 'index'])->name('ordenes.crearorden');
    Route::get('/listadoOrden', [listadoOrdenController::class, 'index'])->name('ordenes.index');
    Route::get('/clientes', [listadoClienteController::class, 'index'])->name('clientes.index');
    Route::put('/cliente/update', [listadoClienteController::class, 'update'])->name('cliente.update');
    Route::post('/clientes/store', [listadoClienteController::class, 'store'])->name('cliente.store');
    Route::post('/ordenes/store', [crearOrdenController::class, 'store'])->name('ordenes.store');
    Route::get('/ordenes/{id}', [listadoOrdenController::class, 'show'])->name('ordenes.show');
    Route::get('/deudores', [DeudoresController::class, 'index'])->name('deudores.index');
    Route::get('/deudores/documentos/{idCliente}', [DeudoresController::class, 'getDocumentosDeudores'])->name('deudores.documentos');
    Route::get('/deudores/anticipos/{idCliente}', [DeudoresController::class,'getAnticipos'])->name('deudores.anticipos');
    Route::get('/deudores/pagos-aplicados/{idDocumento}', [DeudoresController::class, 'getPagosAplicados'])->name('deudores.pagos_aplicados');
    Route::post('/pagos/store', [DeudoresController::class, 'store'])->name('pagos.store');
    Route::get('/consulta_pagos', [Consulta_PagosController::class, 'index'])->name('deudores.consulta_pagos');
    Route::get('/listado_deudores', [ListadoDeudoresController::class, 'index'])->name('deudores.listado_deudores');
    Route::get('/impresion_orden/{id}', [ListadoOrdenController::class, 'generateReport'])->name('voting-result');
    Route::get('/impresion_pago/{id}', [Consulta_PagosController::class, 'generateReport'])->name('pago_impresion');
    Route::get('/listado_movimientos', [ListadoMovimientosController::class, 'index'])->name('movimientos.index');
    Route::get('/bancos', [ListadoBancosController::class, 'index'])->name('movimientos.bancos');
    Route::get('/cuentas_bancarias', [ListadoCuentasController::class, 'index'])->name('movimientos.cuentas');
    Route::post('/movimiento/store', [ListadoMovimientosController::class, 'store'])->name('movimiento.store');
    Route::post('/anticipos', [DeudoresController::class, 'storeAnticipo'])->name('anticipos.store');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
