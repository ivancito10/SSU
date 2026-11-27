@extends('principal')

@section('contenido')

@can('menu.lista_agenda_archivo_clinico')
<!-- Contenido específico de la vista Index -->
@livewire('archivo-clinico.lista-agendas.lista-agendas-main')
@else

<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan

@stop
