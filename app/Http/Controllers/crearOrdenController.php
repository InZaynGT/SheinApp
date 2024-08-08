<?php

namespace App\Http\Controllers;

use App\Models\clienteModel;
use Illuminate\Http\Request;
use App\Models\OrdenModel;
use App\Models\OrdenDetalleModel;
use App\Models\CxcDocumentoModel;
use Illuminate\Support\Facades\DB;

class crearOrdenController extends Controller
{
    public function index()
    {
        $clientes = clienteModel::all();
        return view('ordenes.crearOrden', compact('clientes'));
    }

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
}
