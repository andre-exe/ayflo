<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Cargo;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{ 
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empleados = Empleado::paginate(5);
        return view('empleados.index', compact('empleados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cargos = Cargo::all();
        return view('empleados.create', compact('cargos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dui' => 'required|string|unique:empleados,dui',
            'nombresemp' => 'required|string|max:255',
            'apellidosemp' => 'required|string|max:255',
            'telefonemp' => 'nullable|string|max:20',
            'direccionemp' => 'nullable|string|max:255',
            'cargo_id' => 'required|exists:cargos,id', // Cambié temporalmente para probar
        ]);

        Empleado::create($validated);

        return redirect()->route('empleados.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Empleado $empleado)
    {
        return view('empleados.show', compact('empleado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empleado $empleado)
    {
        $cargos = Cargo::all();
        return view('empleados.edit', compact('empleado', 'cargos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empleado $empleado)
{
    // Validación SIN el campo DUI - no se puede editar
    $validated = $request->validate([
        'nombresemp' => 'required|string|max:255',
        'apellidosemp' => 'required|string|max:255',
        'telefonemp' => 'nullable|string|max:20',
        'direccionemp' => 'nullable|string|max:255',
        'cargo_id' => 'required|integer', // o exists:cargos,id cuando tengas la tabla
    ]);

    // Actualizar solo los campos permitidos (sin DUI)
    $empleado->update($validated);

    return redirect()->route('empleados.index')->with('success', 'Empleado actualizado exitosamente');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empleado $empleado)
    {
        $empleado->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado exitosamente');
    }
}