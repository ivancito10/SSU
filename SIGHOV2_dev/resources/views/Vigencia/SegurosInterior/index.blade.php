@extends('principal')

@section('contenido')

@can('menu.seguros_interior')
<div>
    @livewire('Vigencia.seguro-interior.seguro-interior-main')
</div>
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan
@stop
