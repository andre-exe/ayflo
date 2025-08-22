<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Cargo;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{ 
    public function index()
    {
        $empleados = Empleado::with(['cargoRelacion'])->latest()->paginate(10);
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        $cargos = Cargo::all(); // Variable en plural para claridad
        return view('empleados.create', compact('cargos')); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
           
            'nombresemp' => 'required|string|max:255',
            'apellidosemp' => 'required|string|max:255',
            'telefonemp' => 'nullable|string|max:20',
            'direccionemp' => 'nullable|string|max:255',
            'cargo_id' => 'required|exists:cargo,id', 
        ]);

        Empleado::create($validated);
        return redirect()->route('empleados.index');
    }

    public function show(Empleado $empleado)
    {
        return view('empleados.show', compact('empleado'));
    }

    public function edit(Empleado $empleado)
    {
        $cargos = Cargo::all(); // Variable en plural
        return view('empleados.edit', compact('empleado', 'cargos')); 
    }

    public function update(Request $request, Empleado $empleado)
    {
        $validated = $request->validate([
            'nombresemp' => 'required|string|max:255',
            'apellidosemp' => 'required|string|max:255',
            'telefonemp' => 'nullable|string|max:20',
            'direccionemp' => 'nullable|string|max:255',
            'cargo_id' => 'required|exists:cargo,id', 
        ]);

        $empleado->update($validated);
        return redirect()->route('empleados.index');
    }

    public function destroy(Empleado $empleado)
    {
        $empleado->delete();
        return redirect()->route('empleados.index');
    }
}