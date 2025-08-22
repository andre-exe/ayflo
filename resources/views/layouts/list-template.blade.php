@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
    {{-- SweetAlert2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    {{-- CSS para ocultar iconos de paginación --}}
    <style>
        /* Ocultar todos los iconos SVG de la paginación */
        .pagination .page-link svg {
            display: none !important;
        }
        
        /* Agregar texto simple para navegación */
        .pagination .page-link[rel="prev"]:after {
            content: "‹ Anterior";
            font-size: 14px;
        }
        
        .pagination .page-link[rel="next"]:after {
            content: "Siguiente ›";
            font-size: 14px;
        }
        
        /* Para casos donde no use rel, usar aria-label */
        .pagination .page-link[aria-label*="Previous"]:after,
        .pagination .page-link[aria-label*="Anterior"]:after {
            content: "‹ Anterior";
            font-size: 14px;
        }
        
        .pagination .page-link[aria-label*="Next"]:after,
        .pagination .page-link[aria-label*="Siguiente"]:after {
            content: "Siguiente ›";
            font-size: 14px;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header principal -->
    <div class="list-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1>
                    <i class="{{ $headerIcon ?? 'fas fa-list' }} mr-2"></i>
                    {{ $headerTitle }}
                </h1>
                <p class="mb-0">{{ $headerSubtitle ?? '' }}</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
    @if(isset($statsCards))
        @foreach($statsCards as $card)
            <div class="stats-card {{ $card['type'] ?? '' }} mr-2 mb-2 text-dark">
                <i class="{{ $card['icon'] }} text-{{ $card['color'] ?? 'primary' }} mr-2"></i>
                <strong>{{ $card['value'] }}</strong> {{ $card['label'] }}
            </div>
        @endforeach
    @endif
</div>

        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- Contenedor principal -->
    <div class="table-container">
        <!-- Header de tabla -->
        <div class="table-header">
            <h3 class="table-title">
                <i class="{{ $tableIcon ?? 'fas fa-list' }} mr-2"></i>
                {{ $tableTitle }}
            </h3>
            
            <div class="d-flex gap-2 flex-wrap">
                @if(isset($enableSearch) && $enableSearch)
                    <div class="search-box mr-3">
                        <input type="text" id="search-input" class="form-control" placeholder="Buscar...">
                        <i class="fas fa-search"></i>
                    </div>
                @endif
                
                @if(isset($createRoute))
                    <a href="{{ $createRoute }}" class="btn btn-new-record {{ $createButtonClass ?? '' }}">
                        <i class="{{ $createIcon ?? 'fas fa-plus' }} mr-2"></i>
                        {{ $createButtonText }}
                    </a>
                @endif
                
                @if(isset($extraButtons))
                    @foreach($extraButtons as $button)
                        <a href="{{ $button['route'] }}" class="btn {{ $button['class'] }}">
                            <i class="{{ $button['icon'] }} mr-2"></i>
                            {{ $button['text'] }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        @if(isset($enableFilters) && $enableFilters)
            <!-- Filtros -->
            <div class="table-filters">
                @yield('filters')
            </div>
        @endif
        
        @if($records->count())
            <!-- Tabla -->
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            @if(isset($enableBulkActions) && $enableBulkActions)
                                <th width="5%">
                                    <input type="checkbox" id="select-all">
                                </th>
                            @endif
                            @yield('table-headers')
                        </tr>
                    </thead>
                    <tbody>
                        @yield('table-rows')
                    </tbody>
                </table>
            </div>

            @if(isset($enablePagination) && $enablePagination)
                <!-- Paginación -->
                <div class="pagination-container">
                    {{ $records->links() }}
                </div>
            @endif
        @else
            <!-- Estado vacío -->
            <div class="empty-state">
                <i class="{{ $emptyIcon ?? 'fas fa-inbox' }}"></i>
                <h4>{{ $emptyTitle ?? 'No hay registros' }}</h4>
                <p>{{ $emptyMessage ?? 'No se han encontrado registros para mostrar.' }}</p>
                @if(isset($createRoute))
                    <a href="{{ $createRoute }}" class="btn btn-new-record mt-3">
                        <i class="{{ $createIcon ?? 'fas fa-plus' }} mr-2"></i>
                        {{ $emptyButtonText ?? $createButtonText }}
                    </a>
                @endif
            </div>
        @endif

        @if(isset($enableBulkActions) && $enableBulkActions)
            <!-- Acciones masivas -->
            <div class="bulk-actions" style="display: none;">
                <div class="alert alert-info">
                    <span class="bulk-count">0</span> elementos seleccionados
                    @yield('bulk-actions')
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('js')
    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- Incluir alerts.js --}}
    <script src="{{ asset('js/alert.js') }}"></script>
    
    {{-- Incluir tables.js --}}
    <script src="{{ asset('js/tables.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            // Configuración base
            const config = {
                deleteButtonClass: '{{ $deleteButtonClass ?? ".btn-delete-record" }}',
                enableSearch: {{ isset($enableSearch) && $enableSearch ? 'true' : 'false' }},
                enableAnimations: {{ isset($enableAnimations) && $enableAnimations ? 'true' : 'false' }},
                successMessage: @if(session('success')) "{{ session('success') }}" @else null @endif,
                deleteConfirmText: '{{ $deleteConfirmText ?? "Esta acción no se puede deshacer." }}'
            };
            
            // Inicializar vista
            initListView(config);

            @if(isset($enableBulkActions) && $enableBulkActions)
                setupBulkSelection();
            @endif

            @if(isset($sortableColumns))
                setupTableSorting({!! json_encode($sortableColumns) !!});
            @endif
            
            // Scripts adicionales específicos de la vista
            @yield('additional-scripts')
        });
    </script>
@endsection