@extends('adminlte::page')

@section('title', 'Registrar Egreso')

@section('css')
    {{-- CSS global para formularios --}}
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <h1>Registrar Egreso</h1>

    <div class="card form-card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>¡Oops!</strong> Hay algunos errores:
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('egresos.store') }}" method="POST" id="egresoForm">
                @csrf
                
                <div class="form-group">
                    <label for="montoegreso" class="form-label">
                        <i class="fas fa-user text-primary"></i> Monto:
                    </label>
                    <input type="number" 
                    step="0.01"
                           name="montoegreso" 
                           id="montoegreso"
                           class="form-control @error('montoegreso') is-invalid @enderror" 
                           placeholder="Ingrese las cantidades"
                           value="{{ old('montoegreso') }}"
                           required>
                    @error('montoegreso')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descripcionegreso" class="form-label">
                        <i class="fas fa-user-tag text-info"></i> Descripción:
                    </label>
                    <input type="text" 
                           name="descripcionegreso" 
                           id="descripcionegreso"
                           class="form-control @error('descripcionegreso') is-invalid @enderror" 
                           placeholder="Ingrese la descripcion del gasto"
                           value="{{ old('descripcionegreso') }}"
                           required>
                    @error('descripcionegreso')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="fecha" class="form-label">
                        <i class="fas fa-envelope text-success"></i> Fecha:
                    </label>
                    <input type="date" 
                           name="fecha" 
                           id="fecha"
                           class="form-control @error('fecha') is-invalid @enderror" 
                           
                           value="{{ old('fecha', date('Y-m-d')) }}" required>
                    @error('fecha')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-success form-btn">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <a href="{{ route('egresos.index') }}" class="btn btn-secondary form-btn">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{--@section('js')
    <script>
        $(document).ready(function() {
            // Configurar validación en tiempo real
            configurarValidacionTiempoReal({
                nombrescliente: {
                    requerido: true,
                    minLength: 2,
                    nombre: 'Nombres'
                },
                apellidoscliente: {
                    requerido: true,
                    minLength: 2,
                    nombre: 'Apellidos'
                },
                correocliente: {
                    requerido: false,
                    tipo: 'email',
                    nombre: 'Correo'
                }
            });
        });
    </script>
@endsection--}}
