<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\clienteModel;
use App\Models\OrdenModel;
use App\Models\OrdenDetalleModel;
use App\Models\CxcDocumentoModel;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class OrdenController extends Controller
{
    /**
     * Muestra el listado de órdenes con filtros opcionales.
     */
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

    /**
     * Muestra el formulario para crear una nueva orden.
     */
    public function create()
    {
        $clientes = clienteModel::all();
        return view('ordenes.crearOrden', compact('clientes'));
    }

    /**
     * Almacena una nueva orden con sus detalles y documento CXC.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_promesa' => 'required|date',
            'productos' => 'required|array',
            'productos.*.SKU' => 'required|string',
            'productos.*.talla' => 'required|string',
            'productos.*.descripcion' => 'required|string',
            'productos.*.costo' => 'required|numeric',
            'productos.*.precio' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            // Guardar en la tabla orden_enc
            $orden = OrdenModel::create([
                'idCliente' => $request->cliente_id,
                'fechaPromesa' => $request->fecha_promesa,
            ]);

            // Guardar en la tabla orden_detalle
            foreach ($request->productos as $producto) {
                OrdenDetalleModel::create([
                    'idOrden' => $orden->id,
                    'SKU' => $producto['SKU'],
                    'talla' => $producto['talla'],
                    'descripcion' => $producto['descripcion'],
                    'CostoMX' => $producto['costo'],
                    'CostoGT' => $producto['costo'] * 0.43,
                    'PrecioOfrecido' => $producto['precio'],
                ]);
            }

            // Calcular el total de la orden
            $totalOrden = array_sum(array_column($request->productos, 'precio'));

            // Guardar en la tabla cxc_documento
            CxcDocumentoModel::create([
                'Nro_docto' => $orden->id,
                'idCliente' => $request->cliente_id,
                'fechaDocto' => $request->fecha_promesa,
                'montoDocto' => $totalOrden,
                'saldoDocto' => $totalOrden,
                'nroPagos' => 0,
                'totalAcumuladoPagos' => 0,
                'estadoDocto' => 1,
            ]);

            DB::commit();

            // Devolver el ID de la orden creada
            return response()->json(['success' => true, 'orden_id' => $orden->id]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Muestra el detalle de una orden específica.
     */
    public function show($id)
    {
        // Obtén la orden y sus detalles
        $orden = OrdenModel::with('cliente', 'detalleOrden')->findOrFail($id);

        // Retorna la vista con los datos de la orden
        return view('ordenes.detalleOrden', compact('orden'));
    }

    /**
     * Genera el reporte PDF de una orden.
     */
    public function generateReport($id)
    {
        // Obtén la orden y sus detalles
        $orden = OrdenModel::with('cliente', 'detalleOrden')->findOrFail($id);

        $pdf = FacadePdf::loadView('reports.voting-result', compact('orden'));

        return $pdf->stream();
    }
}
