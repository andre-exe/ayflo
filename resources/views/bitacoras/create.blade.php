@extends('adminlte::page')

@section('title', 'Registrar Bitácora')

@section('css')
    {{-- CSS global para formularios --}}
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <h1>Registrar Bitácora</h1>

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

            <form action="{{ route('bitacoras.store') }}" method="POST" id="bitacoraForm">
                @csrf

                {{-- Cliente --}}
                <div class="form-group">
                    <label for="cliente" class="form-label">
                        <i class="fas fa-user text-primary"></i> Cliente:
                    </label>
                    <select name="cliente" id="cliente"
                            class="form-control @error('cliente') is-invalid @enderror" required>
                        <option value="">Seleccione un cliente</option>
                        @foreach ($clientes as $cli)
                            <option value="{{ $cli->id }}">{{ $cli->nombrescliente }}</option>
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
                        @foreach ($responsables as $resp)
                            <option value="{{ $resp->id }}">{{ $resp->nombres }}</option>
                        @endforeach
                    </select>
                    @error('responsable')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Trabajo --}}
                <div class="form-group">
                    <label for="idtrabajo" class="form-label">
                        <i class="fas fa-briefcase text-warning"></i> Trabajo:
                    </label>
                    <select name="idtrabajo" id="trabajoSelect"
                            class="form-control @error('idtrabajo') is-invalid @enderror" required>
                        <option value="">Seleccione un trabajo</option>
                        @foreach ($trabajos as $trabajo)
                            <option value="{{ $trabajo->id }}"
                                    data-monto="{{ $trabajo->montototal }}"
                                    data-fecha="{{ $trabajo->fechatrabajo }}">
                                {{ $trabajo->id }}
                            </option>
                        @endforeach
                    </select>
                    @error('idtrabajo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Monto (autocompletado) --}}
                <div class="form-group">
                    <label for="monto" class="form-label">
                        <i class="fas fa-dollar-sign text-success"></i> Monto:
                    </label>
                    <input type="number" step="0.01"
                           name="monto" id="montoInput"
                           class="form-control @error('monto') is-invalid @enderror" readonly>
                    @error('monto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Fecha (autocompletada) --}}
                <div class="form-group">
                    <label for="fechatrabajobitacora" class="form-label">
                        <i class="fas fa-calendar-alt text-primary"></i> Fecha del Trabajo:
                    </label>
                    <input type="date"
                           name="fechatrabajobitacora" id="fechaInput"
                           class="form-control @error('fechatrabajobitacora') is-invalid @enderror" readonly>
                    @error('fechatrabajobitacora')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div class="form-group">
                    <label for="descripcionbitacora" class="form-label">
                        <i class="fas fa-align-left text-info"></i> Descripción:
                    </label>
                    <textarea name="descripcionbitacora" id="descripcionbitacora"
                              class="form-control @error('descripcionbitacora') is-invalid @enderror"
                              placeholder="Ingrese una breve descripción"></textarea>
                    @error('descripcionbitacora')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="form-buttons">
                    <button type="submit" class="btn btn-success form-btn">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <a href="{{ route('bitacoras.index') }}" class="btn btn-secondary form-btn">
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
document.getElementById('trabajoSelect').addEventListener('change', function() {
    let selected = this.options[this.selectedIndex];
    document.getElementById('montoInput').value = selected.dataset.monto || '';
    document.getElementById('fechaInput').value = selected.dataset.fecha || '';
});
</script>
@endsection
