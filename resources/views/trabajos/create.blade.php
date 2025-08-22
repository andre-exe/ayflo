@extends('adminlte::page')

@section('title', 'Registrar Trabajo')

@section('css')
<link rel="stylesheet" href="{{ asset('css/forms.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <h1>Registrar Trabajo</h1>

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

            <form action="{{ route('trabajos.store') }}" method="POST" id="trabajoForm" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cliente" class="form-label">
                                <i class="fas fa-user-tie text-primary"></i> Cliente:
                            </label>
                            <select name="cliente" id="cliente" class="form-control @error('cliente') is-invalid @enderror" required>
                                <option value="">Seleccione el cliente</option>
                                @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombrescliente }} {{ $cliente->apellidoscliente }}
                                </option>
                                @endforeach
                            </select>
                            @error('cliente')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="responsable" class="form-label">
                                <i class="fas fa-user-shield text-success"></i> Responsable:
                            </label>
                            <select name="responsable" id="responsable" class="form-control @error('responsable') is-invalid @enderror">
                                <option value="">Seleccione el responsable</option>
                                @foreach ($responsables as $responsable)
                                <option value="{{ $responsable->id }}" {{ old('responsable') == $responsable->id ? 'selected' : '' }}>
                                    {{ $responsable->nombresresp }} {{ $responsable->apellidosresp }}
                                </option>
                                @endforeach
                            </select>
                            @error('responsable')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="empleados" class="form-label">
                                <i class="fas fa-user text-info"></i> Empleado Asignado:
                            </label>
                            <select name="empleado" id="empleado" class="form-control @error('empleado') is-invalid @enderror" required>
    <option value="">Seleccione el empleado</option>
    @foreach ($empleados as $empleado)
    <option value="{{ $empleado->id }}" {{ old('empleado') == $empleado->id ? 'selected' : '' }}>
        {{ $empleado->nombresemp }} {{ $empleado->apellidosemp }}
    </option> 
    @endforeach
</select>
@error('empleado')
<div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fechatrabajo" class="form-label">
                                <i class="fas fa-calendar text-warning"></i> Fecha del Trabajo:
                            </label>
                            <input type="date"
                                name="fechatrabajo"
                                id="fechatrabajo"
                                class="form-control @error('fechatrabajo') is-invalid @enderror"
                                value="{{ old('fechatrabajo') }}"
                                required>
                            @error('fechatrabajo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="estado" class="form-label">
                                <i class="fas fa-flag text-primary"></i> Estado:
                            </label>
                            <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                                <option value="">Seleccione el estado</option>
                                <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="en_progreso" {{ old('estado') == 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
                                <option value="completado" {{ old('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                                <option value="cancelado" {{ old('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="montototal" class="form-label">
                                <i class="fas fa-dollar-sign text-success"></i> Monto Total ($):
                            </label>
                            <input type="number"
                                name="montototal"
                                id="montototal"
                                class="form-control @error('montototal') is-invalid @enderror"
                                placeholder="0.00"
                                step="0.01"
                                min="0"
                                value="{{ old('montototal', '0.00') }}">
                            @error('montototal')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="montopagado" class="form-label">
                                <i class="fas fa-money-check text-success"></i> Monto Pagado ($):
                            </label>
                            <input type="number"
                                name="montopagado"
                                id="montopagado"
                                class="form-control @error('montopagado') is-invalid @enderror"
                                placeholder="0.00"
                                step="0.01"
                                min="0"
                                value="{{ old('montopagado', '0.00') }}">
                            @error('montopagado')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                    <label for="nombretrb" class="form-label">
                        <i class="fas fa-user-tag text-info"></i> Nombre del trabajo:
                    </label>
                    <input type="text" 
                           name="nombretrb" 
                           id="nombretrb"
                           class="form-control @error('nombretrb') is-invalid @enderror" 
                           placeholder="Descripcion de trabajo"
                           value="{{ old('nombretrb') }}"
                           required>
                    @error('nombretrb')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Sección de Archivos -->
                <hr>
                <h5><i class="fas fa-folder text-info"></i> Archivos del Proyecto</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="archivoescritura" class="form-label">
                                <i class="fas fa-file-archive text-secondary"></i> Archivos de Escritura:
                            </label>
                            <input type="file"
                                name="archivoescritura[]"
                                id="archivoescritura"
                                class="form-control-file @error('archivoescritura') is-invalid @enderror"
                                multiple
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.rar">
                            <small class="form-text text-muted">Formatos: PDF, DOC, DOCX, XLS, XLSX, ZIP, RAR</small>
                            @error('archivosestructura')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="puntosrecorrido" class="form-label">
                                <i class="fas fa-route text-danger"></i> Puntos de Recorrido:
                            </label>
                            <input type="file"
                                name="puntosrecorrido[]"
                                id="puntosrecorrido"
                                class="form-control-file @error('puntosrecorrido') is-invalid @enderror"
                                multiple
                                accept=".gpx,.kml,.csv,.txt,.pdf">
                            <small class="form-text text-muted">Formatos: GPX, KML, CSV, TXT, PDF</small>
                            @error('puntosrecorrido')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="archivodwg" class="form-label">
                                <i class="fas fa-drafting-compass text-primary"></i> Archivos DWG:
                            </label>
                            <input type="file"
                                name="archivodwg[]"
                                id="archivodwg"
                                class="form-control-file @error('archivodwg') is-invalid @enderror"
                                multiple
                                accept=".dwg,.dxf,.pdf">
                            <small class="form-text text-muted">Formatos: DWG, DXF, PDF</small>
                            @error('archivodwg')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="archivokml" class="form-label">
                                <i class="fas fa-map-marked text-success"></i> Archivos KML:
                            </label>
                            <input type="file"
                                name="archivokml[]"
                                id="archivokml"
                                class="form-control-file @error('archivokml') is-invalid @enderror"
                                multiple
                                accept=".kml,.kmz,.gpx">
                            <small class="form-text text-muted">Formatos: KML, KMZ, GPX</small>
                            @error('archivokml')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="notas" class="form-label">
                                <i class="fas fa-sticky-note text-warning"></i> Notas:
                            </label>
                            <input type="file"
                                name="notas[]"
                                id="notas"
                                class="form-control-file @error('notas') is-invalid @enderror"
                                multiple
                                accept=".txt,.pdf,.doc,.docx">
                            <small class="form-text text-muted">Formatos: TXT, PDF, DOC, DOCX</small>
                            @error('notas')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insumos" class="form-label">
                                <i class="fas fa-boxes text-info"></i> Insumos:
                            </label>
                            <input type="file"
                                name="insumos[]"
                                id="insumos"
                                class="form-control-file @error('insumos') is-invalid @enderror"
                                multiple
                                accept=".pdf,.xls,.xlsx,.csv,.txt">
                            <small class="form-text text-muted">Formatos: PDF, XLS, XLSX, CSV, TXT</small>
                            @error('insumos')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                 <div class="col-md-6">
                        <div class="form-group">
                            <label for="archivoesquema" class="form-label">
                                <i class="fas fa-boxes text-info"></i> Archivo Esquema:
                            </label>
                            <input type="file"
                                name="archivoesquema[]"
                                id="archivoesquema"
                                class="form-control-file @error('archivoesquema') is-invalid @enderror"
                                multiple
                                accept=".pdf,.xls,.xlsx,.csv,.txt">
                            <small class="form-text text-muted">Formatos: PDF, XLS, XLSX, CSV, TXT</small>
                            @error('archivoesquema')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-success form-btn">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <a href="{{ route('trabajos.index') }}" class="btn btn-secondary form-btn">
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
            cliente: {
                requerido: true,
                nombre: 'Cliente'
            },
            responsable: {
                requerido: true,
                nombre: 'Responsable'
            },
            empleado: {
                requerido: true,
                nombre: 'Empleado'
            },
            fechatrabajo: {
                requerido: true,
                tipo: 'date',
                nombre: 'Fecha del Trabajo'
            },
            monototal: {
                requerido: false,
                tipo: 'number',
                min: 0,
                nombre: 'Monto Total'
            },
            montopagado: {
                requerido: false,
                tipo: 'number',
                min: 0,
                nombre: 'Monto Pagado'
            }
        });

        // Validar que el monto pagado no sea mayor al monto total
        $('#montopagado, #montototal').on('input', function() {
            var montoTotal = parseFloat($('#montototal').val()) || 0;
            var montoPagado = parseFloat($('#montopagado').val()) || 0;
            
            if (montoPagado > montoTotal && montoTotal > 0) {
                $('#montopagado').addClass('is-invalid');
                if (!$('#montopagado').next('.invalid-feedback').length) {
                    $('#montopagado').after('<div class="invalid-feedback">El monto pagado no puede ser mayor al monto total.</div>');
                }
            } else {
                $('#montopagado').removeClass('is-invalid');
                $('#montopagado').next('.invalid-feedback').remove();
            }
        });
    });
</script>
@endsection