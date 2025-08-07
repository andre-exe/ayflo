<?php

namespace App\Http\Controllers;

use App\Models\Egreso;
use Illuminate\Http\Request;

class EgresoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $egresos = Egreso::paginate(5);
    return view('egresos.index', compact('egresos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('egresos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'montoegreso' => 'required|string|max:255',
            'descripcionegreso' => 'required|string|max:255',
            'fecha' => 'required|string|max:20',
            
        ]);

        Egreso::create($request->all());

         return redirect()->route('egresos.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Egreso $egreso)
    {
 //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Egreso $egreso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Egreso $egreso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Egreso $egreso)
    {
        //
    }
}
