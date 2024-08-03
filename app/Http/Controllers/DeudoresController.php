<?php

namespace App\Http\Controllers;

use App\Models\AnticipoModel;
use Illuminate\Http\Request;
use App\Models\ClienteModel;
use App\Models\CxcDocumentoModel;
use App\Models\PagoDetModel;
use App\Models\FormaPago;
use App\Models\cuentaBancariaModel;
use App\Models\PagoEnc;
use Illuminate\Support\Facades\DB;

class DeudoresController extends Controller
{
    //DE PREFERENCIA CARGAR LO QUE NECESITAMOS DE UNA VEZ CON EL INDEX (CLIENTE PENDIENTE DE MODIFICAR)
    public function index()
    {
        $clientes = ClienteModel::all();
        $formasPago = FormaPago::all();
        $cuentasBancarias = cuentaBancariaModel::all();
        return view('deudores.index', compact('clientes', 'formasPago', 'cuentasBancarias'));
    }

    public function getDocumentosDeudores($idCliente)
    {
        $documentos = CxcDocumentoModel::where('idCliente', $idCliente)
            ->where('saldoDocto', '>', 0)->get();
        return response()->json($documentos);
    }

    public function getPagosAplicados($idDocumento)
    {
        // Obtener pagos aplicados para un documento específico
        $pagosAplicados = PagoDetModel::where('ID_CXC', $idDocumento)
            ->with(['pagoEnc.formaPago'])
            ->get();
        // Retornar los datos en formato JSON
        return response()->json($pagosAplicados);
    }

    public function store(Request $request)
    {
        $clienteId = $request->input('cliente_id');
        $formaPago = $request->input('forma_pago');
        $fechaContabilizacion = $request->input('fecha_contabilizacion');
        $referencia = $request->input('referencia');
        $montoTotal = $request->input('monto_total');
        $documentosSeleccionados = $request->input('documentos_seleccionados');
        $numeroDocumento = $request->input('numero_documento');
        $cuentaBancaria = $request->input('cuenta_bancaria');

        DB::beginTransaction();

        try {
            // Crear registro en PAGO_ENC
            $pagoEnc = PagoEnc::create([
                'idCliente' => $clienteId,
                'idPago' => $formaPago,
                'fecha' => $fechaContabilizacion,
                'referencia' => $referencia,
                'monto' => $montoTotal,
                'NRO_DOCTO_BANCARIO' => in_array($formaPago, [3, 4]) ? $numeroDocumento : null,
                'ID_CUENTA_BANCARIA' => in_array($formaPago, [3, 4]) ? $cuentaBancaria : null
            ]);

            // Ordenar documentos seleccionados por fecha en orden ascendente
            usort($documentosSeleccionados, function ($a, $b) {
                return strtotime($a['fechaDocto']) - strtotime($b['fechaDocto']);
            });

            // Guardar el monto total inicial para usarlo en la inserción del depósito
            $montoTotalInicial = $montoTotal;

            // Procesar documentos seleccionados
            foreach ($documentosSeleccionados as $documento) {
                $cxcDocumento = CxcDocumentoModel::findOrFail($documento['id']);
                $montoAplicado = min($documento['saldo'], $montoTotal);

                if ($montoAplicado > 0) {
                    $cxcDocumento->saldoDocto -= $montoAplicado;
                    $cxcDocumento->nroPagos += 1;
                    $cxcDocumento->totalAcumuladoPagos += $montoAplicado;

                    if ($cxcDocumento->saldoDocto < 0) {
                        throw new \Exception("El saldo del documento no puede ser negativo.");
                    }

                    $cxcDocumento->save();

                    PagoDetModel::create([
                        'ID_CXC_PAGO' => $pagoEnc->id,
                        'ID_CXC' => $cxcDocumento->id,
                        'monto_aplicado' => $montoAplicado
                    ]);

                    $montoTotal -= $montoAplicado;

                    if ($montoTotal <= 0) {
                        break;
                    }
                }
            }

            // Verificar si el monto total no se ha agotado completamente
            if ($montoTotal > 0) {
                throw new \Exception("El monto total a aplicar excede el monto disponible.");
            }

            // Insertar en banco_deposito si hay referencia y cuenta bancaria seleccionada
            if (in_array($formaPago, [3, 4])) {
                $cuentaBancariaModel = CuentaBancariaModel::findOrFail($cuentaBancaria);
                $nuevoSaldo = $cuentaBancariaModel->saldoActual + $montoTotalInicial;  // Usar el monto total inicial aquí

                // Insertar el depósito
                DB::table('banco_deposito')->insert([
                    'ID_CUENTA_BANCARIA' => $cuentaBancaria,
                    'fecha' => $fechaContabilizacion,
                    'nro_referencia' => $numeroDocumento,
                    'debe' => $montoTotalInicial,  // Usar el monto total inicial aquí
                    'haber' => 0,
                    'notas' => $referencia,
                    'estado' => 1,
                    'saldoActual' => $nuevoSaldo,
                ]);

                // Actualizar el saldo en la cuenta bancaria
                $cuentaBancariaModel->saldoActual = $nuevoSaldo;
                $cuentaBancariaModel->save();
            }

            DB::commit();

            //Devolvemos el ID del pago creado
            return response()->json(['success' => true, 'orden_id' => $pagoEnc->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json(['success' => true], 200);
    }

    public function storeAnticipo(Request $request)
    {   
        //se inserta aplicado = 0 debido a que es un nuevo anticipo que no se ha usado para nada.
        $idCliente = $request->input('cliente_id');
        $formaPago = $request->input('forma_pago');
        $fechaContabilizacion = $request->input('fecha');
        $cuentaBancaria = $request->input('cuenta_bancaria');
        $numeroDocumento = $request->input('nro_documento');
        $observaciones = $request->input('observaciones');
        $monto = $request->input('monto');
        $anticipo_aplicado = 0;

        $anticipo = new AnticipoModel();
        $anticipo->idCliente = $idCliente;
        $anticipo->formaPago = $formaPago;
        $anticipo->fecha = $fechaContabilizacion;
        $anticipo->monto = $monto;
        $anticipo->observaciones = $observaciones;
        $anticipo->aplicado = $anticipo_aplicado;
        $anticipo->save();
        
        $montoTotalInicial = $monto;

        if (in_array($request->input('forma_pago'), [3, 4])) {
            $cuentaBancariaModel = CuentaBancariaModel::findOrFail($cuentaBancaria);
            $nuevoSaldo = $cuentaBancariaModel->saldoActual + $montoTotalInicial;  // Usar el monto total inicial aquí

            // Insertar el depósito
            DB::table('banco_deposito')->insert([
                'ID_CUENTA_BANCARIA' => $cuentaBancaria,
                'fecha' => $fechaContabilizacion,
                'nro_referencia' => $numeroDocumento,
                'debe' => $montoTotalInicial,  // Usar el monto total inicial aquí
                'haber' => 0,
                'notas' => $observaciones,
                'estado' => 1,
                'saldoActual' => $nuevoSaldo,
            ]);

            // Actualizar el saldo en la cuenta bancaria
            $cuentaBancariaModel->saldoActual = $nuevoSaldo;
            $cuentaBancariaModel->save();
        }


        return response()->json(['success' => true, 'message' => 'Anticipo registrado exitosamente']);
    }
}
