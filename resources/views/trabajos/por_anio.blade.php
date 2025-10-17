{{-- resources/views/trabajos/por_anio.blade.php --}}

@php
    // Configuración de la vista
    $headerTitle = 'Trabajos por Año';
    $headerSubtitle = request('ano') 
        ? 'Detalle de trabajos del año ' . request('ano')
        : 'Resumen de trabajos completados agrupados por año';
    $headerIcon = 'fas fa-calendar-alt'; 
    
    $tableTitle = request('ano') 
        ? 'Trabajos del Año ' . request('ano')
        : 'Resumen por Año';
    $tableIcon = 'fas fa-chart-bar';
    
    $emptyTitle = 'No hay trabajos completados';
    $emptyMessage = request('ano') 
        ? 'No hay trabajos registrados para el año ' . request('ano')
        : 'Actualmente no hay trabajos completados registrados.';
    $emptyButtonText = 'Registrar Trabajo';
    $emptyIcon = 'fas fa-briefcase';
    
    $enableSearch = true;
    $enableAnimations = true;
    $enablePagination = false;
    
    // Estadísticas generales
    if (request('ano')) {
        // Estadísticas para vista de detalle por año
        $statsCards = [
            [
                'icon' => 'fas fa-calendar-check',
                'color' => 'primary',
                'value' => request('ano'),
                'label' => 'Año Seleccionado'
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
                'label' => 'Ingresos del Año'
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
                'icon' => 'fas fa-calendar-check',
                'color' => 'warning',
                'value' => $trabajosPorAno->count(),
                'label' => 'Años Registrados'
            ]
        ];
        $records = $trabajosPorAno;
    }
@endphp

@extends('layouts.list-template')

@section('content-before-table')
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-filter mr-2"></i>Filtrar por Año</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('trabajos.por_anio') }}" class="form-inline">
                    <div class="form-group mr-3">
                        <label for="ano" class="mr-2">Seleccionar Año:</label>
                        <select name="ano" id="ano" class="form-control">
                            <option value="">Todos los años (Resumen)</option>
                            @foreach($anosDisponibles as $ano)
                                <option value="{{ $ano }}" {{ request('ano') == $ano ? 'selected' : '' }}>
                                    {{ $ano }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search mr-1"></i>Filtrar
                    </button>
                    @if(request('ano'))
                        <a href="{{ route('trabajos.por_anio') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times mr-1"></i>Ver Resumen
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('table-headers')
    @if(request('ano'))
        {{-- Headers para vista de detalle (trabajos individuales) --}}
        <th width="10%">Código</th>
        <th width="22%">Cliente</th>
        <th width="32%">Descripción</th>
        <th width="15%">Fecha</th>
        <th width="13%">Monto Total</th>
        <th width="8%">Acciones</th>
    @else
        {{-- Headers para vista resumen (agrupado por año) --}}
        <th width="25%">Año</th>
        <th width="25%">Cantidad de Trabajos</th>
        <th width="30%">Ingresos Totales</th>
        <th width="20%">Acciones</th>
    @endif
@endsection

@section('table-rows')
    @if(request('ano'))
        {{-- Vista de detalle: mostrar trabajos individuales del año --}}
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
            
            <td data-label="Monto Total" class="record-info">
                <strong class="text-success">${{ number_format($trabajo->montototal, 2) }}</strong>
            </td>
            
            <td data-label="Acciones" class="action-buttons">
                <a href="{{ route('trabajos.show', $trabajo->id) }}" 
                    class="btn btn-sm btn-info"
                    title="Ver detalles">
                    <i class="fas fa-eye"></i>
                </a>
            </td>
        </tr>
        @endforeach
    @else
        {{-- Vista resumen: mostrar totales por año --}}
        @foreach($trabajosPorAno as $grupo)
        <tr>
            <td data-label="Año" class="record-name">
                <i class="fas fa-calendar text-primary mr-2"></i>
                <strong style="font-size: 1.1rem;">{{ $grupo->ano }}</strong>
            </td>
            
            <td data-label="Cantidad" class="record-info">
                <span class="badge badge-primary badge-lg">
                    <i class="fas fa-briefcase mr-1"></i>
                    {{ $grupo->cantidad }} {{ $grupo->cantidad == 1 ? 'trabajo' : 'trabajos' }}
                </span>
            </td>
            
            <td data-label="Ingresos Totales" class="record-info">
                <strong class="text-success" style="font-size: 1.1rem;">
                    ${{ number_format($grupo->monto_total, 2) }}
                </strong>
            </td>
            
            <td data-label="Acciones" class="action-buttons">
                <a href="{{ route('trabajos.por_anio', ['ano' => $grupo->ano]) }}" 
                    class="btn btn-sm btn-primary"
                    title="Ver trabajos del año {{ $grupo->ano }}">
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
});
</script>
@endpush