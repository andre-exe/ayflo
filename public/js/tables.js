// public/js/tables.js - Funciones reutilizables para vistas de listado

/**
 * Inicializar vista de listado con funcionalidades estándar
 * @param {object} config - Configuración de la vista
 */
function initListView(config = {}) {
    const defaults = {
        deleteButtonClass: '.btn-delete-record',
        searchInputId: '#search-input',
        enableAnimations: true,
        enableSearch: false,
        deleteConfirmTitle: '¿Estás seguro?',
        deleteConfirmText: 'Esta acción no se puede deshacer.',
        successMessage: null
    };
    
    const settings = Object.assign(defaults, config);
    
    // Mostrar mensaje de éxito si existe
    if (settings.successMessage) {
        mostrarAlertaExito(settings.successMessage);
    }
    
    // Configurar eliminación
    if (settings.deleteButtonClass) {
        setupDeleteButtons(settings);
    }
    
    // Configurar búsqueda
    if (settings.enableSearch && settings.searchInputId) {
        setupSearch(settings.searchInputId);
    }
    
    // Animaciones de entrada
    if (settings.enableAnimations) {
        animateTableRows();
    }
    
    // Tooltips en botones
    initTooltips();
}

/**
 * Configurar botones de eliminar
 * @param {object} settings 
 */
function setupDeleteButtons(settings) {
    $(document).on('click', settings.deleteButtonClass, function(e) {
        e.preventDefault();
        
        const recordId = $(this).data('id');
        const recordName = $(this).data('name') || 'este registro';
        const formId = $(this).data('form') || `delete-form-${recordId}`;
        
        const customText = settings.deleteConfirmText.includes('{name}') 
            ? settings.deleteConfirmText.replace('{name}', recordName)
            : `¿Quieres eliminar "${recordName}"? ${settings.deleteConfirmText}`;
        
        confirmarEliminacion(() => {
            $(`#${formId}`).submit();
        }, customText);
    });
}

/**
 * Configurar búsqueda en tiempo real
 * @param {string} searchInputId 
 */
function setupSearch(searchInputId) {
    let searchTimeout;
    
    $(searchInputId).on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterTableRows(searchTerm);
        }, 300);
    });
}

/**
 * Filtrar filas de la tabla
 * @param {string} searchTerm 
 */
function filterTableRows(searchTerm) {
    $('.custom-table tbody tr').each(function() {
        const rowText = $(this).text().toLowerCase();
        const shouldShow = rowText.includes(searchTerm);
        
        $(this).toggle(shouldShow);
        
        if (shouldShow) {
            $(this).removeClass('d-none').addClass('fade-in-up');
        }
    });
    
    // Mostrar mensaje si no hay resultados
    updateEmptyState(searchTerm);
}

/**
 * Actualizar estado vacío según búsqueda
 * @param {string} searchTerm 
 */
function updateEmptyState(searchTerm) {
    const visibleRows = $('.custom-table tbody tr:visible').length;
    
    if (visibleRows === 0 && searchTerm) {
        if (!$('.search-empty-state').length) {
            $('.table-container').append(`
                <div class="search-empty-state empty-state">
                    <i class="fas fa-search"></i>
                    <h4>No se encontraron resultados</h4>
                    <p>No hay registros que coincidan con "${searchTerm}"</p>
                </div>
            `);
        }
    } else {
        $('.search-empty-state').remove();
    }
}

/**
 * Animar entrada de filas
 */
function animateTableRows() {
    $('.custom-table tbody tr').each(function(index) {
        $(this).delay(index * 100).fadeIn(500);
    });
}

/**
 * Inicializar tooltips
 */
function initTooltips() {
    if (typeof $().tooltip === 'function') {
        $('[title]').tooltip();
    }
}

/**
 * Configurar contador de registros
 * @param {string} containerSelector 
 * @param {string} singularText 
 * @param {string} pluralText 
 */
function updateRecordCount(containerSelector, singularText = 'registro', pluralText = 'registros') {
    const count = $('.custom-table tbody tr:visible').length;
    const text = count === 1 ? singularText : pluralText;
    
    $(containerSelector).html(`
        <i class="fas fa-chart-line text-primary mr-2"></i>
        <strong>${count}</strong> ${text}
    `);
}

/**
 * Configurar ordenamiento de tabla
 * @param {array} sortableColumns - Índices de columnas ordenables
 */
function setupTableSorting(sortableColumns = []) {
    sortableColumns.forEach(columnIndex => {
        $(`.custom-table thead th:eq(${columnIndex})`).addClass('sortable-column');
    });
    
    $('.sortable-column').css('cursor', 'pointer').on('click', function() {
        const columnIndex = $(this).index();
        sortTableByColumn(columnIndex);
    });
}

/**
 * Ordenar tabla por columna
 * @param {number} columnIndex 
 */
function sortTableByColumn(columnIndex) {
    const table = $('.custom-table');
    const tbody = table.find('tbody');
    const rows = tbody.find('tr').toArray();
    
    const isAscending = !$(`.custom-table thead th:eq(${columnIndex})`).hasClass('sort-desc');
    
    rows.sort((a, b) => {
        const aValue = $(a).find('td').eq(columnIndex).text().trim();
        const bValue = $(b).find('td').eq(columnIndex).text().trim();
        
        if (isAscending) {
            return aValue.localeCompare(bValue, undefined, { numeric: true });
        } else {
            return bValue.localeCompare(aValue, undefined, { numeric: true });
        }
    });
    
    // Actualizar clases de ordenamiento
    $('.custom-table thead th').removeClass('sort-asc sort-desc');
    $(`.custom-table thead th:eq(${columnIndex})`).addClass(isAscending ? 'sort-asc' : 'sort-desc');
    
    // Reorganizar filas
    tbody.empty().append(rows);
}

/**
 * Exportar tabla a CSV
 * @param {string} filename 
 */
function exportTableToCSV(filename = 'datos.csv') {
    let csv = [];
    
    // Headers
    $('.custom-table thead tr').each(function() {
        const row = [];
        $(this).find('th').each(function() {
            row.push('"' + $(this).text().trim() + '"');
        });
        csv.push(row.join(','));
    });
    
    // Data
    $('.custom-table tbody tr:visible').each(function() {
        const row = [];
        $(this).find('td').each(function() {
            row.push('"' + $(this).text().trim() + '"');
        });
        csv.push(row.join(','));
    });
    
    // Download
    const csvString = csv.join('\n');
    const blob = new Blob([csvString], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.click();
    window.URL.revokeObjectURL(url);
}

/**
 * Configurar selección múltiple
 */
function setupBulkSelection() {
    // Checkbox principal
    $(document).on('change', '#select-all', function() {
        $('.row-checkbox').prop('checked', this.checked);
        updateBulkActions();
    });
    
    // Checkboxes individuales
    $(document).on('change', '.row-checkbox', function() {
        updateSelectAll();
        updateBulkActions();
    });
}

/**
 * Actualizar checkbox principal
 */
function updateSelectAll() {
    const total = $('.row-checkbox').length;
    const checked = $('.row-checkbox:checked').length;
    
    $('#select-all').prop('checked', total === checked);
    $('#select-all').prop('indeterminate', checked > 0 && checked < total);
}

/**
 * Actualizar acciones masivas
 */
function updateBulkActions() {
    const selectedCount = $('.row-checkbox:checked').length;
    
    if (selectedCount > 0) {
        $('.bulk-actions').show();
        $('.bulk-count').text(selectedCount);
    } else {
        $('.bulk-actions').hide();
    }
}