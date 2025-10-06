<?php

namespace App\Http\Controllers;
use App\Models\Pago;
use App\Models\Cliente;
use App\Models\Responsable;
use App\Models\Trabajo;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pagos = Pago::with(['cliente', 'responsable', 'trabajo'])
            ->orderBy('fecha_creacion', 'desc')
            ->paginate(10);
        
        return view('pagos.index', compact('pagos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nombrescliente')->get();
        $responsables = Responsable::orderBy('nombresresp')->get();
        $trabajos = Trabajo::orderBy('fechatrabajo', 'desc')->get();
        
        return view('pagos.create', compact('clientes', 'responsables', 'trabajos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     $request->validate([
            'id_cliente' => 'required|exists:cliente,id',
            'id_responsable' => 'nullable|exists:responsable,id',
            'id_trabajo' => 'required|exists:trabajos,id',
            'monto_total' => 'required|numeric|min:0',
            'fecha_creacion' => 'required|date',
        ]);

        $pago = new Pago();
        $pago->id_cliente = $request->id_cliente;
        $pago->id_responsable = $request->id_responsable;
        $pago->id_trabajo = $request->id_trabajo;
        $pago->monto_total = $request->monto_total;
        $pago->monto_pendiente = $request->monto_total; // Inicia igual al total
        $pago->estado = 'pendiente';
        $pago->fecha_creacion = $request->fecha_creacion;
        $pago->save();

        return redirect()->route('pagos.index')
            ->with('success', 'Pago creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pago $pago)
    {
        $pago->load(['cliente', 'responsable', 'trabajo', 'abonos']);
        return view('pagos.show', compact('pago'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pago $pago)
    {
        $clientes = Cliente::orderBy('nombrescliente')->get();
        $responsables = Responsable::orderBy('nombresresp')->get();
        $trabajos = Trabajo::orderBy('fechatrabajo', 'desc')->get();
        
        return view('pagos.edit', compact('pago', 'clientes', 'responsables', 'trabajos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago)
    {
        $request->validate([
            'id_cliente' => 'required|exists:cliente,id',
            'id_responsable' => 'nullable|exists:responsable,id',
            'id_trabajo' => 'required|exists:trabajos,id',
            'monto_total' => 'required|numeric|min:0',
            'fecha_creacion' => 'required|date',
        ]);

        $pago->update($request->all());

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
