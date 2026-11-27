@extends('principal')
@section('classes_body', 'sidebar-mini layout-fixed sidebar-collapse')
@section('contenido')

    @can('menu.agenda_web')
        @livewire('plataforma.agendamiento.web.web-main')
    @else
        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
                    esta sección.</b></em></p>
    @endcan
@stop
