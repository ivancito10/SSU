@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
    
    @can('menu.extracto_persona')
    @livewire('Aportes.extractos.aportantes.extracto-aportantes-main')
    @else

        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
    @endcan
@stop
