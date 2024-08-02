<?php

namespace App\Http\Controllers;

use App\Models\bancosModel;
use Illuminate\Http\Request;

class ListadoBancosController extends Controller
{
    public function index()
    {
        //Mandamos a traer la info de la BDD y la almacenamos en $ordenes
        $bancos = bancosModel::orderBy('id','desc')->get();
        //Retornamos la vista (pasamos la ruta) y pasamos el parámetro de la variable de ordenes
        return view('movimientos.bancos', compact('bancos'));
    }
}
