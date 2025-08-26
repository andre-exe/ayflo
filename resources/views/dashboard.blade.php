@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

<div class="row">
        @foreach ($cards as $card)
            <div class="col-lg-3 col-6">
                <div class="small-box bg-{{ $card['color'] }}">
                    <div class="inner">
                        <h3>{{ $card['value'] }}</h3>
                        <p>{{ ucfirst($card['label']) }}</p>
                    </div>
                    <div class="icon">
                        <i class="{{ $card['icon'] }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    <p>Bienvenido a AYFLO SYSTEM. </p>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop