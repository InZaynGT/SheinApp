<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CxcDocumentoModel;
use App\Models\PagoDetModel;

class CxcDocumentoController extends Controller
{
    /**
     * Muestra el listado de deudores (clientes con saldo pendiente).
     */
    public function index()
    {
        $deudores = CxcDocumentoModel::selectRaw('cxcdocumento.idCliente, clientes.nombre, COALESCE(SUM(cxcdocumento.saldoDocto), 0) as saldo_total')
            ->join('clientes', 'cxcdocumento.idCliente', '=', 'clientes.id')
            ->where('cxcdocumento.saldoDocto', '>', 0)
            ->orderBy('saldo_total', 'DESC')
            ->groupBy('cxcdocumento.idCliente', 'clientes.nombre')
            ->get();

        return view('deudores.listado_deudores', compact('deudores'));
    }

    /**
     * Obtiene los documentos con saldo pendiente de un cliente.
     */
    public function getDocumentosDeudores($idCliente)
    {
        $documentos = CxcDocumentoModel::where('idCliente', $idCliente)
            ->where('saldoDocto', '>', 0)->get();
        return response()->json($documentos);
    }

    /**
     * Obtiene los pagos aplicados a un documento específico.
     */
    public function getPagosAplicados($idDocumento)
    {
        // Obtener pagos aplicados para un documento específico
        $pagosAplicados = PagoDetModel::where('ID_CXC', $idDocumento)
            ->with(['pagoEnc.formaPago'])
            ->get();
        // Retornar los datos en formato JSON
        return response()->json($pagosAplicados);
    }
}
