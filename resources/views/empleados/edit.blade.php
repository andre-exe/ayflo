@extends('adminlte::page')

@section('title', 'Editar Empleado')

@section('css')
{{-- CSS global para formularios --}}
<link rel="stylesheet" href="{{ asset('css/forms.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <h1>Editar Empleado</h1>

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

            <form action="{{ route('empleados.update', $empleado->id) }}" method="POST" id="EmpleadoForm">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nombresemp" class="form-label">
                        <i class="fas fa-user text-primary"></i> Nombres:
                    </label>
                    <input type="text"
                        name="nombresemp"
                        id="nombresemp"
                        class="form-control @error('nombresemp') is-invalid @enderror"
                        placeholder="Ingrese los nombres"
                        value="{{ old('nombresemp', $empleado->nombresemp) }}"
                        required>
                    @error('nombresemp')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="apellidosemp" class="form-label">
                        <i class="fas fa-user-tag text-info"></i> Apellidos:
                    </label>
                    <input type="text"
                        name="apellidosemp"
                        id="apellidosemp"
                        class="form-control @error('apellidosemp') is-invalid @enderror"
                        placeholder="Ingrese los apellidos"
                        value="{{ old('apellidosemp', $empleado->apellidosemp) }}"
                        required>
                    @error('apellidosemp')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="telefonemp" class="form-label">
                        <i class="fas fa-phone text-warning"></i> Teléfono:
                    </label>
                    <input type="tel"
                        name="telefonemp"
                        id="telefonemp"
                        class="form-control @error('telefonemp') is-invalid @enderror"
                        placeholder="0000-0000"
                        value="{{ old('telefonemp', $empleado->telefonemp) }}">
                    @error('telefonemp')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="direccionemp" class="form-label">
                        <i class="fas fa-map-marker-alt text-success"></i> Direccion:
                    </label>
                    <input type="text"
                        name="direccionemp"
                        id="direccionemp"
                        class="form-control @error('direccionemp') is-invalid @enderror"
                        placeholder="Tu direccion..."
                        value="{{ old('direccionemp', $empleado->direccionemp) }}">
                    @error('direccionemp')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
    <label for="cargo_id" class="form-label">
        <i class="fas fa-briefcase text-primary"></i> Cargo del empleado:
    </label>
    <select name="cargo_id" id="cargo_id" 
            class="form-control @error('cargo_id') is-invalid @enderror" 
            required>
        <option value="">Seleccione el cargo</option>
        @foreach ($cargos as $cargoItem)
        <option value="{{ $cargoItem->id }}" 
                {{ old('cargo_id', $empleado->cargo_id) == $cargoItem->id ? 'selected' : '' }}>
            {{ $cargoItem->nombre }}
        </option>
        @endforeach
    </select>
    @error('cargo_id')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


                <div class="form-buttons">
                    <button type="submit" class="btn btn-success form-btn">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                    <a href="{{ route('empleados.index') }}" class="btn btn-secondary form-btn">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Configurar validación en tiempo real
        configurarValidacionTiempoReal({
            nombresemp: {
                requerido: true,
                minLength: 2,
                nombre: 'Nombres'
            },
            apellidosemp: {
                requerido: true,
                minLength: 2,
                nombre: 'Apellidos'
            },
            direccionemp: {
                requerido: false,
                minLength: 2,
                nombre: 'Direccion'
            }
        });
    });
</script>
@endsection