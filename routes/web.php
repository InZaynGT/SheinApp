<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\CxcDocumentoController;
use App\Http\Controllers\MovimientoBancarioController;
use App\Http\Controllers\BancoController;
use App\Http\Controllers\CuentaBancariaController;
use App\Http\Controllers\AnticipoController;

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
    // Órdenes
    Route::get('/crearorden', [OrdenController::class, 'create'])->name('ordenes.crearorden');
    Route::get('/listadoOrden', [OrdenController::class, 'index'])->name('ordenes.index');
    Route::post('/ordenes/store', [OrdenController::class, 'store'])->name('ordenes.store');
    Route::get('/ordenes/{id}', [OrdenController::class, 'show'])->name('ordenes.show');

    // Clientes
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/crearcliente', [ClienteController::class, 'create'])->name('clientes.create');
    Route::put('/cliente/update', [ClienteController::class, 'update'])->name('cliente.update');
    Route::post('/clientes/store', [ClienteController::class, 'store'])->name('cliente.store');
    Route::get('/buscar-cliente', [ClienteController::class, 'buscarCliente'])->name('buscar.cliente');

    // Pagos / Deudores
    Route::get('/deudores', [PagoController::class, 'index'])->name('deudores.index');
    Route::post('/pagos/store', [PagoController::class, 'store'])->name('pagos.store');
    Route::post('/anticipos', [PagoController::class, 'storeAnticipo'])->name('anticipos.store');
    Route::get('/consulta_pagos', [PagoController::class, 'consultaPagos'])->name('deudores.consulta_pagos');

    // Documentos CXC / Deudores
    Route::get('/listado_deudores', [CxcDocumentoController::class, 'index'])->name('deudores.listado_deudores');
    Route::get('/deudores/documentos/{idCliente}', [CxcDocumentoController::class, 'getDocumentosDeudores'])->name('deudores.documentos');
    Route::get('/deudores/pagos-aplicados/{idDocumento}', [CxcDocumentoController::class, 'getPagosAplicados'])->name('deudores.pagos_aplicados');

    // Anticipos
    Route::get('/deudores/anticipos/{idCliente}', [AnticipoController::class, 'getAnticipos'])->name('deudores.anticipos');

    // Movimientos bancarios
    Route::get('/listado_movimientos', [MovimientoBancarioController::class, 'index'])->name('movimientos.index');
    Route::post('/movimiento/store', [MovimientoBancarioController::class, 'store'])->name('movimiento.store');
    Route::get('/bancos', [BancoController::class, 'index'])->name('movimientos.bancos');
    Route::get('/cuentas_bancarias', [CuentaBancariaController::class, 'index'])->name('movimientos.cuentas');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::get('/impresion_orden/{id}', [OrdenController::class, 'generateReport'])->name('voting-result');
Route::get('/impresion_pago/{id}', [PagoController::class, 'generateReport'])->name('pago_impresion');
