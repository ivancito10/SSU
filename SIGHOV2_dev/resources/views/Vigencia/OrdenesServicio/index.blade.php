@extends('principal')

@section('contenido')

@can('menu.ordenes_servicio_hologacion')

<div>
    @livewire('Vigencia.ordenes-servicio.ordenes-servicio-main')
</div>
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan
@stop
