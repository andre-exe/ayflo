<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $clientes = Cliente::paginate(5);
    return view('clientes.index', compact('clientes'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombrescliente' => 'required|string|max:255',
            'apellidoscliente' => 'required|string|max:255',
            'telefonocliente' => 'required|string|max:20',
            'correocliente' => 'nullable|email',
            
        ]);

        Cliente::create($request->all());

         return redirect()->route('clientes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombrescliente' => 'required|string|max:255',
            'apellidoscliente' => 'required|string|max:255',
            'telefonocliente' => 'required|string|max:20',
            'correocliente' => 'nullable|email',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
         $cliente->delete();
        return redirect()->route('clientes.index');
    }
}
