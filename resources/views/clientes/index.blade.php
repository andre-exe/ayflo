{{-- resources/views/clientes/index.blade.php --}}

@php
    // Configuración de la vista
    $headerTitle = 'Gestión de Clientes';
    $headerSubtitle = 'Administra y visualiza la información de tus clientes';
    $headerIcon = 'fas fa-users';
    
    $tableTitle = 'Lista de Clientes';
    $tableIcon = 'fas fa-list';
    
    $createRoute = route('clientes.create');
    $createButtonText = 'Nuevo Cliente';
    $createIcon = 'fas fa-plus';
    
    $emptyTitle = 'No hay clientes registrados';
    $emptyMessage = 'Comienza agregando tu primer cliente haciendo clic en el botón "Nuevo Cliente".';
    $emptyButtonText = 'Registrar Primer Cliente';
    $emptyIcon = 'fas fa-users';
    
    $enableSearch = true;
    $enableAnimations = true;
    $enablePagination = true; // ← Habilitar paginación
    $deleteButtonClass = '.btn-delete-client';
    
    // Estadísticas
    $statsCards = [
        [
            'icon' => 'fas fa-chart-line',
            'color' => 'primary',
            'value' => $clientes->total(), // ← Cambiar de count() a total()
            'label' => $clientes->total() != 1 ? 'clientes' : 'cliente'
        ]
    ];
    
    $records = $clientes;
@endphp

@extends('layouts.list-template')

@section('table-headers')
    <th width="8%">ID</th>
    <th width="20%">Nombres</th>
    <th width="20%">Apellidos</th>
    <th width="18%">Teléfono</th>
    <th width="20%">Correo</th>
    <th width="14%">Acciones</th>
@endsection

@section('table-rows')
    @foreach($clientes as $cliente)
    <tr>
        <td data-label="ID">
            <span class="record-id">#{{ $cliente->id }}</span>
        </td>
        <td data-label="Nombres" class="record-name">
            {{ $cliente->nombrescliente }}
        </td>
        <td data-label="Apellidos" class="record-name">
            {{ $cliente->apellidoscliente }}
        </td>
        <td data-label="Teléfono" class="record-info">
            @if($cliente->telefonocliente)
                <i class="fas fa-phone text-success mr-1"></i>
                {{ $cliente->telefonocliente }}
            @else
                <span class="text-muted">
                    <i class="fas fa-minus"></i> Sin teléfono
                </span>
            @endif
        </td>
        <td data-label="Correo" class="record-info">
            @if($cliente->correocliente)
                <i class="fas fa-envelope text-primary mr-1"></i>
                <span class="text-truncate-mobile">{{ $cliente->correocliente }}</span>
            @else
                <span class="text-muted">
                    <i class="fas fa-minus"></i> Sin correo
                </span>
            @endif
        </td>
        <td data-label="Acciones" class="action-buttons">
            <a href="{{ route('clientes.show', $cliente->id) }}" 
                class="btn btn-sm btn-view"
                title="Ver detalles">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('clientes.edit', $cliente->id) }}" 
                class="btn btn-sm btn-edit"
                title="Editar">
                <i class="fas fa-edit"></i>
            </a>

            {{-- Formulario oculto para eliminar --}}
            <form id="delete-form-{{ $cliente->id }}" 
                    action="{{ route('clientes.destroy', $cliente) }}" 
                    method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>

            <button type="button" 
                    class="btn btn-sm btn-delete btn-delete-client" 
                    data-id="{{ $cliente->id }}"
                    data-name="{{ $cliente->nombrescliente }} {{ $cliente->apellidoscliente }}"
                    title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
    @endforeach
@endsection