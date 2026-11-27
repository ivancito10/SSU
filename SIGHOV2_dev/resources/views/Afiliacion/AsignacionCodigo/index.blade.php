@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index ACA ASIGNACION DE CODIGO -->

    @can('menu.codigo_institucional')
    @livewire('afiliacion.asignacion-codigo.codigo-institucion-ssu-main')
    @else

        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
    @endcan
@stop
