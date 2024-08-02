<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PagoEnc;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;


class Consulta_PagosController extends Controller
{
    public function index()
    {
        //Mandamos a traer la info de la BDD y la almacenamos en $ordenes
        $listado_pagos = PagoEnc::with(['Clientes', 'formaPago', 'CuentasBancarias.Bancos', 'PagosDet.orden'])->get();
        //Retornamos la vista (pasamos la ruta) y pasamos el parámetro de la variable de ordenes
        return view('deudores.consulta_pagos', compact('listado_pagos'));
    }

    public function generateReport($id){
        // Obtén la orden y sus detalles
        $listado_pagos = PagoEnc::with(['Clientes', 'formaPago', 'CuentasBancarias.Bancos', 'PagosDet.orden'])
        ->where('id', '=', $id)->get();

        $pdf = FacadePdf::loadView('reports.listado_pagos',compact('listado_pagos'));

        return $pdf->stream();
    }
}
