@extends('adminlte::page')

@section('title', 'Editar Pago')

@section('css')
    {{-- CSS global para formularios --}}
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <h1>Editar Pagos de los Trabajos</h1>

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

            <form action="{{ route('pagos.update', $pago->id) }}" method="POST" id="pagoForm">
                @csrf
                @method('PUT')

                {{-- Cliente --}}
                <div class="form-group">
                    <label for="cliente" class="form-label">
                        <i class="fas fa-user text-primary"></i> Cliente:
                    </label>
                    <select name="cliente" id="cliente"
                            class="form-control @error('cliente') is-invalid @enderror" required>
                        <option value="">Seleccione un cliente</option>
                         @foreach ($clientes as $clienteItem)
                            <option value="{{ $clienteItem->id }}" 
                                {{ $pago->id_cliente == $clienteItem->id ? 'selected' : '' }}>
                                {{ $clienteItem->nombrescliente }} {{ $clienteItem->apellidoscliente }}
                            </option>
                         @endforeach
                    </select>
                    @error('cliente')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Responsable --}}
                <div class="form-group">
                    <label for="responsable" class="form-label">
                        <i class="fas fa-user-tie text-info"></i> Responsable:
                    </label>
                    <select name="responsable" id="responsable"
                            class="form-control @error('responsable') is-invalid @enderror">
                        <option value="">Seleccione un responsable</option>
                         @foreach ($responsables as $responsableItem)
                            <option value="{{ $responsableItem->id }}" 
                                {{ $pago->id_responsable == $responsableItem->id ? 'selected' : '' }}>
                                {{ $responsableItem->nombresresp }} {{ $responsableItem->apellidosresp }}
                            </option>
                        @endforeach
                        
                    </select>
                    @error('responsable')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                
                
                {{-- Monto total --}}
                 <div class="form-group">
                    <label for="montototal" class="form-label">
                        <i class="fas fa-dollar-sign text-success"></i> Monto total:
                    </label>
                    <input type="number" step="0.01"
                           name="montototal" id="montototal"
                           value="{{ old('montototal', $pago->montototal) }}"
                           class="form-control @error('montototal') is-invalid @enderror" >
                    @error('montototal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                           
                {{-- abono --}}

                <div class="form-group">
                    <label for="abono" class="form-label">
                        <i class="fas fa-dollar-sign text-success"></i> Abono:
                    </label>
                    <input type="number" step="0.01"
                           name="abono" id="abono"
                           value="{{ old('abono', $pago->abono) }}"
                           class="form-control @error('abono') is-invalid @enderror" >
                    @error('abono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Fecha trabajo --}}

                <div class="form-group">
                    <label for="fechaabono" class="form-label">
                        <i class="fas fa-calendar-alt text-primary"></i> Fecha del abono:
                    </label>
                    <input type="date"
                           name="fechaabono" id="fechaabono"
                           value="{{ old('fechaabono', \Carbon\Carbon::parse($pago->fechaabono)->format('Y-m-d')) }}"
                           class="form-control @error('fechaabono') is-invalid @enderror" readonly>
                    @error('fechaabono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                
                {{-- Botones --}}
                <div class="form-buttons">
                    <button type="submit" class="btn btn-success form-btn">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                    <a href="{{ route('pagos.index') }}" class="btn btn-secondary form-btn">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
