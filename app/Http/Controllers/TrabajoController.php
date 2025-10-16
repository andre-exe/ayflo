<?php

namespace App\Http\Controllers;

use App\Models\Trabajo;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrabajoController extends Controller
{
    public function index(Request $request)
    {
        $query = Trabajo::with(['clienteRelacion', 'responsable', 'empleado'])->latest();

    // Filtrar por estado si se envía en la URL (excepto "todos")
    if ($request->filled('estado') && $request->estado !== 'todos') {
        $query->where('estado', $request->estado);
    }

    $trabajos = $query->paginate(10);

    return view('trabajos.index', compact('trabajos'));


    }

    public function create()
    {
        $clientes = Cliente::all();
        $empleados = Empleado::all();
        $responsables = Responsable::all();
        return view('trabajos.create', compact('clientes', 'empleados', 'responsables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            
            'cliente' => 'required|exists:cliente,id',
            'responsable' => 'nullable|exists:responsable,id',
            'empleado' => 'required|exists:empleado,id',
            'fechatrabajo' => 'required|date',
            'estado' => 'required|string|max:50',
            'montototal' => 'required|numeric|min:0',
            'montopagado' => 'nullable|numeric|min:0',
            'nombretrb' => 'required|string|max:100',
           
            'archivoescritura.*' => 'nullable|file|max:5120',
            'archivoesquema.*' => 'nullable|file|max:5120',
            'puntosrecorrido.*' => 'nullable|file|max:5120',
            'archivodwg.*' => 'nullable|file|max:5120',
            'archivokml.*' => 'nullable|file|max:5120',
            'notas.*' => 'nullable|file|max:5120',
            'insumos.*' => 'nullable|file|max:5120',
        ]);

        
        $data = [
            'cliente' => $request->cliente,
            'responsable' => $request->responsable,
            'empleado' => $request->empleado,
            'fechatrabajo' => $request->fechatrabajo,
            'estado' => $request->estado,
            'nombretrb' => $request->nombretrb,
            'montototal' => $request->montototal ?? 0,
            'montopagado' => $request->montopagado ?? 0,
        ];

        // Manejar archivos múltiples - nombres exactos del modelo
        $camposArchivos = [
            'archivoescritura',  // Nombre del modelo
            'archivoesquema',    // Nombre del modelo  
            'puntosrecorrido', 
            'archivodwg',        
            'archivokml',        
            'notas',
            'insumos'
        ];

        foreach ($camposArchivos as $campo) {
            if ($request->hasFile($campo)) {
                $archivos = [];
                foreach ($request->file($campo) as $archivo) {
                    $ruta = $archivo->store("trabajos/{$campo}", 'public');
                    $archivos[] = $ruta;
                }
                // Guardar como JSON ya que el campo es TEXT y puede contener múltiples rutas
                $data[$campo] = json_encode($archivos);
            } else {
                $data[$campo] = null;
            }
        }

        Trabajo::create($data);

        return redirect()->route('trabajos.index');
    }

    public function show(Trabajo $trabajo)
    {
        return view('trabajos.show', compact('trabajo'));
    }

    public function edit(Trabajo $trabajo)
    {
        $clientes = Cliente::all();
        $empleados = Empleado::all();
        $responsables = Responsable::all();
        return view('trabajos.edit', compact('trabajo', 'clientes', 'empleados', 'responsables'));
    }

    public function update(Request $request, Trabajo $trabajo)
    {
         $request->validate([
        'cliente' => 'required|exists:cliente,id',     
        'responsable' => 'nullable|exists:responsable,id',  
        'empleado' => 'required|exists:empleado,id',        
        'fechatrabajo' => 'required|date',
        'estado' => 'required|string|max:50',
        'montototal' => 'nullable|numeric|min:0',            
        'montopagado' => 'nullable|numeric|min:0',
        'nombretrb' => 'required|string|max:100',
        
        'archivoescritura.*' => 'nullable|file|max:5120',   // Corregido nombre
        'archivoesquema.*' => 'nullable|file|max:5120',     
        'puntosrecorrido.*' => 'nullable|file|max:5120',
        'archivodwg.*' => 'nullable|file|max:5120',
        'archivokml.*' => 'nullable|file|max:5120',
        'notas.*' => 'nullable|file|max:5120',
        'insumos.*' => 'nullable|file|max:5120',
        ]);

        
        $data = [
        'cliente' => $request->cliente,           // Sin _id
        'responsable' => $request->responsable,   // Sin _id
        'empleado' => $request->empleado,         // Sin _id
        'fechatrabajo' => $request->fechatrabajo,
        'estado' => $request->estado,
        'nombretrb' => $request->nombretrb,
        'montototal' => $request->montototal ?? 0,  // Corregido
        'montopagado' => $request->montopagado ?? 0,
        ];

        // Manejar archivos múltiples
        $camposArchivos = [
            'archivoescritura',  // Nombre del modelo
            'archivoesquema',    // Nombre del modelo  
            'puntosrecorrido', 
            'archivodwg',        
            'archivokml',        
            'notas',
            'insumos'
        ];

        foreach ($camposArchivos as $campo) {
            if ($request->hasFile($campo)) {
                $archivos = [];
                foreach ($request->file($campo) as $archivo) {
                    $ruta = $archivo->store("trabajos/{$campo}", 'public');
                    $archivos[] = $ruta;
                }
                $data[$campo] = json_encode($archivos);
            }
            // Si no hay nuevos archivos, mantener los existentes
        }

        $trabajo->update($data);

        return redirect()->route('trabajos.index');
    }

   public function descargarArchivo($trabajo_id, $tipo_archivo)
{
    try {
        // Buscar el trabajo
        $trabajo = Trabajo::find($trabajo_id);
        
        if (!$trabajo) {
            return response()->json(['error' => 'Trabajo no encontrado'], 404);
        }

        // Verificar que el campo existe y tiene datos
        if (!$trabajo->$tipo_archivo) {
            return response()->json(['error' => 'No hay archivos de este tipo'], 404);
        }

        // Obtener el array de archivos del tipo especificado
        $archivos = json_decode($trabajo->$tipo_archivo, true);
        
        if (!$archivos || !is_array($archivos) || empty($archivos)) {
            return response()->json(['error' => 'No se encontraron archivos válidos'], 404);
        }

        // Tomar el primer archivo
        $archivo_encontrado = $archivos[0];

        // Verificar que el archivo existe en el storage
        if (!Storage::disk('public')->exists($archivo_encontrado)) {
            return response()->json(['error' => 'El archivo no existe en el servidor'], 404);
        }

        // Obtener la ruta completa del archivo
        $rutaArchivo = Storage::disk('public')->path($archivo_encontrado);
        
        // Obtener el nombre del archivo
        $nombreArchivo = basename($archivo_encontrado);
        
        // Descargar el archivo
        return response()->download($rutaArchivo, $nombreArchivo);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Error interno: ' . $e->getMessage()], 500);
    }
}


public function testDescarga(Request $request)
{
    // Mostrar todos los parámetros recibidos
    dd([
        'trabajo' => $request->trabajo,
        'tipo_archivo' => $request->tipo_archivo,
        'todos_parametros' => $request->all()
    ]);
}

        

    public function destroy(Trabajo $trabajo)
    {
        $trabajo->delete();
        return redirect()->route('trabajos.index');
    }

    public function pendientes()
{
    $trabajos = Trabajo::with(['clienteRelacion', 'responsable', 'empleado'])
        ->where('estado', 'pendiente')
        ->latest()
        ->paginate(10);

    return view('trabajos.pendientes', compact('trabajos'));
}

public function completados()
{
    $trabajos = Trabajo::with(['clienteRelacion', 'responsable', 'empleado'])
        ->where('estado', 'completado')
        ->latest()
        ->paginate(10);

    return view('trabajos.completados', compact('trabajos'));
}

public function en_progreso()
{
    $trabajos = Trabajo::with(['clienteRelacion', 'responsable', 'empleado'])
        ->where('estado', 'en_progreso')
        ->latest()
        ->paginate(10);

    return view('trabajos.en_progreso', compact('trabajos'));
}

public function cancelados()
{
    $trabajos = Trabajo::with(['clienteRelacion', 'responsable', 'empleado'])
        ->where('estado', 'cancelado')
        ->latest()
        ->paginate(10);

    return view('trabajos.cancelados', compact('trabajos'));
}
// Agrega este método en tu TrabajoController.php

public function porAnio(Request $request)
{
    $anoSeleccionado = $request->input('ano');
    
    // Obtener todos los trabajos para estadísticas generales
    $trabajos = Trabajo::with(['clienteRelacion', 'responsable', 'empleado'])->get();
    
    // Obtener años disponibles para el filtro (PostgreSQL sintaxis)
    $anosDisponibles = Trabajo::selectRaw('EXTRACT(YEAR FROM fechatrabajo) as ano')
        ->distinct()
        ->orderBy('ano', 'desc')
        ->pluck('ano');
    
    if ($anoSeleccionado) {
        // Vista de DETALLE: Mostrar todos los trabajos del año seleccionado
        $trabajos = Trabajo::with(['clienteRelacion', 'responsable', 'empleado'])
            ->whereRaw('EXTRACT(YEAR FROM fechatrabajo) = ?', [$anoSeleccionado])
            ->orderBy('fechatrabajo', 'desc')
            ->get();
        
        $trabajosPorAno = collect(); // Colección vacía para la vista
        
    } else {
        // Vista de RESUMEN: Agrupar por año (PostgreSQL sintaxis)
        $trabajosPorAno = Trabajo::selectRaw('
                EXTRACT(YEAR FROM fechatrabajo) as ano,
                COUNT(*) as cantidad,
                SUM(montototal) as monto_total,
                SUM(montopagado) as monto_pagado
            ')
            ->groupBy('ano')
            ->orderBy('ano', 'desc')
            ->get();
    }
    
    return view('trabajos.por_anio', compact(
        'trabajos',
        'trabajosPorAno',
        'anosDisponibles'
    ));
}

// inicio de consulta por empleado 
public function porEmpleado(Request $request)
{
    $empleadoId = $request->input('empleado');
    
    // Obtener todos los empleados disponibles para el filtro
    $empleadosDisponibles = Empleado::orderBy('nombresemp')->get();
    
    // Consulta base: solo trabajos completados
    $trabajosQuery = Trabajo::where('estado', 'completado')
        ->whereNotNull('empleado');
    
    if ($empleadoId) {
        // Vista de detalle: trabajos de un empleado específico
        $trabajos = $trabajosQuery
            ->where('empleado', $empleadoId)
            ->with(['clienteRelacion', 'responsable', 'empleado'])
            ->orderBy('fechatrabajo', 'desc')
            ->get();
        
        $empleadoSeleccionado = Empleado::find($empleadoId);
        
        return view('trabajos.por_empleado', compact(
            'trabajos', 
            'empleadosDisponibles', 
            'empleadoSeleccionado'
        ));
    } else {
        // Vista resumen: trabajos agrupados por empleado
        $trabajosPorEmpleado = DB::table('trabajos')
            ->join('empleado', 'trabajos.empleado', '=', 'empleado.id')
            ->leftJoin('cargo', 'empleado.cargo_id', '=', 'cargo.id')
            ->select(
                'empleado.id',
                'empleado.nombresemp',
                'empleado.apellidosemp',
                'cargo.nombre as cargo_nombre',
                DB::raw('COUNT(trabajos.id) as cantidad'),
                DB::raw('SUM(trabajos.montototal) as monto_total'),
                DB::raw('SUM(trabajos.montopagado) as monto_pagado')
            )
            ->where('trabajos.estado', 'completado')
            ->whereNotNull('trabajos.empleado')
            ->groupBy(
                'empleado.id', 
                'empleado.nombresemp', 
                'empleado.apellidosemp',
                'cargo.nombre'
            )
            ->orderBy('cantidad', 'desc')
            ->get();
        
        // Obtener todos los trabajos completados para estadísticas generales
        $trabajos = $trabajosQuery->get();
        
        return view('trabajos.por_empleado', compact(
            'trabajosPorEmpleado',
            'trabajos',
            'empleadosDisponibles'
        ));
    }
}

}