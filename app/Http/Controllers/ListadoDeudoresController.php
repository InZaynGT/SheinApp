<?php

namespace App\Http\Controllers;

use App\Models\CxcDocumentoModel;
use Illuminate\Http\Request;

class ListadoDeudoresController extends Controller
{
    public function index()
    {
        //Mandamos a traer la info de la BDD y la almacenamos en $ordenes
        $deudores = CxcDocumentoModel::selectRaw('cxcdocumento.idCliente, clientes.nombre, COALESCE(SUM(cxcdocumento.saldoDocto), 0) as saldo_total')
            ->join('clientes','cxcdocumento.idCliente', '=', 'clientes.id')
            ->where('cxcdocumento.saldoDocto', '>',0)
            ->orderBy('saldo_total','DESC')
            ->groupBy('cxcdocumento.idCliente','clientes.nombre')
            ->get();
        //Retornamos la vista (pasamos la ruta) y pasamos el parámetro de la variable de ordenes
        return view('deudores.listado_deudores', compact('deudores'));
    }
}
