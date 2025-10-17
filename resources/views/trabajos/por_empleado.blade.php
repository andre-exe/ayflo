{{-- resources/views/trabajos/por_empleado.blade.php --}}

@php
    // Configuración de la vista
    $headerTitle = 'Trabajos por Empleado';
    $headerSubtitle = request('empleado') 
        ? 'Detalle de trabajos del empleado ' . ($empleadoSeleccionado->nombresemp ?? '') . ' ' . ($empleadoSeleccionado->apellidosemp ?? '')
        : 'Resumen de trabajos completados agrupados por empleado';
    $headerIcon = 'fas fa-users'; 
    
    $tableTitle = request('empleado') 
        ? 'Trabajos de ' . ($empleadoSeleccionado->nombresemp ?? '') . ' ' . ($empleadoSeleccionado->apellidosemp ?? '')
        : 'Resumen por Empleado';
    $tableIcon = 'fas fa-chart-bar';
    
    $emptyTitle = 'No hay trabajos completados';
    $emptyMessage = request('empleado') 
        ? 'No hay trabajos registrados para este empleado'
        : 'Actualmente no hay trabajos completados con empleados asignados.';
    $emptyButtonText = 'Registrar Trabajo';
    $emptyIcon = 'fas fa-briefcase';
    
    $enableSearch = true;
    $enableAnimations = true;
    $enablePagination = false;
    
    // Estadísticas generales
    if (request('empleado')) {
        // Estadísticas para vista de detalle por empleado
        $statsCards = [
            [
                'icon' => 'fas fa-user',
                'color' => 'primary',
                'value' => ($empleadoSeleccionado->nombresemp ?? '') . ' ' . ($empleadoSeleccionado->apellidosemp ?? ''),
                'label' => 'Empleado Seleccionado'
            ],
            [
                'icon' => 'fas fa-briefcase',
                'color' => 'success',
                'value' => $trabajos->count(),
                'label' => 'Trabajos Realizados'
            ],
            [
                'icon' => 'fas fa-dollar-sign',
                'color' => 'info',
                'value' => '$' . number_format($trabajos->sum('montototal'), 2),
                'label' => 'Ingresos Generados'
            ]
        ];
        $records = $trabajos;
    } else {
        // Estadísticas para vista resumen
        $statsCards = [
            [
                'icon' => 'fas fa-check-circle',
                'color' => 'success',
                'value' => $trabajos->count(),
                'label' => 'Trabajos Completados'
            ],
            [
                'icon' => 'fas fa-dollar-sign',
                'color' => 'info',
                'value' => '$' . number_format($trabajos->sum('montototal'), 2),
                'label' => 'Ingresos Totales'
            ],
            [
                'icon' => 'fas fa-users',
                'color' => 'warning',
                'value' => $trabajosPorEmpleado->count(),
                'label' => 'Empleados Activos'
            ]
        ];
        $records = $trabajosPorEmpleado;
    }
@endphp

@extends('layouts.list-template')

@section('content-before-table')
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-filter mr-2"></i>Filtrar por Empleado</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('trabajos.por_empleado') }}" class="form-inline">
                    <div class="form-group mr-3">
                        <label for="empleado" class="mr-2">Seleccionar Empleado:</label>
                        <select name="empleado" id="empleado" class="form-control" style="min-width: 250px;">
                            <option value="">Todos los empleados (Resumen)</option>
                            @foreach($empleadosDisponibles as $emp)
                                <option value="{{ $emp->id }}" {{ request('empleado') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->nombresemp }} {{ $emp->apellidosemp }}
                                    @if($emp->cargoRelacion)
                                        - {{ $emp->cargoRelacion->nombre }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search mr-1"></i>Filtrar
                    </button>
                    @if(request('empleado'))
                        <a href="{{ route('trabajos.por_empleado') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times mr-1"></i>Ver Resumen
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Información del empleado seleccionado --}}
@if(request('empleado') && isset($empleadoSeleccionado))
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-primary">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-2">
                            <i class="fas fa-user-circle text-primary mr-2"></i>
                            <strong>{{ $empleadoSeleccionado->nombresemp }} {{ $empleadoSeleccionado->apellidosemp }}</strong>
                        </h5>
                        <div class="text-muted">
                            @if($empleadoSeleccionado->cargoRelacion)
                                <span class="badge badge-info mr-2">
                                    <i class="fas fa-id-badge"></i> {{ $empleadoSeleccionado->cargoRelacion->nombre }}
                                </span>
                            @endif
                            @if($empleadoSeleccionado->telefonemp)
                                <i class="fas fa-phone mr-1"></i>{{ $empleadoSeleccionado->telefonemp }}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{ route('empleados.show', $empleadoSeleccionado->id) }}" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user mr-1"></i>Ver Perfil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('table-headers')
    @if(request('empleado'))
        {{-- Headers para vista de detalle (trabajos individuales) --}}
        <th width="8%">Código</th>
        <th width="20%">Cliente</th>
        <th width="30%">Descripción</th>
        <th width="15%">Fecha</th>
        <th width="12%">Estado</th>
        <th width="15%">Monto Total</th>
    @else
        {{-- Headers para vista resumen (agrupado por empleado) --}}
        <th width="30%">Empleado</th>
        <th width="20%">Cargo</th>
        <th width="15%">Trabajos</th>
        <th width="20%">Ingresos Generados</th>
        <th width="15%">Acciones</th>
    @endif
@endsection

@section('table-rows')
    @if(request('empleado'))
        {{-- Vista de detalle: mostrar trabajos individuales del empleado --}}
        @foreach($trabajos as $trabajo)
        <tr>
            <td data-label="Código" class="record-name">
                <strong>#{{ $trabajo->id }}</strong>
            </td>
            
            <td data-label="Cliente" class="record-info">
                <i class="fas fa-user text-primary mr-1"></i>
                @if($trabajo->clienteRelacion)
                    {{ $trabajo->clienteRelacion->nombrescliente }} {{ $trabajo->clienteRelacion->apellidoscliente }}
                @else
                    <span class="text-muted">Sin cliente</span>
                @endif
            </td>
            
            <td data-label="Descripción" class="record-info">
                {{ Str::limit($trabajo->nombretrb, 50) }}
            </td>
            
            <td data-label="Fecha" class="record-info">
                <i class="fas fa-calendar text-muted mr-1"></i>
                {{ \Carbon\Carbon::parse($trabajo->fechatrabajo)->format('d/m/Y') }}
            </td>
            
            <td data-label="Estado" class="record-info">
                <span class="badge badge-success">
                    <i class="fas fa-check-circle"></i> {{ ucfirst($trabajo->estado) }}
                </span>
            </td>
            
            <td data-label="Monto Total" class="record-info">
                <strong class="text-success">${{ number_format($trabajo->montototal, 2) }}</strong>
            </td>
        </tr>
        @endforeach
    @else
        {{-- Vista resumen: mostrar totales por empleado --}}
        @foreach($trabajosPorEmpleado as $grupo)
        <tr>
            <td data-label="Empleado" class="record-name">
                <i class="fas fa-user-tie text-primary mr-2"></i>
                <strong style="font-size: 1.05rem;">
                    {{ $grupo->nombresemp }} {{ $grupo->apellidosemp }}
                </strong>
            </td>
            
            <td data-label="Cargo" class="record-info">
                @if($grupo->cargo_nombre)
                    <span class="badge badge-secondary badge-md">
                        <i class="fas fa-id-badge mr-1"></i>
                        {{ $grupo->cargo_nombre }}
                    </span>
                @else
                    <span class="text-muted">Sin cargo</span>
                @endif
            </td>
            
            <td data-label="Trabajos" class="record-info">
                <span class="badge badge-primary badge-lg">
                    <i class="fas fa-briefcase mr-1"></i>
                    {{ $grupo->cantidad }} {{ $grupo->cantidad == 1 ? 'trabajo' : 'trabajos' }}
                </span>
            </td>
            
            <td data-label="Ingresos" class="record-info">
                <strong class="text-success" style="font-size: 1.1rem;">
                    ${{ number_format($grupo->monto_total, 2) }}
                </strong>
            </td>
            
            <td data-label="Acciones" class="action-buttons">
                <a href="{{ route('trabajos.por_empleado', ['empleado' => $grupo->id]) }}" 
                    class="btn btn-sm btn-primary"
                    title="Ver trabajos de {{ $grupo->nombresemp }}">
                    <i class="fas fa-search-plus"></i> Ver Detalle
                </a>
            </td>
        </tr>
        @endforeach
    @endif
@endsection

@push('styles')
<style>
.badge-lg {
    font-size: 1rem;
    padding: 0.5rem 1rem;
}

.badge-md {
    font-size: 0.9rem;
    padding: 0.4rem 0.8rem;
}

.card-header {
    font-weight: 600;
}

.form-inline .form-group {
    margin-bottom: 0;
}

@media (max-width: 768px) {
    .form-inline {
        flex-direction: column;
        align-items: stretch;
    }
    
    .form-inline .form-group,
    .form-inline .btn {
        width: 100%;
        margin-bottom: 0.5rem;
        margin-right: 0 !important;
    }
    
    .form-inline .form-group .form-control {
        width: 100%;
    }
}

.text-success {
    color: #28a745 !important;
}

.text-info {
    color: #17a2b8 !important;
}

.text-warning {
    color: #ffc107 !important;
}

.record-name strong {
    font-size: 1rem;
}

.table td {
    vertical-align: middle;
}

.border-primary {
    border: 2px solid #007bff !important;
}

/* Animación para las filas */
tbody tr {
    transition: all 0.3s ease;
}

tbody tr:hover {
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Tooltips
    $('[title]').tooltip({
        placement: 'top',
        trigger: 'hover'
    });
    
    // Highlight de la fila al pasar el mouse
    $('tbody tr').hover(
        function() {
            $(this).css('background-color', '#f8f9fa');
        },
        function() {
            $(this).css('background-color', '');
        }
    );
    
    // Mejorar el select con estilo
    $('#empleado').select2({
        placeholder: 'Seleccione un empleado',
        allowClear: true,
        width: '100%'
    });
});
</script>
@endpush