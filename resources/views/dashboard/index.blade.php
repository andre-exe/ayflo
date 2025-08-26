@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')
    <div class="container-fluid">
        <!-- Sección de bienvenida con tarjetas integradas -->
        <div class="row">
            <div class="col-12">
                <div class="card modern-welcome-card">
                    <div class="card-body py-5">
                        <div class="welcome-content">
                            <div class="text-center mb-4">
                                <div class="welcome-logo mb-3">
                                    <svg width="80" height="80" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                                        <!-- Cuadrado azul claro de fondo -->
                                        <rect x="30" y="10" width="60" height="60" fill="#5DADE2" transform="rotate(45 60 40)"/>
                                        <!-- Cuadrado azul oscuro -->
                                        <rect x="10" y="30" width="60" height="60" fill="#2E86C1" transform="rotate(45 40 60)"/>
                                        <!-- Cuadrado azul medio -->
                                        <rect x="50" y="30" width="60" height="60" fill="#3498DB" transform="rotate(45 80 60)"/>
                                    </svg>
                                </div>
                                <h2 class="welcome-title">¡Bienvenido a AYFLO SYSTEM!</h2>
                                <p class="welcome-description text-muted mb-4">
                                    Tu centro de comando para gestionar y monitorear todas las tareas de manera eficiente.
                                </p>
                            </div>
                            
                            <!-- Tarjetas de métricas integradas -->
                            <div class="row">
                                @foreach ($cards as $index => $card)
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                        <div class="info-box modern-card card-hover-effect" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                                            <div class="info-box-icon bg-{{ $card['color'] }} modern-icon">
                                                <i class="{{ $card['icon'] }}"></i>
                                            </div>

                                            <div class="info-box-content modern-content">
                                                <span class="info-box-text">
                                                    {{ ucfirst($card['label']) }}
                                                </span>
                                                <span class="info-box-number">
                                                    {{ $card['value'] }}
                                                </span>
                                                <div class="progress mt-2">
                                                    <div class="progress-bar bg-{{ $card['color'] }}" style="width: {{ rand(60, 95) }}%"></div>
                                                </div>
                                                <span class="progress-description text-muted">
                                                    <i class="fas fa-arrow-up text-success mr-1"></i>
                                                    {{ rand(5, 25) }}% más que el mes anterior
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    /* Estilos modernos para el dashboard */
    
    /* Tarjetas modernas */
    .modern-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        background: white;
        overflow: hidden;
        position: relative;
    }
    
    .modern-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--card-gradient-start), var(--card-gradient-end));
    }
    
    .card-hover-effect:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    
    .modern-icon {
        border-radius: 50%;
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .modern-content {
        padding: 20px;
    }
    
    .info-box-text {
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        margin-bottom: 8px;
    }
    
    .info-box-number {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        display: block;
        margin-bottom: 10px;
    }
    
    .progress {
        height: 6px;
        border-radius: 3px;
        background-color: #f8f9fa;
    }
    
    .progress-bar {
        border-radius: 3px;
    }
    
    .progress-description {
        font-size: 0.8rem;
        margin-top: 8px;
    }
    
    /* Tarjeta de bienvenida */
    .modern-welcome-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        position: relative;
        overflow: hidden;
    }
    
    .modern-welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(102,126,234,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }
    
    .welcome-content {
        position: relative;
        z-index: 2;
    }
    
    .welcome-logo {
        display: inline-block;
        animation: float 3s ease-in-out infinite;
    }
    
    .welcome-logo svg {
        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
        transition: transform 0.3s ease;
    }
    
    .welcome-logo:hover svg {
        transform: scale(1.1);
    }
    
    .welcome-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
    }
    
    .welcome-description {
        font-size: 1.1rem;
        line-height: 1.6;
        max-width: 600px;
        margin: 0 auto;
    }
    

    
    /* Colores personalizados para las tarjetas */
    .bg-primary { --card-gradient-start: #667eea; --card-gradient-end: #764ba2; }
    .bg-success { --card-gradient-start: #56ab2f; --card-gradient-end: #a8e6cf; }
    .bg-warning { --card-gradient-start: #f093fb; --card-gradient-end: #f5576c; }
    .bg-info { --card-gradient-start: #4facfe; --card-gradient-end: #00f2fe; }
    .bg-danger { --card-gradient-start: #fa709a; --card-gradient-end: #fee140; }
    
    /* Responsivo */
    @media (max-width: 768px) {
        .welcome-title {
            font-size: 2rem;
        }
        
        .welcome-logo svg {
            width: 60px;
            height: 60px;
        }
        
        .modern-icon {
            width: 60px;
            height: 60px;
            font-size: 1.3rem;
        }
        
        .info-box-number {
            font-size: 1.5rem;
        }
    }
    
    /* Animaciones de entrada */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .modern-card {
        animation: fadeInUp 0.6s ease forwards;
    }
</style>
@stop

@section('js')
<script>
    // Animaciones y efectos interactivos
    document.addEventListener('DOMContentLoaded', function() {
        // Efecto de contador animado para los números
        const numbers = document.querySelectorAll('.info-box-number');
        numbers.forEach(number => {
            const finalValue = parseInt(number.textContent.replace(/\D/g, '')) || 0;
            if (finalValue > 0) {
                animateNumber(number, 0, finalValue, 1500);
            }
        });
        
        // Efecto de hover mejorado
        const cards = document.querySelectorAll('.modern-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
    
    function animateNumber(element, start, end, duration) {
        const range = end - start;
        const increment = range / (duration / 16);
        let current = start;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= end) {
                current = end;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current).toLocaleString();
        }, 16);
    }
    
    // Actualización de fecha y hora en tiempo real
    function updateDateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        const dateTimeString = now.toLocaleDateString('es-ES', options);
        
        const dateElement = document.querySelector('.dashboard-subtitle small');
        if (dateElement) {
            dateElement.textContent = dateTimeString;
        }
    }
    
    // Actualizar cada minuto
    setInterval(updateDateTime, 60000);
</script>
@stop