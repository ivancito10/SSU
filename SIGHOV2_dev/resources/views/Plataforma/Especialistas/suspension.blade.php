@extends('principal')

@section('contenido')

@can('menu.suspension_vacaciones')
@livewire('plataforma.suspencion-vacacion.suspencion-vacacion-main')
@else

<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para
            acceder a
            esta sección.</b></em></p>
@endcan
@stop
