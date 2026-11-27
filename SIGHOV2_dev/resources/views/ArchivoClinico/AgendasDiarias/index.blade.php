@extends('principal')

@section('contenido')

@can('menu.agendas_diarias')
<!-- Contenido específico de la vista Index -->
@livewire('archivo-clinico.agendas-diarias.agenda-diaria-main')
@else

<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan

@stop
