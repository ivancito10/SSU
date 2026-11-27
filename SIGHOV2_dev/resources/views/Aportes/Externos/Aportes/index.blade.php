@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
    @can('menu.aportes_externos')
    @livewire('aportes.externos.aporte.aporte-institucion-main-ext')
    @else

        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
    @endcan

    
    
    
@stop
