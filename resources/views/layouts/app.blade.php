@extends('layouts.admin')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
        {{-- Menú de navegación --}}
        <nav class="bg-white shadow mb-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <ul class="flex space-x-4 py-4">
                    @role('admin')
                        <li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Panel de Administración</a></li>
                        <li><a href="{{ route('empleados.index') }}" class="text-blue-600 hover:text-blue-800">Gestionar Usuarios</a></li>
                        <li><a href="{{ route('trabajos.index') }}" class="text-blue-600 hover:text-blue-800">Trabajos</a></li>
                    @endrole
                </ul>
            </div>
        </nav>


            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts

        
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- CSS globales -->
<link rel="stylesheet" href="{{ asset('css/forms.css') }}">
<link rel="stylesheet" href="{{ asset('css/tables.css') }}">

<!-- JS globales -->
<script src="{{ asset('js/alert.js') }}"></script>
<script src="{{ asset('js/tables.js') }}"></script>

    @yield('js')
    </body>
    

</html>
