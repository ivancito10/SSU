@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
  <!-- ACA EL MINISTERIO DE SALUD-->
    
    @can('menu.liquidaciones_ministerio')
    @livewire('aportes.liquidaciones.ministerio.ministerio-main')
    @else

        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
    @endcan
    
@stop
