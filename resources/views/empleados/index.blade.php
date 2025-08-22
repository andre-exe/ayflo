{{-- resources/views/empleados/index.blade.php --}}

@php
    // Configuración de la vista
    $headerTitle = 'Gestión de Empleados';
    $headerSubtitle = 'Administra y visualiza la información de tus empleados';
    $headerIcon = 'fas fa-users';
    
    $tableTitle = 'Lista de Empleados';
    $tableIcon = 'fas fa-list';
    
    $createRoute = route('empleados.create');
    $createButtonText = 'Nuevo Empleado';
    $createIcon = 'fas fa-plus';
    
    $emptyTitle = 'No hay empleados registrados';
    $emptyMessage = 'Comienza agregando tu primer empleado haciendo clic en el botón "Nuevo Empleado".';
    $emptyButtonText = 'Registrar Primer Empleado';
    $emptyIcon = 'fas fa-users';
    
    $enableSearch = true;
    $enableAnimations = true;
    $enablePagination = true; // ← Habilitar paginación
    $deleteButtonClass = '.btn-delete-emp';
    
    // Estadísticas
    $statsCards = [
        [
            'icon' => 'fas fa-chart-line',
            'color' => 'primary',
            'value' => $empleados->total(),
            'label' => $empleados->total() != 1 ? 'empleados' : 'empleado'
        ]
    ];
    
    $records = $empleados;
@endphp

@extends('layouts.list-template')

@section('table-headers')
    <th width="8%">ID</th>
    <th width="15%">Nombres</th>
    <th width="15%">Apellidos</th>
    <th width="15%">Teléfono</th>
    <th width="20%">Direccion</th>
    <th width="10%">Cargo</th>
    <th width="15%">Acciones</th>
@endsection

@section('table-rows')
    @foreach($empleados as $empleado)
    <tr>
        <td data-label="ID">
            <span class="record-id">{{ $empleado->id }}</span> <!-- Ahora muestra ID -->
        </td>
        <td data-label="Nombres" class="record-name">
            {{ $empleado->nombresemp }}
        </td>
        <td data-label="Apellidos" class="record-name">
            {{ $empleado->apellidosemp }}
        </td>
        <td data-label="Teléfono" class="record-info">
            @if($empleado->telefonemp)
                <i class="fas fa-phone text-success mr-1"></i>
                {{ $empleado->telefonemp }}
            @else
                <span class="text-muted">
                    <i class="fas fa-minus"></i> Sin teléfono
                </span>
            @endif
        </td>
        <td data-label="Direccion" class="record-info">
            @if($empleado->direccionemp)
                <i class="fas fa-envelope text-primary mr-1"></i>
                <span class="text-truncate-mobile">{{ $empleado->direccionemp }}</span>
            @else
                <span class="text-muted">
                    <i class="fas fa-minus"></i> Sin direccion
                </span>
            @endif
        </td>
        <td data-label="Cargo">
    {{ $empleado->cargoRelacion->nombre ?? 'Sin cargo' }}
</td>
        <td data-label="Acciones" class="action-buttons">
            
            <a href="{{ route('empleados.edit', $empleado->id) }}" 
                class="btn btn-sm btn-edit"
                title="Editar">
                <i class="fas fa-edit"></i>
            </a>

            {{-- Formulario oculto para eliminar --}}
            <form id="delete-form-{{ $empleado->id }}" 
                    action="{{ route('empleados.destroy', $empleado->id) }}" 
                    method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>

            <button type="button" 
                    class="btn btn-sm btn-delete btn-delete-emp" 
                    data-id="{{ $empleado->id }}"
                    data-name="{{ $empleado->nombresemp }} {{ $empleado->apellidosemp }}"
                    title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
    @endforeach
@endsection

@section('pagination')
    @if($enablePagination)
        {{ $empleados->links() }} <!-- Enlaces de paginación -->
    @endif
@endsection
