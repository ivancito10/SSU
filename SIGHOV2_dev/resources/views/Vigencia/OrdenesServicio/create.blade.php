@extends('principal')

@section('contenido')
<!-- Contenido específico de la vista Index -->

@can('menu.ordenes_servicio_hologacion')
<livewire:vigencia.ordenes-servicio.ordenes-servicio-register
    :idPersona="$id" />>
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan
@stop
