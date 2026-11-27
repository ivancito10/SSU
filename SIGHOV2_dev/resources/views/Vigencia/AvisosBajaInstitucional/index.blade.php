@extends('principal')

@section('contenido')

@can('menu.bajas_asegurados')
<div>
  @livewire('Vigencia.aviso-baja-institucional.aviso-baja-institucional-main')
</div>
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
      esta sección.</b></em></p>
@endcan
@stop
