{{-- resources/views/pagos/index.blade.php --}}

@php
    // Configuración de la vista
    $headerTitle = 'Control de Pagos de los Trabajos';
    $headerSubtitle = 'Visualiza el historial de pagos realizados y registrados';
    $headerIcon = 'fas fa-book';

    $tableTitle = 'Lista de Pagos';
    $tableIcon = 'fas fa-list';

    $createRoute =route('pagos.create'); 
    $createButtonText = ' Nuevo Pago ';
    $createIcon = ' fas fa-plus '; 

    $emptyTitle = 'No hay registros de pagos';
    $emptyMessage = 'Aquí aparecerán los pagos registrados automáticamente.';
    $emptyButtonText = '';
    $emptyIcon = 'fas fa-book';

    $enableSearch = true;
    $enableAnimations = true;
    $enablePagination = true; // paginación activada
    $deleteButtonClass = '.btn-delete-pago'; 

    // Estadísticas
    $statsCards = [
        [
            'icon' => 'fas fa-chart-line',
            'color' => 'primary',
            'value' => $pagos->total(),
            'label' => $pagos->total() != 1 ? 'pagos' : 'pagos'
        ]
    ];

    $records = $pagos;
@endphp

@extends('layouts.list-template')

@section('table-headers')
    <th width="6%">ID</th>
    <th width="25%">Cliente</th>
    <th width="25%">Responsable</th>
    <th width="12%">Monto Total</th>
    <th width="12%">Abono</th>
    <th width="12%">Fecha</th>
    <th width="10%">Acciones</th>
@endsection

@section('table-rows')
     @foreach ($pagos as $pago)
     <tr>

<td data-label="ID">
            <span class="record-id">#{{ $pago->id }}</span>
        </td>

        {{-- Nombre cliente desde la relación cliente --}}
        <td data-label="NombreCliente" class="record-name">  @if($pago->cliente)
        @php $cliente = $pago->cliente()->first(); @endphp
        {{ $cliente->nombrescliente }} {{ $cliente->apellidoscliente }}
    @else
        N/A
    @endif
        </td>

{{-- Nombre responsable desde la relación responsable --}}
        <td data-label="NombreResponsable" class="record-name">
            @if($pago->responsable)
        @php $responsable = $pago->responsable()->first(); @endphp
        {{ $responsable->nombresresp }} {{ $responsable->apellidosresp }}
    @else
        <div class="d-flex align-items-center">
                    <i class="fas fa-minus-circle text-muted mr-2"></i>
                    <span class="text-muted font-italic">Sin responsable</span>
                </div>
    @endif
        </td>

        {{-- Datos sacados de pagos--}}
        <td data-label="Precio" class="record-name">
            $ {{ number_format($pago->montototal, 2) }}</td>

        <td data-label="abono" class="record-name">
            ${{ number_format($pago->abono,2)}}</td>

        <td data-label="Fecha" class="record-name">
            {{ \Carbon\Carbon::parse($pago->fechaabono)->format('d/m/Y') }}
        </td>

       
         
         {{-- Botones de pagos --}}
        <td data-label="Acciones" class="action-buttons">
            <a href="{{ route('pagos.edit', $pago->id) }}" 
                class="btn btn-sm btn-edit"
                title="Editar">
                <i class="fas fa-edit"></i>
            </a>

            {{-- Formulario oculto para eliminar --}}
            <form id="delete-form-{{ $pago->id }}" 
                    action="{{ route('pagos.destroy', $pago) }}" 
                    method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>

            <button type="button" 
                    class="btn btn-sm btn-delete btn-delete-pago" 
                    data-id="{{ $pago->id }}"
                    data-name="{{ $pago->abono }} "
                    title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </td>
     </tr>
     @endforeach
@endsection
