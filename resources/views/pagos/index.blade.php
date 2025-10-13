{{-- resources/views/pagos/index.blade.php --}}

@php
    // Configuración de la vista
    $headerTitle = 'Gestión de Pagos';
    $headerSubtitle = 'Administra los pagos y abonos de tus clientes';
    $headerIcon = 'fas fa-money-bill-wave';
    
    $tableTitle = 'Lista de Pagos';
    $tableIcon = 'fas fa-list';
    
    $createRoute = route('pagos.create');
    $createButtonText = 'Nuevo Pago';
    $createIcon = 'fas fa-plus';
    
    $emptyTitle = 'No hay pagos registrados';
    $emptyMessage = 'Comienza registrando el primer pago de un cliente.';
    $emptyButtonText = 'Registrar Primer Pago';
    $emptyIcon = 'fas fa-money-bill-wave';
    
    $enableSearch = true;
    $enableAnimations = true;
    $enablePagination = true;
    $deleteButtonClass = '.btn-delete-pago';
    
    // Estadísticas
    $totalPagos = $pagos->total();
  
    $statsCards = [
        [
            'icon' => 'fas fa-file-invoice-dollar',
            'color' => 'primary',
            'value' => $totalPagos,
            'label' => $totalPagos != 1 ? 'pagos totales' : 'pago total'
        ]
    ];
    
    $records = $pagos;
@endphp

@extends('layouts.list-template')

@section('table-headers')
    <th width="6%">ID</th>
    <th width="18%">Cliente</th>
    <th width="15%">Trabajo</th>
    <th width="10%">Fecha</th>
    <th width="11%">Monto Total</th>
    <th width="11%">Pendiente</th>
    <th width="9%">Estado</th>
    <th width="12%">Progreso</th>
    <th width="8%">Acciones</th>
@endsection

@section('table-rows')
    @foreach($pagos as $pago)
    <tr>
        <td data-label="ID">
            <span class="record-id">#{{ $pago->id }}</span>
        </td>
        <td data-label="Cliente" class="record-name">
            <strong>{{ $pago->cliente->nombrescliente }} {{ $pago->cliente->apellidoscliente }}</strong>
        </td>
        <td data-label="Trabajo" class="record-info">
            {{ $pago->trabajo->nombretrb ?? 'Trabajo #' . $pago->id_trabajo }}
        </td>
        <td data-label="Fecha" class="record-info">
            {{ $pago->fecha_creacion->format('d/m/Y') }}
        </td>
        <td data-label="Monto Total" class="text-end">
            <strong class="text-primary">${{ number_format($pago->monto_total, 2) }}</strong>
        </td>
        <td data-label="Pendiente" class="text-end">
            <span class="badge bg-warning text-dark">
                ${{ number_format($pago->monto_pendiente, 2) }}
            </span>
        </td>
        <td data-label="Estado">
            @if($pago->estado == 'pagado')
                <span class="badge bg-success">
                    <i class="fas fa-check-circle"></i> Pagado
                </span>
            @elseif($pago->estado == 'parcial')
                <span class="badge bg-info">
                    <i class="fas fa-hourglass-half"></i> Parcial
                </span>
            @else
                <span class="badge bg-secondary">
                    <i class="fas fa-clock"></i> Pendiente
                </span>
            @endif
        </td>
        <td data-label="Progreso">
            <div class="progress" style="height: 22px; min-width: 100px;">
                <div class="progress-bar {{ $pago->porcentaje_pagado == 100 ? 'bg-success' : 'bg-info' }}" 
                     role="progressbar" 
                     style="width: {{ $pago->porcentaje_pagado }}%"
                     aria-valuenow="{{ $pago->porcentaje_pagado }}" 
                     aria-valuemin="0" 
                     aria-valuemax="100">
                    <small class="fw-bold">{{ number_format($pago->porcentaje_pagado, 0) }}%</small>
                </div>
            </div>
        </td>
        <td data-label="Acciones" class="action-buttons">
            <a href="{{ route('pagos.show', $pago) }}" 
               class="btn btn-sm btn-view"
               title="Ver detalles y abonos">
                <i class="fas fa-eye"></i>
            </a>
            
                   
            <a href="{{ route('pagos.edit', $pago) }}" 
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
                    data-name="Pago #{{ $pago->id }} - {{ $pago->cliente->nombrescliente }}"
                    title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>

    {{-- Modal para registrar abono --}}
    <div class="modal fade" id="modalAbono{{ $pago->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-dollar-sign"></i> Registrar Abono - Pago #{{ $pago->id }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('abonos.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="id_pago" value="{{ $pago->id }}">
                    
                    <div class="modal-body">
                        <div class="alert alert-info mb-3">
                            <div class="row text-center">
                                <div class="col-6">
                                    <strong>Monto Total</strong><br>
                                    <span class="fs-5">${{ number_format($pago->monto_total, 2) }}</span>
                                </div>
                                <div class="col-6">
                                    <strong>Monto Pendiente</strong><br>
                                    <span class="fs-5 text-danger">${{ number_format($pago->monto_pendiente, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="monto_abonado{{ $pago->id }}" class="form-label">
                                Monto a Abonar <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input type="number" 
                                       class="form-control @error('monto_abonado') is-invalid @enderror" 
                                       id="monto_abonado{{ $pago->id }}"
                                       name="monto_abonado" 
                                       step="0.01" 
                                       min="0.01"
                                       max="{{ $pago->monto_pendiente }}"
                                       value="{{ old('monto_abonado') }}"
                                       placeholder="0.00"
                                       required>
                            </div>
                            @error('monto_abonado')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Máximo permitido: ${{ number_format($pago->monto_pendiente, 2) }}
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_abono{{ $pago->id }}" class="form-label">
                                Fecha del Abono <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <input type="date" 
                                       class="form-control @error('fecha_abono') is-invalid @enderror" 
                                       id="fecha_abono{{ $pago->id }}"
                                       name="fecha_abono" 
                                       value="{{ old('fecha_abono', date('Y-m-d')) }}"
                                       max="{{ date('Y-m-d') }}"
                                       required>
                            </div>
                            @error('fecha_abono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Registrar Abono
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endsection

@push('scripts')
<script>
    // Validación de formulario de abonos
    document.querySelectorAll('.needs-validation').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Autocompletar monto pendiente al hacer clic en el campo
    document.querySelectorAll('[name="monto_abonado"]').forEach(input => {
        input.addEventListener('focus', function() {
            if (!this.value || this.value === '0.00') {
                const max = parseFloat(this.getAttribute('max'));
                if (max > 0) {
                    this.value = max.toFixed(2);
                    this.select();
                }
            }
        });
    });
</script>
@endpush