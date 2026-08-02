<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\bancosModel;

class BancoController extends Controller
{
    /**
     * Muestra el listado de bancos.
     */
    public function index()
    {
        $bancos = bancosModel::orderBy('id', 'desc')->get();

        return view('movimientos.bancos', compact('bancos'));
    }
}
