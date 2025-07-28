@extends('adminlte::page')

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Cliente</h2>

    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nombres:</label>
            <input type="text" name="nombrescliente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Apellidos:</label>
            <input type="text" name="apellidoscliente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Teléfono:</label>
            <input type="text" name="telefonocliente" class="form-control">
        </div>

        <div class="mb-3">
            <label>Correo:</label>
            <input type="email" name="correocliente" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
