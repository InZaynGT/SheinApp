<?php

namespace App\Http\Controllers;

use App\Models\cuentaBancariaModel;
use App\Models\Movimiento_Bancario;

use Illuminate\Http\Request;

class ListadoMovimientosController extends Controller
{
    public function index()
    {
        //Mandamos a traer la info de la BDD y la almacenamos en $ordenes
        $mov_banc = Movimiento_Bancario::orderBy('id','desc')->get();
        $cuentas_bancarias = cuentaBancariaModel::all();
        //Retornamos la vista (pasamos la ruta) y pasamos el parámetro de la variable de ordenes
        return view('movimientos.index', compact('mov_banc', 'cuentas_bancarias'));
    }
    public function store(Request $request)
    {
        $cuentaBancariaId = $request->input('cuenta_bancaria');
        $tipoMovimiento = $request->input('tipo_movimiento');
        $fecha = $request->input('fecha');
        $referencia = $request->input('referencia');
        $monto = $request->input('monto');
        $notas = $request->input('notas');
    
        // Encuentra la cuenta bancaria
        $cuentaBancaria = CuentaBancariaModel::findOrFail($cuentaBancariaId);
        $saldoActual = $cuentaBancaria->saldoActual;
        if($tipoMovimiento == 0){
            $debe = $monto;
            $haber = 0;
            $nuevoSaldo = $saldoActual + $monto;
        }
        else if($tipoMovimiento == 1){
            $debe = 0;
            $haber = $monto;
            $nuevoSaldo = $saldoActual - $monto;
        }
    
        // Inserta el movimiento en la base de datos
        $movimiento = new Movimiento_Bancario();
        $movimiento->ID_CUENTA_BANCARIA = $cuentaBancariaId;
        $movimiento->fecha = $fecha;
        $movimiento->nro_referencia = $referencia;
        $movimiento->debe = $debe;
        $movimiento->haber = $haber;
        $movimiento->saldoActual = $nuevoSaldo; // Guarda el nuevo saldo en el movimiento
        $movimiento->notas = $notas;
        $movimiento->estado = 1;
        $movimiento->save();
    
        // Actualiza el saldo en la cuenta bancaria
        $cuentaBancaria->saldoActual = $nuevoSaldo; // Actualiza el saldo de la cuenta bancaria
        $cuentaBancaria->save();
    
        return redirect()->route('movimientos.index')->with('success', 'Movimiento agregado exitosamente.');
    }
    
}
