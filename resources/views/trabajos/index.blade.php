{{-- resources/views/trabajos/index.blade.php --}}

@php
    // Configuración de la vista
    $headerTitle = 'Gestión de Trabajos';
    $headerSubtitle = 'Administra y visualiza la información de tus trabajos con archivos';
    $headerIcon = 'fas fa-briefcase';
    
    $tableTitle = 'Lista de Trabajos';
    $tableIcon = 'fas fa-list';
    
    $createRoute = route('trabajos.create');
    $createButtonText = 'Nuevo Trabajo';
    $createIcon = 'fas fa-plus';
    
    $emptyTitle = 'No hay trabajos registrados';
    $emptyMessage = 'Comienza agregando tu primer trabajo haciendo clic en el botón "Nuevo Trabajo".';
    $emptyButtonText = 'Registrar Primer Trabajo';
    $emptyIcon = 'fas fa-briefcase';
    
    $enableSearch = true;
    $enableAnimations = true;
    $enablePagination = true;
    $deleteButtonClass = '.btn-delete-trabajo';
    
    // Estadísticas
    $statsCards = [
        [
            'icon' => 'fas fa-briefcase',
            'color' => 'primary',
            'value' => $trabajos->total(),
            'label' => $trabajos->total() != 1 ? 'trabajos' : 'trabajo'
        ],
        [
            'icon' => 'fas fa-clock',
            'color' => 'warning',
            'value' => $trabajos->where('estado', 'pendiente')->count(),
            'label' => 'pendientes'
        ],
        [
            'icon' => 'fas fa-check-circle',
            'color' => 'success',
            'value' => $trabajos->where('estado', 'completado')->count(),
            'label' => 'completados'
        ],
        [
            'icon' => 'fas fa-file-alt',
            'color' => 'info',
            'value' => $trabajos->filter(function($trabajo) {
                return collect(['archivoescritura', 'archivoesquema', 'puntosrecorrido', 'archivodwg', 'archivokml', 'notas', 'insumos'])
                    ->some(function($campo) use ($trabajo) {
                        return !empty($trabajo->$campo);
                    });
            })->count(),
            'label' => 'con archivos'
        ]
    ];
    
    $records = $trabajos;
@endphp

@extends('layouts.list-template')

@section('table-headers')
    
    <th width="16%">Cliente</th>
    <th width="14%">Nombre de trabajo</th>
    <th width="12%">Fecha</th>
    <th width="10%">Estado</th>
    <th width="12%">Monto Total</th>
    <th width="10%">Archivos</th>
    <th width="20%">Acciones</th>
@endsection

@section('table-rows')
    @foreach($trabajos as $trabajo)
    <td data-label="Cliente" class="record-name">
    @if($trabajo->clienteRelacion && $trabajo->clienteRelacion->nombrescliente && $trabajo->clienteRelacion->apellidoscliente)
        {{ $trabajo->clienteRelacion->nombrescliente }} {{ $trabajo->clienteRelacion->apellidoscliente }}
    @elseif($trabajo->clienteRelacion)
        Cliente #{{ $trabajo->clienteRelacion->id }}
    @else
        <span class="text-muted">
            <i class="fas fa-minus"></i> Sin Cliente
        </span>
    @endif
</td>

<td data-label="Nombre" class="record-info">
            @if($trabajo->nombretrb)
                
                {{ $trabajo->nombretrb }}
            @else
                <span class="text-muted">
                    <i class="fas fa-minus"></i> Sin Descripcion
                </span>
            @endif
        </td>

        
        <td data-label="Fecha" class="record-info">
            <i class="fas fa-calendar text-secondary mr-1"></i>
            {{ $trabajo->fechatrabajo ? $trabajo->fechatrabajo->format('d/m/Y') : 'Sin fecha' }}
        </td>
        <td data-label="Estado" class="record-info">
            @php
                $estadoClasses = [
                    'pendiente' => 'badge-warning',
                    'en_proceso' => 'badge-info',
                    'completado' => 'badge-success',
                    'cancelado' => 'badge-danger',
                ];
                $estadoClass = $estadoClasses[$trabajo->estado] ?? 'badge-secondary';
            @endphp
            <span class="badge {{ $estadoClass }}">
                {{ ucfirst(str_replace('_', ' ', $trabajo->estado)) }}
            </span>
        </td>
        <td data-label="Monto Total" class="record-info">
           
            <strong>${{ number_format($trabajo->montototal, 2) }}</strong>
            @if($trabajo->montopagado > 0)
                <br><small class="text-muted">Pagado: ${{ number_format($trabajo->montopagado, 2) }}</small>
            @endif
        </td>
        <td data-label="Archivos" class="record-info">
            @php
    $archivosCount = 0;
    
    $camposArchivos = ['archivoescritura', 'archivoesquema', 'puntosrecorrido', 'archivodwg', 'archivokml', 'notas', 'insumos'];
    foreach($camposArchivos as $campo) {
        if(!empty($trabajo->$campo)) $archivosCount++;
    }
@endphp
            
            @if($archivosCount > 0)
                <span class="badge badge-info">
                    <i class="fas fa-paperclip mr-1"></i>
                    {{ $archivosCount }} {{ $archivosCount == 1 ? 'archivo' : 'archivos' }}
                </span>
            @else
                <span class="text-muted">
                    <i class="fas fa-minus"></i> Sin archivos
                </span>
            @endif
        </td>
        <td data-label="Acciones" class="action-buttons">
           
            
            @if($archivosCount > 0)
                <div class="btn-group" role="group">
                    <button type="button" 
                            class="btn btn-sm btn-info dropdown-toggle" 
                            data-toggle="dropdown" 
                            aria-haspopup="true" 
                            aria-expanded="false"
                            title="Descargar archivos">
                        <i class="fas fa-download"></i>
                    </button>
                    <div class="dropdown-menu">
                        @foreach($camposArchivos as $campo)
                            @if(!empty($trabajo->$campo))
                                <a class="dropdown-item" 
   href="{{ route('trabajos.descargar-archivo', [$trabajo->id, $campo]) }}"
   target="_blank">
    <i class="fas fa-file mr-2"></i>
    {{ ucfirst(str_replace(['archivo', '_'], ['', ' '], $campo)) }}
</a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
            
            <a href="{{ route('trabajos.edit', $trabajo->id) }}" 
                class="btn btn-sm btn-edit"
                title="Editar">
                <i class="fas fa-edit"></i>
            </a>

            {{-- Formulario oculto para eliminar --}}
            <form id="delete-form-{{ $trabajo->id }}" 
                    action="{{ route('trabajos.destroy', $trabajo) }}" 
                    method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>

            <button type="button" 
                    class="btn btn-sm btn-delete btn-delete-trabajo" 
                    data-id="{{ $trabajo->id }}"
                    data-name="Trabajo #{{ $trabajo->id }} - {{ $trabajo->cliente->nombrescliente ?? 'Sin cliente' }}"
                    title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
    @endforeach
@endsection

@push('styles')
<style>
.badge {
    font-size: 0.75em;
}

.dropdown-menu {
    min-width: 200px;
}

.dropdown-item {
    font-size: 0.875rem;
}

.btn-group {
    display: inline-block;
}

@media (max-width: 768px) {
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    .btn-group {
        flex: 1;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Manejar eliminación de trabajos
    $(document).on('click', '.btn-delete-trabajo', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: `Esta acción eliminará permanentemente "${name}" y todos sus archivos asociados.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar loading
                Swal.fire({
                    title: 'Eliminando...',
                    text: 'Por favor espera mientras se eliminan los archivos.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Enviar formulario
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    });
    
    // Mejorar tooltips para archivos
    $('[title]').tooltip({
        placement: 'top',
        trigger: 'hover'
    });
});
</script>
@endpush