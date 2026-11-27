@extends('principal')

@section('contenido')

@can('menu.baja_medica_hologacion')
<div>
  @livewire('vigencia.bajas-medicas.bajas-medicas-main')
</div>
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
      esta sección.</b></em></p>
@endcan
@stop
