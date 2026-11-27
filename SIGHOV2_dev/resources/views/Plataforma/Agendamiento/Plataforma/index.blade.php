@extends('principal')

@section('contenido')

    @can('menu.agenda_plataforma')
        @livewire('Plataforma.agendamiento.plataforma.agenda-main')
    @else
        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
                    esta sección.</b></em></p>
    @endcan

@stop
