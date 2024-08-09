<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenModel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Livewire\Component;

class listadoOrdenController extends Controller
{
    public function index()
    {
        //Mandamos a traer la info de la BDD y la almacenamos en $ordenes
        $ordenes = OrdenModel::with('cliente', 'CXC')
        ->where('estado','=','1')
        ->orderBy('id', 'desc')->paginate(50);        
        //Retornamos la vista (pasamos la ruta) y pasamos el parámetro de la variable de ordenes
        return view('ordenes.index', compact('ordenes'));
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
