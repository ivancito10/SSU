@extends('principal')

@section('contenido')

@can('menu.anulacion_fichas')
@livewire('Plataforma.Anulacion.anulacion-main')
@else

<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan

@stop
