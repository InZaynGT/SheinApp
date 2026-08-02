<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cuentaBancariaModel;
use App\Models\Movimiento_Bancario;

class MovimientoBancarioController extends Controller
{
    /**
     * Muestra el listado de movimientos bancarios con filtros opcionales.
     */
    public function index(Request $request)
    {
        $cuentaBancaria = $request->input('cuenta_bancaria');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $query = Movimiento_Bancario::orderBy('id', 'desc');

        if ($cuentaBancaria) {
            $query->where('ID_CUENTA_BANCARIA', $cuentaBancaria);
        }

        if ($fechaInicio) {
            $query->whereDate('fecha', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $query->whereDate('fecha', '<=', $fechaFin);
        }

        $mov_banc = $query->get();
        $cuentas_bancarias = cuentaBancariaModel::all();

        return view('movimientos.index', compact('mov_banc', 'cuentas_bancarias', 'cuentaBancaria', 'fechaInicio', 'fechaFin'));
    }


    /**
     * Registra un nuevo movimiento bancario y actualiza el saldo de la cuenta.
     */
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
        if ($tipoMovimiento == 0) {
            $debe = $monto;
            $haber = 0;
            $nuevoSaldo = $saldoActual + $monto;
        } else if ($tipoMovimiento == 1) {
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
