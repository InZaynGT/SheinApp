<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cuentaBancariaModel;

class CuentaBancariaController extends Controller
{
    /**
     * Muestra el listado de cuentas bancarias.
     */
    public function index()
    {
        $cuentas = cuentaBancariaModel::orderBy('id', 'desc')->get();

        return view('movimientos.cuentas', compact('cuentas'));
    }
}
