{{-- resources/views/bitacora/index.blade.php --}}

@php
    // Configuración de la vista
    $headerTitle = 'Bitacora de Trabajos';
    $headerSubtitle = 'Visualiza el historial de trabajos realizados y registrados';
    $headerIcon = 'fas fa-book';

    $tableTitle = 'Lista de Bitacoras';
    $tableIcon = 'fas fa-list';

    $createRoute =null; //route('bitacoras.create'); crear o null No hay creación manual en bitácora, así que null
    $createButtonText = ' ';// 'Nueva Bitacora';quitar estos datos y dejar bacias las comillas ya que esto es de forma automatica
    $createIcon = ' '; // 'fas fa-plus';

    $emptyTitle = 'No hay registros en la bitácora';
    $emptyMessage = 'Aquí aparecerán los trabajos registrados automáticamente.';
    $emptyButtonText = '';
    $emptyIcon = 'fas fa-book';

    $enableSearch = true;
    $enableAnimations = true;
    $enablePagination = true; // paginación activada
    $deleteButtonClass = '.btn-delete-bitacor'; // no implementaremos borrar por ahora

    // Estadísticas
    $statsCards = [
        [
            'icon' => 'fas fa-clipboard-list',
            'color' => 'primary',
            'value' => $bitacoras->total(),
            'label' => $bitacoras->total() != 1 ? 'registros' : 'registro'
        ]
    ];

    $records = $bitacoras;
@endphp

@extends('layouts.list-template')

@section('table-headers')
    <th width="6%">ID</th>
    <th width="20%">Cliente</th>
    <th width="20%">Responsable</th>
    <th width="10%">Trabajo</th>
    <th width="12%">Monto</th>
    <th width="12%">Fecha</th>
    <th width="20%">Descripción</th>
    <th width="20%">Acciones</th>
@endsection

@section('table-rows')
     @foreach ($bitacoras as $bitacora)
     <tr>

<td data-label="ID">
            <span class="record-id">#{{ $bitacora->id }}</span>
        </td>

        {{-- Nombre cliente desde la relación cliente --}}
        <td data-label="NombreCliente" class="record-name">  @if($bitacora->cliente)
        @php $cliente = $bitacora->cliente()->first(); @endphp
        {{ $cliente->nombrescliente }} {{ $cliente->apellidoscliente }}
    @else
        N/A
    @endif
        </td>

{{-- Nombre responsable desde la relación responsable --}}
        <td data-label="NombreResponsable" class="record-name">
            @if($bitacora->responsable)
        @php $responsable = $bitacora->responsable()->first(); @endphp
        {{ $responsable->nombresresp }} {{ $responsable->apellidosresp }}
    @else
        N/A
    @endif
        </td>

{{-- Datos sacados de la relación trabajo --}}
        <td data-label="IdTrabajo" class="record-name">
            {{ $bitacora->trabajo ? $bitacora->trabajo->id : 'N/A' }}
        </td>
   
        {{-- Datos sacados de bitacora--}}
        <td data-label="Precio" class="record-name">
            $ {{ number_format($bitacora->monto, 2) }}</td>
        <td data-label="Fecha" class="record-name">
            {{ \Carbon\Carbon::parse($bitacora->fechatrabajobitacora)->format('d/m/Y') }}
        </td>

        {{-- Descripción de bitácora --}}
        <td data-label="Descripcion">{{ $bitacora->descripcionbitacora ?? 'Sin descripción' }}</td>
         
         {{-- Botones de bitácora --}}
        <td data-label="Acciones" class="action-buttons">
            <a href="{{ route('bitacoras.show', $bitacora->id) }}" 
                class="btn btn-sm btn-view"
                title="Ver detalles">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('bitacoras.edit', $bitacora->id) }}" 
                class="btn btn-sm btn-edit"
                title="Editar">
                <i class="fas fa-edit"></i>
            </a>

            {{-- Formulario oculto para eliminar --}}
            <form id="delete-form-{{ $bitacora->id }}" 
                    action="{{ route('bitacoras.destroy', $bitacora) }}" 
                    method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>

            <button type="button" 
                    class="btn btn-sm btn-delete btn-delete-bitacor" 
                    data-id="{{ $bitacora->id }}"
                    data-name="{{ $bitacora->descripcionbitacora }} "
                    title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </td>
     </tr>
     @endforeach
@endsection
