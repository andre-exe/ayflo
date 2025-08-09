@extends('adminlte::page')

@section('title', 'Editar Egreso')

@section('css')
    {{-- CSS global para formularios --}}
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <h1>Editar Egreso</h1>

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

            <form action="{{ route('egresos.update', $egreso->id) }}" method="POST" id="egresoForm">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="montoegreso" class="form-label">
                        <i class="fas fa-user text-primary"></i> Monto:
                    </label>
                    <input type="number" 
                    step="0.01"
                           name="montoegreso" 
                           id="montoegreso"
                           class="form-control @error('montoegreso') is-invalid @enderror" 
                           placeholder="Ingrese el monto a modificar"
                           value="{{ old('montoegreso', $egreso->montoegreso) }}"
                           required>
                    @error('montoegreso')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descripcionegreso" class="form-label">
                        <i class="fas fa-user-tag text-info"></i> Apellidos:
                    </label>
                    <input type="text" 
                           name="descripcionegreso" 
                           id="descripcionegreso"
                           class="form-control @error('descripcionegreso') is-invalid @enderror" 
                           placeholder="Ingrese la descripcion de sus gastos"
                           value="{{ old('descripcionegreso', $egreso->descripcionegreso) }}"
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
                           value="{{ old('fecha', $egreso->fecha->format('Y-m-d')) }}" required>
                           
                    @error('fecha')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-success form-btn">
                        <i class="fas fa-save"></i> Actualizar
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