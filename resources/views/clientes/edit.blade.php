@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Cliente</h2>

    <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nombres:</label>
            <input type="text" name="nombrescliente" class="form-control" value="{{ $cliente->nombrescliente }}" required>
        </div>

        <div class="mb-3">
            <label>Apellidos:</label>
            <input type="text" name="apellidoscliente" class="form-control" value="{{ $cliente->apellidoscliente }}" required>
        </div>

        <div class="mb-3">
            <label>Teléfono:</label>
            <input type="text" name="telefonocliente" class="form-control" value="{{ $cliente->telefonocliente }}">
        </div>

        <div class="mb-3">
            <label>Correo:</label>
            <input type="email" name="correocliente" class="form-control" value="{{ $cliente->correocliente }}">
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
