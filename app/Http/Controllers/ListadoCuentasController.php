<?php

namespace App\Http\Controllers;

use App\Models\cuentaBancariaModel;
use Illuminate\Http\Request;

class ListadoCuentasController extends Controller
{
    public function index()
    {
        //Mandamos a traer la info de la BDD y la almacenamos en $ordenes
        $cuentas = cuentaBancariaModel::orderBy('id','desc')->get();
        //Retornamos la vista (pasamos la ruta) y pasamos el parámetro de la variable de ordenes
        return view('movimientos.cuentas', compact('cuentas'));
    }
}
