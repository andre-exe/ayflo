{{-- resources/views/pagos/show.blade.php --}}

@extends('adminlte::page')

@section('title', 'Mostrar Pagos')

@section('css')
    {{-- CSS global para formularios --}}
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
@endsection

@section('content')
<div class="container-fluid py-4">
    {{-- Botón volver --}}
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Pagos
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        {{-- Columna izquierda: Información del pago --}}
        <div class="col-lg-8 mb-4">
            {{-- Card: Información del Pago --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-file-invoice-dollar"></i> Detalles del Pago #{{ $pago->id }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small d-block mb-1">
                                <i class="fas fa-user"></i> Cliente
                            </label>
                            <div class="fw-bold fs-6">
                                {{ $pago->cliente->nombrescliente }} {{ $pago->cliente->apellidoscliente }}
                            </div>
                            @if($pago->cliente->telefonocliente)
                                <small class="text-muted">
                                    <i class="fas fa-phone"></i> {{ $pago->cliente->telefonocliente }}
                                </small>
                            @endif
                            @if($pago->cliente->correocliente)
                                <br><small class="text-muted">
                                    <i class="fas fa-envelope"></i> {{ $pago->cliente->correocliente }}
                                </small>
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small d-block mb-1">
                                <i class="fas fa-user-tie"></i> Responsable
                            </label>
                            <div class="fw-bold fs-6">
                                @if($pago->responsable)
                                    {{ $pago->responsable->nombresresp }} {{ $pago->responsable->apellidosresp }}
                                    @if($pago->responsable->telefonoresp)
                                        <br><small class="text-muted">
                                            <i class="fas fa-phone"></i> {{ $pago->responsable->telefonoresp }}
                                        </small>
                                    @endif
                                @else
                                    <span class="text-muted">Sin responsable asignado</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small d-block mb-1">
                                <i class="fas fa-briefcase"></i> Trabajo
                            </label>
                            <div class="fw-bold fs-6">
                                {{ $pago->trabajo->nombretrb ?? 'Trabajo #' . $pago->id_trabajo }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small d-block mb-1">
                                <i class="fas fa-calendar"></i> Fecha de Creación
                            </label>
                            <div class="fw-bold fs-6">
                                {{ $pago->fecha_creacion->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card: Historial de Abonos --}}
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-history"></i> Historial de Abonos
                    </h5>
                    @if($pago->estado != 'pagado')
                        <button type="button" 
                                class="btn btn-light btn-sm" 
                                data-toggle="modal" 
                                data-target="#modalNuevoAbono">
                            <i class="fas fa-plus"></i> Nuevo Abono
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if($pago->abonos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="10%">#</th>
                                        <th width="25%">Fecha</th>
                                        <th width="30%">Monto Abonado</th>
                                        <th width="25%">Fecha Registro</th>
                                        <th width="10%">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pago->abonos as $abono)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">{{ $abono->numero_abono }}</span>
                                        </td>
                                        <td>
                                            <i class="fas fa-calendar text-muted"></i>
                                            {{ $abono->fecha_abono->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            <span class="text-success fw-bold fs-6">
                                                ${{ number_format($abono->monto_abonado, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $abono->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <form id="delete-abono-{{ $abono->id }}" 
                                                  action="{{ route('abonos.destroy', $abono) }}" 
                                                  method="POST" 
                                                  style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger btn-delete-abono" 
                                                    data-id="{{ $abono->id }}"
                                                    data-monto="{{ number_format($abono->monto_abonado, 2) }}"
                                                    title="Eliminar abono">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="2" class="text-end fw-bold">Total Abonado:</td>
                                        <td colspan="3">
                                            <span class="text-success fw-bold fs-5">
                                                ${{ number_format($pago->monto_total - $pago->monto_pendiente, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay abonos registrados</h5>
                            <p class="text-muted">Este pago aún no tiene abonos asociados</p>
                            @if($pago->estado != 'pagado')
                                <button type="button" 
                                        class="btn btn-success" 
                                        data-toggle="modal" 
                                        data-target="#modalNuevoAbono">
                                    <i class="fas fa-plus"></i> Registrar Primer Abono
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Columna derecha: Resumen del pago --}}
        <div class="col-lg-4">
            {{-- Card: Resumen Financiero --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie"></i> Resumen Financiero
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="text-muted small d-block mb-2">Monto Total</label>
                        <div class="fs-4 fw-bold text-primary">
                            ${{ number_format($pago->monto_total, 2) }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small d-block mb-2">Monto Pagado</label>
                        <div class="fs-4 fw-bold text-success">
                            ${{ number_format($pago->monto_total - $pago->monto_pendiente, 2) }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small d-block mb-2">Monto Pendiente</label>
                        <div class="fs-4 fw-bold text-danger">
                            ${{ number_format($pago->monto_pendiente, 2) }}
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="text-muted small d-block mb-2">Progreso de Pago</label>
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar {{ $pago->porcentaje_pagado == 100 ? 'bg-success' : 'bg-info' }}" 
                                 role="progressbar" 
                                 style="width: {{ $pago->porcentaje_pagado }}%">
                                <strong>{{ number_format($pago->porcentaje_pagado, 1) }}%</strong>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small d-block mb-2">Estado del Pago</label>
                        <div>
                            @if($pago->estado == 'pagado')
                                <span class="badge bg-success fs-6 py-2 px-3">
                                    <i class="fas fa-check-circle"></i> PAGADO
                                </span>
                            @elseif($pago->estado == 'parcial')
                                <span class="badge bg-info fs-6 py-2 px-3">
                                    <i class="fas fa-hourglass-half"></i> PARCIAL
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6 py-2 px-3">
                                    <i class="fas fa-clock"></i> PENDIENTE
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="text-muted small d-block mb-2">Abonos Realizados</label>
                        <div class="fs-5 fw-bold">
                            {{ $pago->abonos->count() }} 
                            {{ $pago->abonos->count() != 1 ? 'abonos' : 'abono' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card: Acciones rápidas --}}
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-cog"></i> Acciones
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('pagos.edit', $pago) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar Pago
                        </a>
                        
                        @if($pago->estado != 'pagado')
                            <button type="button" 
                                    class="btn btn-success" 
                                    data-toggle="modal" 
                                    data-target="#modalNuevoAbono">
                                <i class="fas fa-dollar-sign"></i> Registrar Abono
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal para nuevo abono --}}
<div class="modal fade" id="modalNuevoAbono" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-dollar-sign"></i> Registrar Nuevo Abono
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('abonos.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <input type="hidden" name="id_pago" value="{{ $pago->id }}">
                
                <div class="modal-body">
                    <div class="alert alert-info mb-4">
                        <div class="row text-center">
                            <div class="col-6">
                                <strong>Monto Total</strong><br>
                                <span class="fs-4">${{ number_format($pago->monto_total, 2) }}</span>
                            </div>
                            <div class="col-6">
                                <strong>Monto Pendiente</strong><br>
                                <span class="fs-4 text-danger">${{ number_format($pago->monto_pendiente, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="monto_abonado" class="form-label fw-bold">
                            Monto a Abonar <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                            <input type="number" 
                                   class="form-control @error('monto_abonado') is-invalid @enderror" 
                                   id="monto_abonado"
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
                            <i class="fas fa-info-circle"></i> Máximo: ${{ number_format($pago->monto_pendiente, 2) }}
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_abono" class="form-label fw-bold">
                            Fecha del Abono <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-calendar"></i>
                            </span>
                            <input type="date" 
                                   class="form-control @error('fecha_abono') is-invalid @enderror" 
                                   id="fecha_abono"
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación del formulario de abonos
    const formAbono = document.querySelector('.needs-validation');
    formAbono.addEventListener('submit', function(e) {
        if (!formAbono.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        formAbono.classList.add('was-validated');
    });

    // Autocompletar con monto pendiente
    document.getElementById('monto_abonado').addEventListener('focus', function() {
        if (!this.value || this.value === '0.00') {
            this.value = {{ $pago->monto_pendiente }};
            this.select();
        }
    });

    // Confirmación para eliminar abonos
    document.querySelectorAll('.btn-delete-abono').forEach(button => {
        button.addEventListener('click', function() {
            const abonoId = this.dataset.id;
            const monto = this.dataset.monto;
            
            Swal.fire({
                title: '¿Eliminar abono?',
                html: `¿Está seguro de eliminar este abono de <strong>$${monto}</strong>?<br><small class="text-muted">Esta acción actualizará el monto pendiente del pago.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-abono-' + abonoId).submit();
                }
            });
        });
    });
});

</script>
@endpush
