@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->

    @can('menu.suspension_ecografia')
        @livewire('plataforma.agendamiento.ecografia.suspension-horario-ecografia.suspension-horario-main')
    @else
        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para
                    acceder a
                    esta sección.</b></em></p>
    @endcan
@stop
