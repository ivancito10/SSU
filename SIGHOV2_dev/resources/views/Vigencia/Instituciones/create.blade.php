@extends('principal')

@section('contenido')

@can('registrar.institucion_convenio')
@livewire('Vigencia.Instituciones.institucion-convenio-register')
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan

@stop
