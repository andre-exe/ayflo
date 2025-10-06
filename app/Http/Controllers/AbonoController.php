<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\Pago;
use Illuminate\Http\Request;

class AbonoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'id_pago' => 'required|exists:pago,id',
            'monto_abonado' => 'required|numeric|min:0.01',
            'fecha_abono' => 'required|date',
        ]);

        $pago = Pago::findOrFail($request->id_pago);

        // Validar que el abono no exceda el monto pendiente
        if ($request->monto_abonado > $pago->monto_pendiente) {
            return back()->withErrors([
                'monto_abonado' => 'El monto abonado no puede ser mayor al monto pendiente ($' . number_format($pago->monto_pendiente, 2) . ')'
            ])->withInput();
        }

        // Contar abonos existentes para asignar el número
        $numeroAbono = $pago->abonos()->count() + 1;

        $abono = new Abono();
        $abono->id_pago = $request->id_pago;
        $abono->monto_abonado = $request->monto_abonado;
        $abono->fecha_abono = $request->fecha_abono;
        $abono->numero_abono = $numeroAbono;
        $abono->save();

        // El trigger actualiza automáticamente monto_pendiente y estado
        
        return redirect()->route('pagos.show', $request->id_pago);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Abono $abono)
    {
         $idPago = $abono->id_pago;
        $abono->delete();
        
        return redirect()->route('pagos.show', $idPago);
    }
}
