@extends('principal')

@section('contenido')

@can('menu.poblacion_asegurada')
<div>
    <livewire:vigencia.bajas-institucionales.bajas-institucionales-register />
</div>
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan
@stop
