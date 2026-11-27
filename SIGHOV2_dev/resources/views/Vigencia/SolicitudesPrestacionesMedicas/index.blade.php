@extends('principal')

@section('contenido')
    


    @can('menu.prestaciones_interior')
    <div>
        @livewire('Vigencia.solicitud-prestacion-medica.solicitud-prestacion-medica-main')
    </div>
    @else
    <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
                esta sección.</b></em></p>
    @endcan
@stop
