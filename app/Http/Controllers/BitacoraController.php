<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use App\Models\Responsable;
use App\Models\Trabajo;
use App\Models\Cliente;
class BitacoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         // Traemos la info de bitacora con cliente, responsable y trabajo para mostrar datos completos
        $bitacoras = Bitacora::with('cliente', 'responsable', 'trabajo')->paginate(10);

        return view('bitacoras.index', compact('bitacoras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      $clientes = Cliente::all();
    $responsables = Responsable::all();
    $trabajos = Trabajo::all();

    return view('bitacoras.create', compact('clientes', 'responsables', 'trabajos')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     $request->validate([
    'cliente' => 'required|exists:clientes,id',
    'responsable' => 'required|exists:responsables,id', 
    'idtrabajo' => 'required|exists:trabajos,id',
    'monto' => 'required|numeric|min:0',
    'fechatrabajobitacora' => 'required|date',
    'descripcionbitacora' => 'nullable|string|max:500'
]);

    Bitacora::create($request->all());

    return redirect()->route('bitacoras.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bitacora $bitacora)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bitacora $bitacora)
    {
    $cliente = Cliente::all();
    $responsable = Responsable::all();
    $trabajos = Trabajo::all();

    return view('bitacoras.edit', compact('bitacora', 'cliente', 'responsable', 'trabajos'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bitacora $bitacora)
    {
    $request->validate([
    'cliente' => 'required|exists:cliente,id',
    'responsable' => 'required|exists:responsable,id', 
    'idtrabajo' => 'required|exists:trabajos,id',
    'monto' => 'required|numeric|min:0',
    'fechatrabajobitacora' => 'required|date',
    'descripcionbitacora' => 'nullable|string|max:500'
]);
        $bitacora->update($request->all());

        return redirect()->route('bitacoras.index');   //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bitacora $bitacora)
    {
        $bitacora->delete();
        return redirect()->route('bitacoras.index'); //
    
    }
}
