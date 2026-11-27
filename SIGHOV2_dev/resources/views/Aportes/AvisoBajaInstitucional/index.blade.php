@extends('principal')

@section('contenido')

    @can('menu.registro_aviso_baja')
        @livewire('Aportes.Externos.aviso-baja.aviso-baja-main')
    @else
        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
    @endcan
@stop
