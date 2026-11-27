@extends('principal')

@section('contenido')

@can('menu.aportantes_voluntarios')

@livewire('Aportes.aportes-voluntarios.aportantes-voluntarios.aportantes-voluntarios-main')
@else

<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan

@stop