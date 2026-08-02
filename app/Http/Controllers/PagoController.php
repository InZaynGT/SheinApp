<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PagoEnc;
use App\Models\PagoDetModel;
use App\Models\CxcDocumentoModel;
use App\Models\OrdenModel;
use App\Models\AnticipoModel;
use App\Models\AnticipoDETModel;
use App\Models\cuentaBancariaModel;
use App\Models\clienteModel;
use App\Models\FormaPago;
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Http\Controllers\AnticipoController;


class PagoController extends Controller
{
    /**
     * Muestra el formulario de registro de pagos (deudores).
     */
    public function index()
    {
        $clientes = clienteModel::all();
        $formasPago = FormaPago::all();
        $cuentasBancarias = cuentaBancariaModel::all();
        return view('deudores.index', compact('clientes', 'formasPago', 'cuentasBancarias'));
    }

    /**
     * Muestra el listado de pagos registrados con filtros opcionales.
     */
    public function consultaPagos(Request $request)
    {
        $nombre = $request->input('nombre');
        $formaPago = $request->input('forma_pago');
        $cuentaBancaria = $request->input('cuenta_bancaria');
        $nroDocumento = $request->input('nro_documento');

        $query = PagoEnc::with(['Clientes', 'formaPago', 'CuentasBancarias.Bancos', 'PagosDet.orden'])
            ->where('pago_enc.estado', '=', '1');

        if ($nombre) {
            $query->whereHas('Clientes', function ($q) use ($nombre) {
                $q->where('nombre', 'LIKE', '%' . $nombre . '%');
            });
        }

        if ($formaPago) {
            $query->where('pago_enc.idPago', $formaPago);
        }

        if ($cuentaBancaria) {
            $query->where('pago_enc.ID_CUENTA_BANCARIA', $cuentaBancaria);
        }

        if ($nroDocumento) {
            $query->where('pago_enc.NRO_DOCTO_BANCARIO', 'LIKE', '%' . $nroDocumento . '%');
        }

        $listado_pagos = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();



        $formasPago = FormaPago::all();
        $cuentasBancarias = cuentaBancariaModel::all();

        return view('deudores.consulta_pagos', compact('listado_pagos', 'formasPago', 'cuentasBancarias', 'nombre', 'formaPago', 'cuentaBancaria', 'nroDocumento'));
    }



    /**
     * Registra un nuevo pago y aplica el monto a los documentos seleccionados.
     */
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
            if ($formaPago == 5) {
                $anticipoController = new AnticipoController;
                $response = $anticipoController->getAnticipos($clienteId);
                $totalAnticipos = json_decode($response->getContent(), true);
                if ($totalAnticipos < $montoTotal) {
                    throw new \Exception("El anticipo actual es menor que el anticipo por usar.");
                }
            }

            if ($formaPago != 5) {
                $pagoEnc = PagoEnc::create([
                    'idCliente' => $clienteId,
                    'idPago' => $formaPago,
                    'fecha' => $fechaContabilizacion,
                    'referencia' => $referencia,
                    'monto' => $montoTotal,
                    'NRO_DOCTO_BANCARIO' => in_array($formaPago, [3, 4]) ? $numeroDocumento : null,
                    'ID_CUENTA_BANCARIA' => in_array($formaPago, [3, 4]) ? $cuentaBancaria : null,
                    'estado' => 1
                ]);

                if (in_array($formaPago, [3, 4])) {
                    $cuentaBancariaModel = CuentaBancariaModel::findOrFail($cuentaBancaria);
                    $nuevoSaldo = $cuentaBancariaModel->saldoActual + $montoTotal;

                    DB::table('banco_deposito')->insert([
                        'ID_CUENTA_BANCARIA' => $cuentaBancaria,
                        'fecha' => $fechaContabilizacion,
                        'nro_referencia' => $numeroDocumento,
                        'debe' => $montoTotal,
                        'haber' => 0,
                        'notas' => $referencia,
                        'estado' => 1,
                        'saldoActual' => $nuevoSaldo,
                    ]);

                    $cuentaBancariaModel->saldoActual = $nuevoSaldo;
                    $cuentaBancariaModel->save();
                }
            }

            usort($documentosSeleccionados, function ($a, $b) {
                return strtotime($a['fechaDocto']) - strtotime($b['fechaDocto']);
            });

            $anticipos_lista = AnticipoController::obtenerAnticiposRestantes($clienteId)->toArray();


            usort($anticipos_lista, function ($a, $b) {
                return strtotime($a['fecha']) - strtotime($b['fecha']);
            });

            foreach ($documentosSeleccionados as $documento) {
                $cxcDocumento = CxcDocumentoModel::findOrFail($documento['id']);
                $saldoDocumento = $documento['saldo'];
                $ordenEnc = OrdenModel::where('id', $cxcDocumento->Nro_docto)->firstOrFail();

                while ($saldoDocumento > 0 && $montoTotal > 0) {
                    // Verifica si la forma de pago es 5 (anticipos)
                    if ($formaPago == 5) {
                        foreach ($anticipos_lista as $anticipo) {
                            if ($montoTotal <= 0) {
                                break;
                            }

                            $montoAplicadoAnticipo = min($anticipo['anticipoRestante'], $montoTotal);
                            $montoAplicadoDocumento = min($saldoDocumento, $montoAplicadoAnticipo);

                            if ($montoAplicadoDocumento > 0) {
                                $anticipoModel = AnticipoModel::findOrFail($anticipo['id']);
                                $anticipoModel->anticipoRestante -= $montoAplicadoDocumento;

                                if ($anticipoModel->anticipoRestante < 0) {
                                    throw new \Exception("El saldo del anticipo no puede ser negativo.");
                                }

                                $anticipoModel->save();

                                $cxcDocumento->saldoDocto -= $montoAplicadoDocumento;
                                $cxcDocumento->nroPagos += 1;
                                $cxcDocumento->totalAcumuladoPagos += $montoAplicadoDocumento;

                                if ($cxcDocumento->saldoDocto < 0) {
                                    throw new \Exception("El saldo del documento no puede ser negativo.");
                                }

                                $cxcDocumento->save();

                                PagoDetModel::create([
                                    'ID_CXC_PAGO' => $anticipoModel->pagoENC->id,
                                    'ID_CXC' => $cxcDocumento->id,
                                    'monto_aplicado' => $montoAplicadoDocumento,
                                ]);

                                AnticipoDetModel::create([
                                    'idAnticipo' => $anticipoModel->id,
                                    'idOrden' => $cxcDocumento->id,
                                    'montoAplicado' => $montoAplicadoDocumento
                                ]);

                                $montoTotal -= $montoAplicadoDocumento;
                                $saldoDocumento -= $montoAplicadoDocumento;
                            }
                        }
                    } else {
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
                            $ordenEnc = OrdenModel::where('id', $cxcDocumento->Nro_docto)->firstOrFail();

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
                    }
                }
            }

            DB::commit();
            if ($formaPago != 5) {
                return response()->json(['success' => true, 'orden_id' => $pagoEnc->id]);
            } else {
                return response()->json(['success' => true, 'message' => 'Anticipo registrado exitosamente']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Registra un nuevo anticipo para un cliente.
     */
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
        $anticipoRestante = $monto;

        // Crear registro en PAGO_ENC
        $formaPagoAnticipo = 5;
        $pagoEnc = PagoEnc::create([
            'idCliente' => $idCliente,
            'idPago' => $formaPagoAnticipo,
            'fecha' => $fechaContabilizacion,
            'referencia' => $observaciones,
            'monto' => $monto,
            'NRO_DOCTO_BANCARIO' => in_array($formaPago, [3, 4]) ? $numeroDocumento : null,
            'ID_CUENTA_BANCARIA' => in_array($formaPago, [3, 4]) ? $cuentaBancaria : null
        ]);

        //Crear Registro en anticipoenc
        $anticipo = new AnticipoModel();
        $anticipo->idCliente = $idCliente;
        $anticipo->formaPago = $formaPago;
        $anticipo->fecha = $fechaContabilizacion;
        $anticipo->monto = $monto;
        $anticipo->observaciones = $observaciones;
        $anticipo->aplicado = $anticipo_aplicado;
        $anticipo->anticipoRestante = $anticipoRestante;
        $anticipo->idPagoEnc = $pagoEnc->id;
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

        return response()->json(['success' => true, 'orden_id' => $pagoEnc->id]);
    }

    /**
     * Genera el reporte PDF de un pago.
     */
    public function generateReport($id)
    {
        $listado_pagos = PagoEnc::with(['Clientes', 'formaPago', 'CuentasBancarias.Bancos', 'PagosDet.orden'])
            ->where('id', '=', $id)->get();

        $pdf = FacadePdf::loadView('reports.listado_pagos', compact('listado_pagos'));

        return $pdf->stream();
    }
}
