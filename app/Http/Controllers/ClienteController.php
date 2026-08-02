<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\clienteModel;

class ClienteController extends Controller
{
    /**
     * Muestra el listado de clientes activos con filtros opcionales.
     */
    public function index(Request $request)
    {
        $nombre = $request->input('nombre');
        $tipoCliente = $request->input('tipo_cliente');

        $query = clienteModel::where('estado', 1);

        if ($nombre) {
            $query->where('nombre', 'LIKE', '%' . $nombre . '%');
        }

        if ($tipoCliente !== null && $tipoCliente !== '') {
            $query->where('tipo_cli', $tipoCliente);
        }

        $clientes = $query->paginate(10)->withQueryString();


        return view('clientes.index', compact('clientes', 'nombre', 'tipoCliente'));

    }


    /**
     * Muestra el formulario para crear un nuevo cliente.
     */
    public function create()
    {
        return view('crearCliente');
    }

    /**
     * Almacena un nuevo cliente en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_cliente' => 'required|integer',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
        ]);

        // Creación del cliente
        clienteModel::create([
            'nombre' => $request->input('nombre'),
            'tipo_cli' => $request->input('tipo_cliente'),
            'direccion' => $request->input('direccion'),
            'telefono' => $request->input('telefono'),
        ]);

        return redirect()->back()->with('success', 'Cliente agregado con éxito.');
    }

    /**
     * Actualiza los datos de un cliente existente.
     */
    public function update(Request $request)
    {
        $cliente = clienteModel::findOrFail($request->input('id'));
        $cliente->nombre = $request->input('nombre');
        $cliente->direccion = $request->input('direccion');
        $cliente->telefono = $request->input('telefono');
        $cliente->tipo_cli = $request->input('tipo_cliente');
        $cliente->save();

        return redirect()->back()->with('success', 'Cliente actualizado con éxito.');
    }

    /**
     * Busca clientes por nombre (usado en autocompletado).
     */
    public function buscarCliente(Request $request)
    {
        $term = $request->get('term');
        $clientes = clienteModel::where('nombre', 'LIKE', '%' . $term . '%')->get();
        return response()->json($clientes);
    }
}
