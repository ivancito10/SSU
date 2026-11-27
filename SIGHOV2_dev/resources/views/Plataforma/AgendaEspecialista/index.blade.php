@extends('principal')

@section('contenido')

@can('menu.agenda_especialista')
<!-- Contenido específico de la vista Index -->
@livewire('plataforma.agenda-especialista.agenda-especialista-main')
@else

<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan

@stop
