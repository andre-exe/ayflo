<?php

namespace App\Http\Controllers;

use App\Models\Trabajo;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Responsable;
use App\Models\Pago;
use App\Models\Abono;
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
            'montopagado' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value > $request->montototal) {
                        $fail('El monto pagado no puede ser mayor al monto total.');
                    }
                },
            ],
            'nombretrb' => 'required|string|max:100',
            'archivoescritura.*' => 'nullable|file|max:5120',
            'archivoesquema.*' => 'nullable|file|max:5120',
            'puntosrecorrido.*' => 'nullable|file|max:5120',
            'archivodwg.*' => 'nullable|file|max:5120',
            'archivokml.*' => 'nullable|file|max:5120',
            'notas.*' => 'nullable|file|max:5120',
            'insumos.*' => 'nullable|file|max:5120',
        ]);

        DB::beginTransaction();
        
        try {
            // Preparar datos del trabajo
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

            // Manejar archivos múltiples
            $camposArchivos = [
                'archivoescritura',
                'archivoesquema',
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
                } else {
                    $data[$campo] = null;
                }
            }

            // Crear el trabajo
            $trabajo = Trabajo::create($data);

            // ==========================================
            // NUEVA FUNCIONALIDAD: Crear pago automático
            // ==========================================
            if ($trabajo->montototal > 0) {
                // Crear el registro de pago
                $pago = Pago::create([
                    'id_cliente' => $trabajo->cliente,
                    'id_responsable' => $trabajo->responsable,
                    'id_trabajo' => $trabajo->id,
                    'monto_total' => $trabajo->montototal,
                    'monto_pendiente' => $trabajo->montototal,
                    'fecha_creacion' => $trabajo->fechatrabajo,
                ]);

                // Si hay monto pagado inicial, crear el primer abono
                if ($trabajo->montopagado > 0) {
                    Abono::create([
                        'id_pago' => $pago->id,
                        'monto_abonado' => $trabajo->montopagado,
                        'fecha_abono' => $trabajo->fechatrabajo,
                        'numero_abono' => 1
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('trabajos.index')
                ->with('success', 'Trabajo registrado exitosamente' . 
                    ($trabajo->montototal > 0 ? ' y pago creado automáticamente' : ''));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar el trabajo: ' . $e->getMessage());
        }
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
            'montopagado' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value > $request->montototal) {
                        $fail('El monto pagado no puede ser mayor al monto total.');
                    }
                },
            ],
            'nombretrb' => 'required|string|max:100',
            'archivoescritura.*' => 'nullable|file|max:5120',
            'archivoesquema.*' => 'nullable|file|max:5120',
            'puntosrecorrido.*' => 'nullable|file|max:5120',
            'archivodwg.*' => 'nullable|file|max:5120',
            'archivokml.*' => 'nullable|file|max:5120',
            'notas.*' => 'nullable|file|max:5120',
            'insumos.*' => 'nullable|file|max:5120',
        ]);

        DB::beginTransaction();
        
        try {
            // Guardar valores anteriores
            $montoTotalAnterior = $trabajo->montototal;
            $montoPagadoAnterior = $trabajo->montopagado;

            // Preparar datos del trabajo
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

            // Manejar archivos múltiples
            $camposArchivos = [
                'archivoescritura',
                'archivoesquema',
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
            }

            // Actualizar el trabajo
            $trabajo->update($data);

            // ==========================================
            // NUEVA FUNCIONALIDAD: Actualizar pago
            // ==========================================
            
            // Buscar si ya existe un pago para este trabajo
            $pago = Pago::where('id_trabajo', $trabajo->id)->first();

            // Si cambió el monto total
            if ($trabajo->montototal != $montoTotalAnterior) {
                if ($pago) {
                    // Verificar que el nuevo monto total no sea menor a lo ya abonado
                    $totalAbonado = $pago->monto_total - $pago->monto_pendiente;
                    
                    if ($trabajo->montototal < $totalAbonado) {
                        throw new \Exception(
                            "El nuevo monto total ($" . number_format($trabajo->montototal, 2) . 
                            ") no puede ser menor al monto ya abonado ($" . number_format($totalAbonado, 2) . ")"
                        );
                    }
                    
                    // Actualizar el pago existente
                    $pago->update([
                        'monto_total' => $trabajo->montototal,
                        'id_cliente' => $trabajo->cliente,
                        'id_responsable' => $trabajo->responsable,
                    ]);
                } else if ($trabajo->montototal > 0) {
                    // Crear nuevo pago si no existía
                    $pago = Pago::create([
                        'id_cliente' => $trabajo->cliente,
                        'id_responsable' => $trabajo->responsable,
                        'id_trabajo' => $trabajo->id,
                        'monto_total' => $trabajo->montototal,
                        'monto_pendiente' => $trabajo->montototal,
                        'fecha_creacion' => $trabajo->fechatrabajo,
                    ]);
                }
            }

            // Si cambió el monto pagado inicial
            if ($pago && $trabajo->montopagado != $montoPagadoAnterior) {
                // Buscar el abono inicial (número 1)
                $abonoInicial = Abono::where('id_pago', $pago->id)
                                     ->where('numero_abono', 1)
                                     ->first();

                if ($trabajo->montopagado > 0) {
                    if ($abonoInicial) {
                        // Actualizar el abono existente
                        $abonoInicial->update([
                            'monto_abonado' => $trabajo->montopagado,
                            'fecha_abono' => $trabajo->fechatrabajo
                        ]);
                    } else {
                        // Crear el abono inicial si no existía
                        Abono::create([
                            'id_pago' => $pago->id,
                            'monto_abonado' => $trabajo->montopagado,
                            'fecha_abono' => $trabajo->fechatrabajo,
                            'numero_abono' => 1
                        ]);
                    }
                } else {
                    // Si el monto pagado es 0, eliminar el abono inicial si existe
                    if ($abonoInicial) {
                        $abonoInicial->delete();
                    }
                }
            }

            DB::commit();

            return redirect()->route('trabajos.index')
                ->with('success', 'Trabajo actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el trabajo: ' . $e->getMessage());
        }
    }

    public function descargarArchivo($trabajo_id, $tipo_archivo)
    {
        try {
            $trabajo = Trabajo::find($trabajo_id);
            
            if (!$trabajo) {
                return response()->json(['error' => 'Trabajo no encontrado'], 404);
            }

            if (!$trabajo->$tipo_archivo) {
                return response()->json(['error' => 'No hay archivos de este tipo'], 404);
            }

            $archivos = json_decode($trabajo->$tipo_archivo, true);
            
            if (!$archivos || !is_array($archivos) || empty($archivos)) {
                return response()->json(['error' => 'No se encontraron archivos válidos'], 404);
            }

            $archivo_encontrado = $archivos[0];

            if (!Storage::disk('public')->exists($archivo_encontrado)) {
                return response()->json(['error' => 'El archivo no existe en el servidor'], 404);
            }

            $rutaArchivo = Storage::disk('public')->path($archivo_encontrado);
            $nombreArchivo = basename($archivo_encontrado);
            
            return response()->download($rutaArchivo, $nombreArchivo);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }

    public function testDescarga(Request $request)
    {
        dd([
            'trabajo' => $request->trabajo,
            'tipo_archivo' => $request->tipo_archivo,
            'todos_parametros' => $request->all()
        ]);
    }

    public function destroy(Trabajo $trabajo)
    {
        DB::beginTransaction();
        
        try {
            // Eliminar el pago asociado (los abonos se eliminarán en cascada)
            $pago = Pago::where('id_trabajo', $trabajo->id)->first();
            if ($pago) {
                $pago->delete();
            }
            
            // Eliminar el trabajo
            $trabajo->delete();
            
            DB::commit();
            
            return redirect()->route('trabajos.index')
                ->with('success', 'Trabajo eliminado exitosamente');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al eliminar el trabajo: ' . $e->getMessage());
        }
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

    public function porAnio(Request $request)
    {
        $anoSeleccionado = $request->input('ano');
        
        $trabajos = Trabajo::with(['clienteRelacion', 'responsable', 'empleado'])->get();
        
        $anosDisponibles = Trabajo::selectRaw('EXTRACT(YEAR FROM fechatrabajo) as ano')
            ->distinct()
            ->orderBy('ano', 'desc')
            ->pluck('ano');
        
        if ($anoSeleccionado) {
            $trabajos = Trabajo::with(['clienteRelacion', 'responsable', 'empleado'])
                ->whereRaw('EXTRACT(YEAR FROM fechatrabajo) = ?', [$anoSeleccionado])
                ->orderBy('fechatrabajo', 'desc')
                ->get();
            
            $trabajosPorAno = collect();
            
        } else {
            $trabajosPorAno = Trabajo::selectRaw('
                    EXTRACT(YEAR FROM fechatrabajo) as ano,
                    COUNT(*) as cantidad,
                    SUM(montototal) as monto_total
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

    public function porEmpleado(Request $request)
    {
        $empleadoId = $request->input('empleado');
        
        $empleadosDisponibles = Empleado::orderBy('nombresemp')->get();
        
        $trabajosQuery = Trabajo::where('estado', 'completado')
            ->whereNotNull('empleado');
        
        if ($empleadoId) {
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
            $trabajosPorEmpleado = DB::table('trabajos')
                ->join('empleado', 'trabajos.empleado', '=', 'empleado.id')
                ->leftJoin('cargo', 'empleado.cargo_id', '=', 'cargo.id')
                ->select(
                    'empleado.id',
                    'empleado.nombresemp',
                    'empleado.apellidosemp',
                    'cargo.nombre as cargo_nombre',
                    DB::raw('COUNT(trabajos.id) as cantidad'),
                    DB::raw('SUM(trabajos.montototal) as monto_total')
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
            
            $trabajos = $trabajosQuery->get();
            
            return view('trabajos.por_empleado', compact(
                'trabajosPorEmpleado',
                'trabajos',
                'empleadosDisponibles'
            ));
        }
    }
}