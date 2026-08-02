<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenModel;
use App\Models\clienteModel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Livewire\Component;


class listadoOrdenController extends Controller
{
    public function index(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin    = $request->input('fecha_fin');
        $idCliente   = $request->input('id_cliente');
        $nombreCliente = '';

        $query = OrdenModel::with('cliente', 'CXC')->orderBy('id', 'desc');
        if ($fechaInicio) {
            $query->whereDate('fechaPromesa', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $query->whereDate('fechaPromesa', '<=', $fechaFin);
        }

        if ($idCliente) {
            $query->where('idCliente', $idCliente);
            $cliente = clienteModel::find($idCliente);
            $nombreCliente = $cliente ? $cliente->nombre : '';
        }

        $ordenes = $query->paginate(50)->withQueryString();

        return view('ordenes.index', compact('ordenes', 'fechaInicio', 'fechaFin', 'idCliente', 'nombreCliente'));


    }

    public function show($id)
    {
        // Obtén la orden y sus detalles
        $orden = OrdenModel::with('cliente', 'detalleOrden')->findOrFail($id);
    
        // Retorna la vista con los datos de la orden
        return view('ordenes.detalleOrden', compact('orden'));
    }

    public function generateReport($id){
        // Obtén la orden y sus detalles
        $orden = OrdenModel::with('cliente', 'detalleOrden')->findOrFail($id);

        $pdf = FacadePdf::loadView('reports.voting-result',compact('orden'));

        return $pdf->stream();
    }

    
}
