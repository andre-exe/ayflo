@extends('adminlte::page')

@section('title', 'Registrar Cliente')

@section('css')
    {{-- CSS global para formularios --}}
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <h1>Registrar Cliente</h1>

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

            <form action="{{ route('clientes.store') }}" method="POST" id="clienteForm">
                @csrf
                
                <div class="form-group">
                    <label for="nombrescliente" class="form-label">
                        <i class="fas fa-user text-primary"></i> Nombres:
                    </label>
                    <input type="text" 
                           name="nombrescliente" 
                           id="nombrescliente"
                           class="form-control @error('nombrescliente') is-invalid @enderror" 
                           placeholder="Ingrese los nombres"
                           value="{{ old('nombrescliente') }}"
                           required>
                    @error('nombrescliente')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="apellidoscliente" class="form-label">
                        <i class="fas fa-user-tag text-info"></i> Apellidos:
                    </label>
                    <input type="text" 
                           name="apellidoscliente" 
                           id="apellidoscliente"
                           class="form-control @error('apellidoscliente') is-invalid @enderror" 
                           placeholder="Ingrese los apellidos"
                           value="{{ old('apellidoscliente') }}"
                           required>
                    @error('apellidoscliente')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="correocliente" class="form-label">
                        <i class="fas fa-envelope text-success"></i> Correo:
                    </label>
                    <input type="email" 
                           name="correocliente" 
                           id="correocliente"
                           class="form-control @error('correocliente') is-invalid @enderror" 
                           placeholder="ejemplo@correo.com"
                           value="{{ old('correocliente') }}">
                    @error('correocliente')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="telefonocliente" class="form-label">
                        <i class="fas fa-phone text-warning"></i> Teléfono:
                    </label>
                    <input type="tel" 
                           name="telefonocliente" 
                           id="telefonocliente"
                           class="form-control @error('telefonocliente') is-invalid @enderror" 
                           placeholder="0000-0000"
                           value="{{ old('telefonocliente') }}">
                    @error('telefonocliente')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-success form-btn">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary form-btn">
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
@endsection