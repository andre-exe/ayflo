<?php

namespace App\Http\Controllers;
use App\Models\Cliente;
use App\Models\Responsable;
use Illuminate\Http\Request;

class ResponsableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $responsables = Responsable::with('cliente')-> paginate(10);     
       // $responsables = Responsable::paginate(5);
    return view('responsables.index', compact('responsables'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       //return view('responsables.create');
       $clientes = Cliente::all();
    return view('responsables.create', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'nombresresp' => 'required|string|max:255',
            'apellidosresp' => 'required|string|max:255',
            'telefonoresp' => 'required|string|max:20',
            'correoresp' => 'nullable|email',
            'cliente'=> 'required|exists:cliente,id',
            
        ]);

        Responsable::create($request->all());

         return redirect()->route('responsables.index')->with('success', 'Responsable registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Responsable $responsable)
    {
      return view('responsables.show', compact('responsable'));   //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Responsable $responsable)
    {
        $clientes = Cliente::all();
        return view('responsables.edit', compact('responsable', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Responsable $responsable)
    {
     $request->validate([
            'nombresresp' => 'required|string|max:255',
            'apellidosresp' => 'required|string|max:255',
            'telefonoresp' => 'required|string|max:20',
            'correoresp' => 'nullable|email',
            'cliente'=> 'required|exists:cliente,id' /*hace referencia a la tabla cliente y su id*/
        ]);

        $responsable->update($request->all());

        return redirect()->route('responsables.index')->with('success', 'Responsable actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Responsable $responsable)
    {
        $responsable->delete();
        return redirect()->route('responsables.index');
    }
}
