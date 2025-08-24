<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Cliente;
use App\Models\Responsable;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         // Traemos la info de pago con cliente, responsable y trabajo para mostrar datos completos
        $pagos = Pago::with('cliente', 'responsable')->paginate(10);

        return view('pagos.index', compact('pagos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    $clientes = Cliente::all();
    $responsables = Responsable::all();
 

    return view('pagos.create', compact('clientes', 'responsables')); 
 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
    'cliente' => 'required|exists:cliente,id',
    'responsable' => 'nullable|responsable,id',
    'montototal' => 'required|numeric|min:0', 
    'abono' => 'required|numeric|min:0',
    'fechaabono' => 'required|date'
]);

    Pago::create([
        'id_cliente' => $request->cliente,       
        'id_responsable' => $request->responsable ?: null, //convierte la cadena vacia a nulkl
        'montototal' => $request->montototal,
        'abono' => $request->abono,
        'fechaabono' => $request->fechaabono,
    ]);

    return redirect()->route('pagos.index');
    

    }

    /**
     * Display the specified resource.
     */
    public function show(Pago $pago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pago $pago)
    {
        $clientes = Cliente::all();
        $responsables = Responsable::all();
        
        return view('pagos.edit', compact('pago', 'clientes', 'responsables'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago)
    {
         $request->validate([
            'cliente' => 'required|exists:cliente,id',
            'responsable' => 'nullable|exists:responsable,id',
            'montototal' => 'required|numeric|min:0', 
            'abono' => 'required|numeric|min:0',
            'fechaabono' => 'required|date'
        ]);

        $pago->update([
            'id_cliente' => $request->cliente,       
            'id_responsable' => $request->responsable ?: null, 
            'montototal' => $request->montototal,
            'abono' => $request->abono,
            'fechaabono' => $request->fechaabono,
        ]);

        return redirect()->route('pagos.index');
       }     

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago)
    {
        $pago->delete();
        return redirect()->route('pagos.index'); 
    
    }
}
