{{-- resources/views/responsables/index.blade.php --}}

@php
    // Configuración de la vista
    $headerTitle = 'Gestión de Responsables';
    $headerSubtitle = 'Administra y visualiza la información de tus responsables';
    $headerIcon = 'fas fa-users';
    
    $tableTitle = 'Lista de Responsables';
    $tableIcon = 'fas fa-list';
    
    $createRoute = route('responsables.create');
    $createButtonText = 'Nuevo Responsable';
    $createIcon = 'fas fa-plus';
    
    $emptyTitle = 'No hay responsables registrados';
    $emptyMessage = 'Comienza agregando tu primer responsable haciendo clic en el botón "Nuevo Responsable".';
    $emptyButtonText = 'Registrar Primer Responsable';
    $emptyIcon = 'fas fa-users';
    
    $enableSearch = true;
    $enableAnimations = true;
    $enablePagination = true; // ← Habilitar paginación
    $deleteButtonClass = '.btn-delete-responsa';
    
    // Estadísticas
    $statsCards = [
        [
            'icon' => 'fas fa-chart-line',
            'color' => 'primary', //primary 
            'value' => $responsables->total(), // ← Cambiar de count() a total()
            'label' => $responsables->total() != 1 ? 'responsables' : 'responsable'
        ]
    ];
    
    $records = $responsables;
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
    @foreach($responsables as $responsable)
    <tr>
        <td data-label="ID">
            <span class="record-id">#{{ $responsable->id }}</span>
        </td>
        <td data-label="Nombres" class="record-name">
            {{ $responsable->nombresresp }}
        </td>
        <td data-label="Apellidos" class="record-name">
            {{ $responsable->apellidosresp }}
        </td>
        <td data-label="Teléfono" class="record-info">
            @if($responsable->telefonoresp)
                <i class="fas fa-phone text-success mr-1"></i>
                {{ $responsable->telefonoresp }}
            @else
                <span class="text-muted">
                    <i class="fas fa-minus"></i> Sin teléfono
                </span>
            @endif
        </td>
        <td data-label="Correo" class="record-info">
            @if($responsable->correoresp)
                <i class="fas fa-envelope text-primary mr-1"></i>
                <span class="text-truncate-mobile">{{ $responsable->correoresp }}</span>
            @else
                <span class="text-muted">
                    <i class="fas fa-minus"></i> Sin correo
                </span>
            @endif
        </td>



        <td data-label="Acciones" class="action-buttons">
            <a href="{{ route('responsables.show', $responsable->id) }}" 
                class="btn btn-sm btn-view"
                title="Ver detalles">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('responsables.edit', $responsable->id) }}" 
                class="btn btn-sm btn-edit"
                title="Editar">
                <i class="fas fa-edit"></i>
            </a>

            {{-- Formulario oculto para eliminar --}}
            <form id="delete-form-{{ $responsable->id }}" 
                    action="{{ route('responsables.destroy', $responsable) }}" 
                    method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>

            <button type="button" 
                    class="btn btn-sm btn-delete btn-delete-responsa" 
                    data-id="{{ $responsable->id }}"
                    data-name="{{ $responsable->nombresresp }} {{ $responsable->apellidosresp }}"
                    title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
    @endforeach
@endsection