{{-- resources/views/pagos/edit.blade.php --}}

@extends('adminlte::page')

@section('title', 'Editar Pagos')

@section('css')
    {{-- CSS global para formularios --}}
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
@endsection

@section('content')
<div class="container-fluid py-4">
  
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-edit fa-2x me-3"></i>
                        <div>
                            <h4 class="mb-0">Editar Pago #{{ $pago->id }}</h4>
                            <small>Modifica la información del pago</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>¡Error!</strong> Por favor corrige los siguientes errores:
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Alerta si el pago tiene abonos --}}
                    @if($pago->abonos->count() > 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Importante:</strong> Este pago tiene {{ $pago->abonos->count() }} 
                            {{ $pago->abonos->count() == 1 ? 'abono registrado' : 'abonos registrados' }}.
                            Ten cuidado al modificar el monto total.
                        </div>
                    @endif

                    <form action="{{ route('pagos.update', $pago) }}" method="POST" id="formEditarPago" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Sección: Información del Cliente --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-user text-warning"></i> Información del Cliente
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="id_cliente" class="form-label fw-bold">
                                        Cliente <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('id_cliente') is-invalid @enderror" 
                                            id="id_cliente" 
                                            name="id_cliente" 
                                            required>
                                        <option value="">-- Seleccione un cliente --</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id }}" 
                                                    {{ old('id_cliente', $pago->id_cliente) == $cliente->id ? 'selected' : '' }}>
                                                {{ $cliente->nombrescliente }} {{ $cliente->apellidoscliente }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_cliente')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="id_responsable" class="form-label fw-bold">
                                        Responsable <small class="text-muted">(Opcional)</small>
                                    </label>
                                    <select class="form-select @error('id_responsable') is-invalid @enderror" 
                                            id="id_responsable" 
                                            name="id_responsable">
                                        <option value="">-- Sin responsable asignado --</option>
                                        @foreach($responsables as $responsable)
                                            <option value="{{ $responsable->id }}" 
                                                    data-cliente="{{ $responsable->cliente }}"
                                                    {{ old('id_responsable', $pago->id_responsable) == $responsable->id ? 'selected' : '' }}>
                                                {{ $responsable->nombresresp }} {{ $responsable->apellidosresp }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_responsable')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Sección: Información del Pago --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-dollar-sign text-warning"></i> Información del Pago
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="monto_total" class="form-label fw-bold">
                                        Monto Total del Pago <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-dollar-sign"></i>
                                        </span>
                                        <input type="number" 
                                               class="form-control @error('monto_total') is-invalid @enderror" 
                                               id="monto_total" 
                                               name="monto_total" 
                                               step="0.01" 
                                               min="0.01"
                                               value="{{ old('monto_total', $pago->monto_total) }}" 
                                               placeholder="0.00"
                                               required>
                                    </div>
                                    @error('monto_total')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @if($pago->abonos->count() > 0)
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            Ya se han abonado: ${{ number_format($pago->monto_total - $pago->monto_pendiente, 2) }}
                                        </small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="fecha_creacion" class="form-label fw-bold">
                                        Fecha de Creación <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                        <input type="date" 
                                               class="form-control @error('fecha_creacion') is-invalid @enderror" 
                                               id="fecha_creacion" 
                                               name="fecha_creacion" 
                                               value="{{ old('fecha_creacion', $pago->fecha_creacion->format('Y-m-d')) }}" 
                                               max="{{ date('Y-m-d') }}"
                                               required>
                                    </div>
                                    @error('fecha_creacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Información actual del pago --}}
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Estado Actual del Pago</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Estado:</strong><br>
                                    @if($pago->estado == 'pagado')
                                        <span class="badge bg-success">Pagado</span>
                                    @elseif($pago->estado == 'parcial')
                                        <span class="badge bg-info">Parcial</span>
                                    @else
                                        <span class="badge bg-secondary">Pendiente</span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <strong>Monto Pendiente:</strong><br>
                                    ${{ number_format($pago->monto_pendiente, 2) }}
                                </div>
                                <div class="col-md-3">
                                    <strong>Monto Pagado:</strong><br>
                                    ${{ number_format($pago->monto_total - $pago->monto_pendiente, 2) }}
                                </div>
                                <div class="col-md-3">
                                    <strong>Abonos:</strong><br>
                                    {{ $pago->abonos->count() }}
                                </div>
                            </div>
                        </div>
                        
                        {{-- Botones de acción --}}
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('pagos.show', $pago) }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-save me-2"></i> Actualizar Pago
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditarPago');
    const montoOriginal = {{ $pago->monto_total }};
    const montoAbonado = {{ $pago->monto_total - $pago->monto_pendiente }};
    const tieneAbonos = {{ $pago->abonos->count() > 0 ? 'true' : 'false' }};

    // Validación del formulario
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            form.classList.add('was-validated');
            return;
        }

        const nuevoMonto = parseFloat(document.getElementById('monto_total').value);

        // Validar que el nuevo monto no sea menor a lo ya abonado
        if (tieneAbonos && nuevoMonto < montoAbonado) {
            e.preventDefault();
            alert(`El monto total no puede ser menor al monto ya abonado (${montoAbonado.toFixed(2)})`);
            return;
        }

        // Advertencia si se cambia el monto y hay abonos
        if (tieneAbonos && nuevoMonto !== montoOriginal) {
            const confirmar = confirm(
                `ATENCIÓN: Está cambiando el monto total de ${montoOriginal.toFixed(2)} a ${nuevoMonto.toFixed(2)}.\n\n` +
                `Este pago tiene abonos registrados. El monto pendiente se recalculará automáticamente.\n\n` +
                `¿Desea continuar?`
            );
            
            if (!confirmar) {
                e.preventDefault();
                return;
            }
        }
        
        form.classList.add('was-validated');
    });

    // Formatear monto al perder el foco
    document.getElementById('monto_total').addEventListener('blur', function() {
        if (this.value) {
            const valor = parseFloat(this.value);
            if (!isNaN(valor)) {
                this.value = valor.toFixed(2);
            }
        }
    });
});
</script>
@endpush

                      