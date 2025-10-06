{{-- resources/views/pagos/create.blade.php --}}
@extends('adminlte::page')

@section('title', 'Registrar Pagos')

@section('css')
    {{-- CSS global para formularios --}}
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
@endsection

@section('content')
<div class="container-fluid">
<h1>Registrar Pago</h1>


    <div class="row justify-content-center">
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

                    <form action="{{ route('pagos.store') }}" method="POST" id="formCrearPago" class="needs-validation" novalidate>
                        @csrf

                        {{-- Sección: Información del Cliente --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-user text-primary"></i> Información del Cliente
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
                                                    data-telefono="{{ $cliente->telefonocliente }}"
                                                    data-correo="{{ $cliente->correocliente }}"
                                                    {{ old('id_cliente') == $cliente->id ? 'selected' : '' }}>
                                                {{ $cliente->nombrescliente }} {{ $cliente->apellidoscliente }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_cliente')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> Seleccione el cliente que realizará el pago
                                    </div>
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
                                                    {{ old('id_responsable') == $responsable->id ? 'selected' : '' }}>
                                                {{ $responsable->nombresresp }} {{ $responsable->apellidosresp }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_responsable')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> Persona responsable del pago (si aplica)
                                    </div>
                                </div>
                            </div>

                            {{-- Info del cliente seleccionado --}}
                            <div id="clienteInfo" class="alert alert-info d-none">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-phone"></i> Teléfono:</strong>
                                        <span id="clienteTelefono">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-envelope"></i> Correo:</strong>
                                        <span id="clienteCorreo">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sección: Detalles del Trabajo --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-briefcase text-primary"></i> Detalles del Trabajo
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="id_trabajo" class="form-label fw-bold">
                                        Trabajo Asociado <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('id_trabajo') is-invalid @enderror" 
                                            id="id_trabajo" 
                                            name="id_trabajo" 
                                            required>
                                        <option value="">-- Seleccione un trabajo --</option>
                                        @foreach($trabajos as $trabajo)
                                            <option value="{{ $trabajo->id }}" 
                                                    data-cliente="{{ $trabajo->cliente }}"
                                                    data-monto="{{ $trabajo->montototal }}"
                                                    {{ old('id_trabajo') == $trabajo->id ? 'selected' : '' }}>
                                                {{ $trabajo->nombretrb ?? 'Trabajo #' . $trabajo->id }} 
                                                @if($trabajo->fechatrabajo)
                                                    - {{ $trabajo->fechatrabajo->format('d/m/Y') }}
                                                @endif
                                                @if($trabajo->montototal)
                                                    - ${{ number_format($trabajo->montototal, 2) }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_trabajo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> Seleccione el trabajo al que corresponde este pago
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sección: Información del Pago --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-dollar-sign text-primary"></i> Información del Pago
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
                                               value="{{ old('monto_total') }}" 
                                               placeholder="0.00"
                                               required>
                                    </div>
                                    @error('monto_total')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> Ingrese el monto total que debe pagarse
                                    </div>
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
                                               value="{{ old('fecha_creacion', date('Y-m-d')) }}" 
                                               max="{{ date('Y-m-d') }}"
                                               required>
                                    </div>
                                    @error('fecha_creacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> Fecha en que se registra el pago
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Botones de acción --}}
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i> Crear Pago
                            </button>
                        </div>
                    </form>
                </div>
            
       
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formCrearPago');
    const selectCliente = document.getElementById('id_cliente');
    const selectResponsable = document.getElementById('id_responsable');
    const selectTrabajo = document.getElementById('id_trabajo');
    const inputMontoTotal = document.getElementById('monto_total');
    const clienteInfo = document.getElementById('clienteInfo');
    const clienteTelefono = document.getElementById('clienteTelefono');
    const clienteCorreo = document.getElementById('clienteCorreo');

    // Mostrar información del cliente seleccionado
    selectCliente.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            const telefono = selectedOption.dataset.telefono || 'No registrado';
            const correo = selectedOption.dataset.correo || 'No registrado';
            
            clienteTelefono.textContent = telefono;
            clienteCorreo.textContent = correo;
            clienteInfo.classList.remove('d-none');
            
            // Filtrar responsables del cliente seleccionado
            filtrarResponsables(this.value);
            
            // Filtrar trabajos del cliente seleccionado
            filtrarTrabajos(this.value);
        } else {
            clienteInfo.classList.add('d-none');
            selectResponsable.value = '';
            selectTrabajo.value = '';
        }
    });

    // Función para filtrar responsables por cliente
    function filtrarResponsables(clienteId) {
        const options = selectResponsable.querySelectorAll('option');
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }
            
            const optionClienteId = option.dataset.cliente;
            option.style.display = (optionClienteId == clienteId) ? 'block' : 'none';
        });
        
        // Resetear selección si el responsable no pertenece al cliente
        const selectedOption = selectResponsable.options[selectResponsable.selectedIndex];
        if (selectedOption && selectedOption.dataset.cliente != clienteId) {
            selectResponsable.value = '';
        }
    }

    // Función para filtrar trabajos por cliente
    function filtrarTrabajos(clienteId) {
        const options = selectTrabajo.querySelectorAll('option');
        let trabajosFiltrados = 0;
        
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }
            
            const optionClienteId = option.dataset.cliente;
            if (optionClienteId == clienteId) {
                option.style.display = 'block';
                trabajosFiltrados++;
            } else {
                option.style.display = 'none';
            }
        });
        
        // Resetear selección si el trabajo no pertenece al cliente
        const selectedOption = selectTrabajo.options[selectTrabajo.selectedIndex];
        if (selectedOption && selectedOption.dataset.cliente != clienteId) {
            selectTrabajo.value = '';
        }
        
        // Mostrar mensaje si no hay trabajos
        if (trabajosFiltrados === 0) {
            selectTrabajo.innerHTML = '<option value="">No hay trabajos registrados para este cliente</option>';
        }
    }

    // Sugerir monto del trabajo seleccionado
    selectTrabajo.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (this.value && selectedOption.dataset.monto) {
            const monto = parseFloat(selectedOption.dataset.monto);
            if (monto > 0 && !inputMontoTotal.value) {
                inputMontoTotal.value = monto.toFixed(2);
            }
        }
    });

    // Validación del formulario
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        const monto = parseFloat(inputMontoTotal.value);
        if (monto <= 0) {
            e.preventDefault();
            alert('El monto total debe ser mayor a 0');
            inputMontoTotal.focus();
            return;
        }
        
        form.classList.add('was-validated');
    });

    // Formatear monto al perder el foco
    inputMontoTotal.addEventListener('blur', function() {
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