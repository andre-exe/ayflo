// public/js/alerts.js

/**
 * Mostrar alerta de éxito
 * @param {string} mensaje 
 */
function mostrarAlertaExito(mensaje) {
    Swal.fire({
        title: '¡Éxito!',
        text: mensaje,
        icon: 'success',
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
}

/**
 * Mostrar alerta de éxito estándar (no toast)
 * @param {string} mensaje 
 */
function mostrarExito(mensaje) {
    Swal.fire({
        title: '¡Éxito!',
        text: mensaje,
        icon: 'success',
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#28a745'
    });
}

/**
 * Mostrar alerta de error
 * @param {string} mensaje 
 */
function mostrarAlertaError(mensaje) {
    Swal.fire({
        title: 'Error',
        text: mensaje,
        icon: 'error',
        confirmButtonText: 'Aceptar'
    });
}

/**
 * Confirmar eliminación
 * @param {function} callback - Función a ejecutar si confirma
 * @param {string} texto - Texto personalizado (opcional)
 */
function confirmarEliminacion(callback, texto = '¿Estás seguro de eliminar este elemento?') {
    Swal.fire({
        title: '¿Estás seguro?',
        text: texto + ' Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

/**
 * Mostrar alerta de información
 * @param {string} titulo 
 * @param {string} mensaje 
 */
function mostrarAlertaInfo(titulo, mensaje) {
    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'info',
        confirmButtonText: 'Aceptar'
    });
}

/**
 * Validar formulario antes de enviar
 * @param {string} formId - ID del formulario
 * @param {object} campos - Objeto con los campos requeridos
 */
function validarFormulario(formId, campos) {
    const form = document.getElementById(formId);
    
    form.addEventListener('submit', function(e) {
        let errores = [];
        
        for (let campo in campos) {
            const input = document.getElementById(campo);
            const valor = input.value.trim();
            
            if (campos[campo].requerido && valor === '') {
                errores.push(campos[campo].nombre + ' es obligatorio');
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        }
        
        if (errores.length > 0) {
            e.preventDefault();
            Swal.fire({
                title: 'Campos obligatorios',
                html: '<ul style="text-align: left;">' + 
                      errores.map(error => '<li>' + error + '</li>').join('') + 
                      '</ul>',
                icon: 'warning',
                confirmButtonText: 'Entendido'
            });
        }
    });
}

/**
 * Configurar validación en tiempo real para formularios
 * @param {object} campos - Objeto con configuración de campos
 */
function configurarValidacionTiempoReal(campos) {
    for (let campoId in campos) {
        const input = document.getElementById(campoId);
        const config = campos[campoId];
        
        if (input) {
            input.addEventListener('input', function() {
                const valor = this.value.trim();
                
                // Validar campo requerido
                if (config.requerido && valor === '') {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                    return;
                }
                
                // Validar email
                if (config.tipo === 'email' && valor !== '') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(valor)) {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                        return;
                    }
                }
                
                // Validar longitud mínima
                if (config.minLength && valor.length < config.minLength) {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                    return;
                }
                
                // Si pasa todas las validaciones
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            });
        }
    }
}