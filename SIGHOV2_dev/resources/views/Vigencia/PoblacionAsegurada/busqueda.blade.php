@extends('principal')

@section('contenido')

    @can('menu.poblacion_asegurada')
        <div>
            @livewire('Vigencia.poblacion-asegurada.poblacion-asegurada-busqueda')
        </div>
    @else
        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
                    esta sección.</b></em></p>
    @endcan
@stop
