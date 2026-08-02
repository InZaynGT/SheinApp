<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenModel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Livewire\Component;

class listadoOrdenController extends Controller
{
    public function index(Request $request)
    {
        // Obtenemos los parámetros de filtro enviados por el formulario (GET)
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin    = $request->input('fecha_fin');

        // Construimos la consulta base con las relaciones y el orden
        $query = OrdenModel::with('cliente', 'CXC')->orderBy('id', 'desc');

        // Aplicamos el filtro de fecha inicial (>=) si viene definido
        if ($fechaInicio) {
            $query->whereDate('fechaPromesa', '>=', $fechaInicio);
        }

        // Aplicamos el filtro de fecha final (<=) si viene definido
        if ($fechaFin) {
            $query->whereDate('fechaPromesa', '<=', $fechaFin);
        }

        // Paginamos conservando los parámetros de filtro en los enlaces de paginación
        $ordenes = $query->paginate(50)->withQueryString();

        // Retornamos la vista y pasamos las variables de ordenes y los filtros activos
        return view('ordenes.index', compact('ordenes', 'fechaInicio', 'fechaFin'));
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
