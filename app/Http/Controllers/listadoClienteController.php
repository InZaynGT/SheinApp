<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\clienteModel;


class listadoClienteController extends Controller
{
    public function index()
    {
        //Mandamos a traer la info de la BDD y la almacenamos en $ordenes
        $clientes = clienteModel::all();
        //Retornamos la vista (pasamos la ruta) y pasamos el parámetro de la variable de ordenes
        return view('clientes.index', compact('clientes'));
    }


    public function update(Request $request)
    {
        $cliente = ClienteModel::findOrFail($request->input('id'));
        $cliente->nombre = $request->input('nombre');
        $cliente->direccion = $request->input('direccion');
        $cliente->telefono = $request->input('telefono');
        $cliente->tipo_cli = $request->input('tipo_cliente');
        // Actualiza otros campos según sea necesario
        $cliente->save();

        return redirect()->back()->with('success', 'Cliente actualizado con éxito.');
    }

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
}
