{{-- resources/views/egresos/index.blade.php --}}

@php
    // Configuración de la vista
    $headerTitle = 'Gestión de Egresos';
    $headerSubtitle = 'Administra y visualiza la información de tus Egresos';
    $headerIcon = 'fas fa-users';
    
    $tableTitle = 'Lista de Egresos';
    $tableIcon = 'fas fa-list';
    
    $createRoute = route('egresos.create');
    $createButtonText = 'Nuevo Egreso';
    $createIcon = 'fas fa-plus';
    
    $emptyTitle = 'No hay egresos registrados';
    $emptyMessage = 'Comienza agregando tu primer Egreso haciendo clic en el botón "Nuevo Egreso".';
    $emptyButtonText = 'Registrar el Primer Egreso';
    $emptyIcon = 'fas fa-users';
    
    $enableSearch = true;
    $enableAnimations = true;
    $enablePagination = true; // ← Habilitar paginación
    $deleteButtonClass = '.btn-delete-egres';
    
    // Estadísticas
    $statsCards = [
        [
            'icon' => 'fas fa-chart-line',
            'color' => 'primary',
            'value' => $egresos->total(), // ← Cambiar de count() a total()
            'label' => $egresos->total() != 1 ? 'egresos' : 'egresos'// la utima palabra va en singular osea egreso
        ]
    ];
    
    $records = $egresos;
@endphp

@extends('layouts.list-template')

@section('table-headers')
    <th width="8%">ID</th>
    <th width="20%">Monto de egreso</th>
    <th width="20%">descripcion de egreso</th>
    <th width="18%">Fecha</th>
@endsection


@section('table-rows')
    @foreach($egresos as $egreso)
    <tr>
        <td data-label="ID">
            <span class="record-id">#{{ $egreso->id }}</span>
        </td>
        <td data-label="Monto del egreso" class="record-name">
            {{ $egreso->montoegreso }}
        </td>
        <td data-label="Descripción" class="record-name">
            {{ $egreso->descripcionegreso }}
        </td>
        <td data-label="Fecha" class="record-name">
            {{ \Carbon\Carbon::parse($egreso->fecha)->format('d/m/Y') }}
        </td>
        <td data-label="Acciones" class="action-buttons">
            <a href="{{ route('egresos.show', $egreso->id) }}" 
                class="btn btn-sm btn-view"
                title="Ver detalles">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('egresos.edit', $egreso->id) }}" 
                class="btn btn-sm btn-edit"
                title="Editar">
                <i class="fas fa-edit"></i>
            </a>

            {{-- Formulario oculto para eliminar --}}
            <form id="delete-form-{{ $egreso->id }}" 
                    action="{{ route('egresos.destroy', $egreso) }}" 
                    method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>

            <button type="button" 
                    class="btn btn-sm btn-delete btn-delete-egres" 
                    data-id="{{ $egreso->id }}"
                    data-name="{{ $egreso->descripcionegreso }}"
                    title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
    @endforeach
@endsection

