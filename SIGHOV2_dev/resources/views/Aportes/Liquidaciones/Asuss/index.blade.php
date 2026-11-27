@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
    
<!-- ACA EL ASUSS-->
    @can('menu.liquidaciones_asuss')
    @livewire('aportes.liquidaciones.asuss.asuss-main')
    @else

        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
    @endcan
@stop
