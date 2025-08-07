@extends('adminlte::page')

@section('title', 'Editar Responsable')

@section('css')
    {{-- CSS global para formularios --}}
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <h1>Editar Responsable</h1>

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

            <form action="{{ route('responsables.update', $responsable->id) }}" method="POST" id="responsableForm">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="nombresresp" class="form-label">
                        <i class="fas fa-user text-primary"></i> Nombres:
                    </label>
                    <input type="text" 
                           name="nombresresp" 
                           id="nombresresp"
                           class="form-control @error('nombresresp') is-invalid @enderror" 
                           placeholder="Ingrese los nombres"
                           value="{{ old('nombresresp', $responsable->nombresresp) }}"
                           required>
                    @error('nombresresp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="apellidosresp" class="form-label">
                        <i class="fas fa-user-tag text-info"></i> Apellidos:
                    </label>
                    <input type="text" 
                           name="apellidosresp" 
                           id="apellidosresp"
                           class="form-control @error('apellidosresp') is-invalid @enderror" 
                           placeholder="Ingrese los apellidos"
                           value="{{ old('apellidosresp', $responsable->apellidosresp) }}"
                           required>
                    @error('apellidosresp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="correoresp" class="form-label">
                        <i class="fas fa-envelope text-success"></i> Correo:
                    </label>
                    <input type="email" 
                           name="correoresp" 
                           id="correoresp"
                           class="form-control @error('correoresp') is-invalid @enderror" 
                           placeholder="ejemplo@correo.com"
                           value="{{ old('correoresp', $responsable->correoresp) }}">
                    @error('correoresp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="telefonoresp" class="form-label">
                        <i class="fas fa-phone text-warning"></i> Teléfono:
                    </label>
                    <input type="tel" 
                           name="telefonoresp" 
                           id="telefonoresp"
                           class="form-control @error('telefonoresp') is-invalid @enderror" 
                           placeholder="0000-0000"
                           value="{{ old('telefonoresp', $responsable->telefonoresp) }}">
                    @error('telefonoresp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
    <label for="cliente" class="form-label">
        <i class="fas fa-users text-primary"></i> Cliente relacionado:
    </label>
    <select name="cliente" id="cliente" class="form-control @error('cliente') is-invalid @enderror" required>
        <option value="">Seleccione un cliente</option>
        @foreach ($clientes as $cliente)
            <option value="{{ $cliente->id }}" 
                {{ old('cliente', $responsable->cliente) == $cliente->id ? 'selected' : '' }}>
                {{ $cliente->nombrescliente }} {{ $cliente->apellidoscliente }}
            </option>
        @endforeach
    </select>
    @error('cliente')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


                <div class="form-buttons">
                    <button type="submit" class="btn btn-success form-btn">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                    <a href="{{ route('responsables.index') }}" class="btn btn-secondary form-btn">
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
                nombresresp: {
                    requerido: true,
                    minLength: 2,
                    nombre: 'Nombres'
                },
                apellidosresp: {
                    requerido: true,
                    minLength: 2,
                    nombre: 'Apellidos'
                },
                correoresp: {
                    requerido: false,
                    tipo: 'email',
                    nombre: 'Correo'
                }
            });
        });
    </script>
@endsection