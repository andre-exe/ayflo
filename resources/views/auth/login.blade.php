@extends('adminlte::auth.login')

@section('auth_header', 'Bienvenido a AYFLO SYSTEM')

@section('auth_body')
    <p class="text-center">Inicia sesión para continuar</p>

   
    @parent
@endsection

@section('auth_footer')
    <small class="text-center d-block">© 2025 AYFLO - Todos los derechos reservados</small>
@endsection
